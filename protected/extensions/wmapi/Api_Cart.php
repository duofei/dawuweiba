<?php
/**
* 购物车API文件
*/

/**
 * 购物车API类
 */
class Api_Cart
{
	/**
	 * 获取一个会话id Token
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET cart.getToken</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getToken($args, $format)
	{
		$token = md5($args['apikey'] . time());
		return array('token'=>$token);
	}
	
	/**
	 * 通过会话id获取这个用户的购物车商品列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET cart.getList</li>
	 *  <li>(required) token string GET 会话id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getList($args, $format)
	{
		$guestid = trim($args['token']);
		if(!$guestid) {
			throw new CException('Token参数错误', ApiError::ARGS_INVALID);
		}
		
		$cartlist = Cart::getGoodsList($guestid);
		
		if(!$cartlist) {
			return ApiBase::$noResult;
		}
		$attributes = array('id', 'goods_id', 'cakeprice_id', 'goods_name', 'goods_nums', 'goods_price', 'create_time', 'remark');
		$array = array();
		foreach ($cartlist as $cart) {
			$array[] = ApiBase::object2array($cart, $attributes);
		}
		return $array;
	}
	
	/**
	 * 清空购物车
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET cart.setEmpty</li>
	 *  <li>(required) token string POST 会话id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function setEmpty($args, $format){
		$guestid = trim($_POST['token']);
		if(!$guestid) {
			throw new CException('token参数错误', ApiError::ARGS_INVALID);
		}
		if(Cart::clearCart($guestid)) {
			return ApiBase::$success;
		} else {
			return ApiBase::$fail;
		}
	}
	
	/**
	 * 把商品加入购物车
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET cart.add</li>
	 *  <li>(required) token string POST 会话id</li>
	 *  <li>(required) goods_id integer POST 商品id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function add($args, $format)
	{
		$guestid = trim($_POST['token']);
		$goodsId = intval($_POST['goods_id']);
		$cakepriceid = intval($_POST['cakeprice_id']);
		
		if(!$guestid) {
			throw new CException('token参数错误', ApiError::ARGS_INVALID);
		}
		if($goodsId <=0 ) {
			throw new CException('goods_id参数错误', ApiError::ARGS_INVALID);
		}
		
		// 查询商品详情
		$goods = Goods::model()->findByPk($goodsId);
		if(!$goods) {
			return ApiBase::$fail;
		}
		
		// 判断购物车里是否已存在其它商家的商品
		$criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('guest_id'=>$guestid));
	    $criteria->addCondition('goods.shop_id !=' . $goods->shop->id);
	    $nums = Cart::model()->with('goods')->count($criteria);
	    if($nums > 0) {
	    	throw new CException('购物车里已存在其它商家的商品', ApiError::CART_OTHER_SHOPGOODS);
	    }
	    
	    // 是否有蛋糕价， 区别处理
    	if($cakepriceid) {
	    	$cart = Cart::model()->findByAttributes(array('goods_id'=>$goodsId, 'guest_id'=>$guestid, 'cakeprice_id'=>$cakepriceid));
    	} else {
    		$cart = Cart::model()->findByAttributes(array('goods_id'=>$goodsId, 'guest_id'=>$guestid));
    	}
    	$cart = (null === $cart) ? new Cart() : $cart;
	    $cart->goods_id = $goodsId;
	    $cart->goods_nums++;
	    $cart->guest_id = $guestid;
	    if($cakepriceid) {
	    	$cakeprice = CakePrice::model()->findByPk($cakepriceid);
	    	$cart->goods_price = $cakeprice->wmPrice;
	    	$cart->goods_name = $goods->name . '(' . $cakeprice->size. '寸)';
	    	$cart->cakeprice_id = $cakepriceid;
	    } else {
	    	$cart->goods_price = $goods->wmPrice;
	    	$cart->goods_name = $goods->name;
	    }
	    
	    // 保存并返回状态
	    if($cart->save()) {
	    	return ApiBase::$success;
	    } else {
	    	return ApiBase::$fail;
	    }
	}
	
	/**
	 * 删除购物车里一个商品
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET cart.delete</li>
	 *  <li>(required) token string POST 会话id</li>
	 *  <li>(required) cart_id integer POST 购物车的id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function delete($args, $format)
	{
		$cartid = intval($_POST['cart_id']);
		$guestid = trim($_POST['token']);
		
		if(!$guestid) {
			throw new CException('token参数错误', ApiError::ARGS_INVALID);
		}
		if($cartid <=0 ) {
			throw new CException('cart_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('guest_id'=>$guestid));
	    if(Cart::model()->deleteByPk($cartid, $criteria)) {
	    	return ApiBase::$success;
	    } else {
	    	return ApiBase::$fail;
	    }
	}
}