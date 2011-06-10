<?php
/**
* 礼品API文件
*/

/**
 * 礼品API类
 */
class Api_Gift
{
	/**
	 * 获取礼品列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET gift.getList</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getList($args, $format)
	{
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$pages = new CPagination(Gift::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$criteria->order = 'integral asc';
		$criteria->select = 'id, name, small_pic, content, integral, create_time, update_time, state';
		$giftlist= Gift::model()->findAll();
		if(!$giftlist) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'name', 'smallPic', 'content', 'integral', 'create_time', 'update_time', 'state');
		$array = array();
		foreach ($giftlist as $gift) {
			$array[] = ApiBase::object2array($gift, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取一个礼品信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET gift.get</li>
	 *  <li>(required) gift_id integer GET 礼品id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$giftId = intval($args['gift_id']);
		if($giftId <= 0) {
			throw new CException('gift_id参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$criteria->select = 'id, name, small_pic, content, integral, create_time, update_time, state';
		$gift = Gift::model()->findByPk($giftId, $criteria);
		if(!$gift) {
			throw new CException('gift_id参数错误', ApiError::ARGS_INVALID);
		}
		$attributes = array('id', 'name', 'smallPic', 'content', 'integral', 'create_time', 'update_time', 'state');
		return ApiBase::object2array($gift, $attributes);
	}
	
	/**
	 * 礼品对换  (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET gift.exchange</li>
	 *  <li>(required) gift_id integer POST 礼品id</li>
	 *  <li>(required) city_id integer POST 城市id</li>
	 *  <li>(required) consignee string POST 联系人</li>
	 *  <li>(required) address string POST 收货地址</li>
	 *  <li>(required) telphone string POST 联系电话</li>
	 *  <li>mobile string POST 联系手机 此项与电话必填一项</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function exchange($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$giftId = intval($_POST['gift_id']);
		$cityId = intval($_POST['city_id']);
		$consignee = trim(strip_tags($_POST['consignee']));
		if(empty($consignee)) {
			throw new CException('consignee参数错误', ApiError::ARGS_INVALID);
		}
		$address = trim(strip_tags($_POST['address']));
		if(empty($address)) {
			throw new CException('address参数错误', ApiError::ARGS_INVALID);
		}
		$message = trim(strip_tags($_POST['message']));
		if($_POST['telphone'] && preg_match("/^(1\d{10})|((0\d{2,3}[-——]?)?\d{7,8})$/", $_POST['telphone'])) {
			$telphone = trim(strip_tags($_POST['telphone']));
		} else {
			throw new CException('telphone参数错误', ApiError::ARGS_INVALID);
		}
		if($_POST['mobile'] && preg_match("/^(1\d{10})|((0\d{2,3}[-——]?)?\d{7,8})$/", $_POST['mobile'])) {
			$mobile = trim(strip_tags($_POST['mobile']));
		} else {
			throw new CException('mobile参数错误', ApiError::ARGS_INVALID);
		}
		if(empty($telphone) && empty($mobile)) {
			throw new CException('参数错误(telphone|mobile)必填一项', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$gift = Gift::model()->findByPk($giftId, $criteria);
		$user = User::model()->findByPk($user->id);
		if($gift->integral > $user->integral) {
			throw new CException('积分不够', ApiError::INTEGRAL_NOT_ENOUGH);
		}
		$giftexchange = new GiftExchangeLog();
		$giftexchange->gift_id = $gift->id;
		$giftexchange->user_id = $user->id;
		$giftexchange->integral = $gift->integral;
		$giftexchange->city_id = $cityId;
		$giftexchange->consignee = $consignee;
		$giftexchange->address = $address;
		$giftexchange->message = $message;
		$giftexchange->telphone = $telphone;
		$giftexchange->mobile = $mobile;
		
		if($giftexchange->save()) {
			return ApiBase::$success;
		} else {
			return ApiBase::$fail;
		}
	}
}

