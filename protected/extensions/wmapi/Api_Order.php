<?php
/**
* 订单API文件
*/

/**
 * 订单API类
 */
class Api_Order
{
	/**
	 * 获取一个订单详情
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET order.getInfo</li>
	 *  <li>(required) order_id integer GET 订单id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$orderId = intval($args['order_id']);
		
		if(!$orderId) {
			throw new CException('order_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id));
		$order = Order::model()->findByPk($orderId, $criteria);
		if(!$order) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'orderSn', 'shop_id', 'user_id', 'create_time', 'status', 'amount', 'buy_type', 'is_pay', 'pay_type', 'message');
		$array = ApiBase::object2array($order, $attributes);
		$og_attributes = array('id', 'order_id', 'goods_id', 'goods_name', 'goods_price', 'goods_nums', 'goods_amount', 'remark');
		foreach ($order->orderGoods as $og) {
			$array['ordergoods'][] = ApiBase::object2array($og, $og_attributes);
		}
		return $array;
	}
	
	/**
	 * 下一个订单  (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET order.checkout</li>
	 *  <li>(required) token integer POST 会话id</li>
	 *  <li>(required) city_id integer POST 城市id</li>
	 *  <li>(required) consignee string POST 联系人</li>
	 *  <li>(required) address string POST 收货地址</li>
	 *  <li>(required) message string POST 订单备注</li>
	 *  <li>(required) telphone string POST 联系电话</li>
	 *  <li>(required) deliver_time string POST 送餐时间</li>
	 *  <li>mobile string POST 联系手机 此项与电话必填一项</li>
	 *  <li>building_id string POST 楼宇id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function checkout($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
	    
		$guestid = trim($_POST['token']);
		if(!$guestid) {
			throw new CException('token参数错误', ApiError::ARGS_INVALID);
		}
		$cartlist = Cart::getGoodsList($guestid);
		/* 判断购物车是否为空 */
	    if(count($cartlist) == 0) {
	  		throw new CException('购物车是空的', ApiError::CART_IS_EMPTY);
	    }
	    
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
		$deliver_time = trim(strip_tags($_POST['deliver_time']));
		if(!$deliver_time) {
			throw new CException('deliver_time参数错误', ApiError::ARGS_INVALID);
		}
		$building_id = intval($_POST['building_id']);
			$order = new Order('checkout');
            $order->shop_id = $cartlist[0]->goods->shop_id;
            $order->consignee = $consignee;
            $order->address = $address;
            $order->telphone = $telphone;
            $order->mobile = $mobile;
            $order->message = $message;
            $order->deliver_time = $deliver_time;
            $order->pay_type = $cartlist[0]->goods->shop->pay_type;
            $order->buy_type = $cartlist[0]->goods->shop->buy_type;
            $order->city_id = $cityId;
           	$order->token = $guestid;
           	$order->user_id = $user->id;
           	$order->building_id = $building_id;

            if ($order->save()) {
            	return ApiBase::$success;
            } else {
            	return ApiBase::$fail;
            }
	}
}