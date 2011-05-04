<?php
/**
* 用户API文件
*/

/**
 * 用户API类
 */
class Api_User
{
	/**
	 * 获取用户信息 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.getInfo</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$user = User::model()->findByPk($user->id);
		
		$attributes = array('id', 'username', 'email', 'realname', 'gender', 'birthday', 'telphone', 'mobile', 'integral', 'credit', 'credit_nums', 'bcnums', 'qq', 'msn', 'city_id', 'district_id', 'update_time', 'create_time', 'last_login_time', 'login_nums', 'portraitUrl');
		return ApiBase::object2array($user, $attributes);
	}
	
	/**
	 * 更新用户信息  (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.update</li>
	 *  <li>realname string POST 真实姓名</li>
	 *  <li>birthday string POST 生日</li>
	 *  <li>telphone string POST 电话</li>
	 *  <li>mobile string POST 手机</li>
	 *  <li>qq string POST QQ</li>
	 *  <li>msn string POST MSN</li>
	 *  <li>password string POST 密码</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function update($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$user = User::model()->findByPk($user->id);
		
		if($_POST['realname'])
			$post['realname'] = trim(strip_tags($_POST['realname']));
		if($_POST['gender'])
			$post['gender'] = intval($_POST['gender']);
		if($_POST['birthday'] && preg_match("/^\d{4}-[01]\d{1}-[0123]\d{1}$/", $_POST['birthday']))
			$post['birthday'] = $_POST['birthday'];
		if($_POST['telphone'] && preg_match("/^\d+$/", $_POST['telphone']))
			$post['telphone'] = ($_POST['telphone']);
		if($_POST['mobile'] && preg_match("/^\d+$/", $_POST['mobile']))
			$post['mobile'] = ($_POST['mobile']);
		if($_POST['qq'] && preg_match("/^\d+$/", $_POST['qq']))
			$post['qq'] = intval($_POST['qq']);
		if($_POST['msn'])
			$post['msn'] = trim(strip_tags($_POST['msn']));
		if($_POST['password']) {
			$post['password'] = md5($_POST['password']);
			$post['clear_password'] = $_POST['password'];
		}
		
		$user->attributes = $post;
		if($user->save(true, array_keys($post)))
			return ApiBase::$success;
		else
			return ApiBase::$fail;
	}
	
	/**
	 * 获取用户地址列表 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.getAddress</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getAddress($args, $format)
	{
		$user = ApiBase::checkUserPw($format);

		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$address = UserAddress::model()->findAll($criteria);
		if(!$address) {
			return ApiBase::$noResult;
		}
		$array = array();
		$attributes = array('id', 'user_id', 'consignee', 'address', 'telphone', 'mobile', 'city_id', 'district_id', 'building_id', 'map_x', 'map_y', 'create_time', 'is_default');
		foreach ($address as $a) {
			$array[] = ApiBase::object2array($a, $attributes);
		}
		return $array;
	}
	
	/**
	 * 验证用户账号是否有效 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.check</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function check($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		return array('id'=>$user->id, 'username'=>$user->username);
	}
	
	/**
	 * 获取商品收藏 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.getGoodsFavorite</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getGoodsFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$pages = new CPagination(UserGoodsFavorite::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$favorites = UserGoodsFavorite::model()->findAll($criteria);
		if(!$favorites) {
			return ApiBase::$noResult;
		}
		
		$array = array();
		$attributes = array('id', 'goods_id', 'goods_name', 'goods_price', 'create_time');
		foreach ($favorites as $f) {
			$array[] = ApiBase::object2array($f, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取商铺收藏 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.getShopFavorite</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getShopFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$pages = new CPagination(UserShopFavorite::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$favorites = UserShopFavorite::model()->findAll($criteria);
		if(!$favorites) {
			return ApiBase::$noResult;
		}
		
		$array = array();
		$attributes = array('id', 'shop_id', 'shop_name', 'create_time');
		foreach ($favorites as $f) {
			$array[] = ApiBase::object2array($f, $attributes);
		}
		return $array;
	}
	
	/**
	 * 删除一个商品收藏 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.deleteOneGoodsFavorite</li>
	 *  <li>(required) favorite_id integer POST 商品收藏id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function deleteOneGoodsFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$favoriteId = intval($_POST['favorite_id']);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$favorite = UserGoodsFavorite::model()->findByPk($favoriteId, $criteria);
		if($favorite) {
			if($favorite->delete()) {
				return ApiBase::$success;
			}
		}
		return ApiBase::$fail;
	}
	
	/**
	 * 删除一个商铺收藏 (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET user.deleteOneShopFavorite</li>
	 *  <li>(required) favorite_id integer POST 商铺收藏id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function deleteOneShopFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$favoriteId = intval($_POST['favorite_id']);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$favorite = UserShopFavorite::model()->findByPk($favoriteId, $criteria);
		if($favorite) {
			if($favorite->delete()) {
				return ApiBase::$success;
			}
		}
		return ApiBase::$fail;
	}
}


