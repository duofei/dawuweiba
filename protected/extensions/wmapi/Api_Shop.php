<?php
/**
* 商铺API文件
*/

/**
 * 商铺API类
 */
class Api_Shop
{
	/**
	 * 通过坐标获取商铺列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getListByCoordinate</li>
	 *  <li>(required) lon integer GET 经度</li>
	 *  <li>(required) lat integer GET 纬度</li>
	 *  <li>(required) city_id integer GET 城市id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getListByCoordinate($args, $format)
	{
		$lon = floatval($args['lon']); 				// 经度
		$lat = floatval($args['lat']); 				// 纬度
		$cityId = intval($args['city_id']); 		// 城市ID
		$categoryId = intval($args['category_id']); //商铺分类，默认值：1， 可选参数：1.美食,2.蛋糕,3.鲜花
		
		if(intval($cityId) <= 0) {
			throw new CException('city_id参数错误', ApiError::ARGS_INVALID);
		}
		if(!key_exists($categoryId,ShopCategory::$categorys)) {
			$categoryId = ShopCategory::CATEGORY_FOOD;
		}
		
		$criteria = new CDbCriteria();
    	$criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
    	$criteria->with = array('tags');
    	
		$data = Shop::getLocationShopListV2(array($lat, $lon), $categoryId, $criteria, $cityId);
		if(!$data['shops']) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'shop_name', 'logoUrl', 'desc', 'announcement', 'address', 'service_avg', 'serviceStarWidth', 'tasteStarWidth', 'taste_avg', 'business_time', 'transport_condition', 'transport_time', 'is_muslim', 'is_sanitary_approve', 'is_dailymenu', 'create_time', 'goods_nums', 'visit_nums', 'telphone', 'mobile', 'qq', 'distanceText', 'tagsText', 'isOpening');
		$array = array();
		foreach ($data['shops'] as $shop) {
			$temp = ApiBase::object2array($shop, $attributes);
			if($shop->tags) {
				foreach ($shop->tags as $t) {
					$temp['tags'] .= $t->name . ' ';
				}
				$temp['tags'] = trim($temp['tags']);
			}
			$array[] = $temp;
		}
		return $array;
	}
	
	/**
	 * 通过位置获取商铺列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getListByLocation</li>
	 *  <li>(required) location_id integer GET 地址id</li>
	 *  <li>(required) city_id integer GET 城市id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getListByLocation($args, $format)
	{
		$locationId = intval($args['location_id']);
		$categoryId = intval($args['category_id']); //商铺分类，默认值：1， 可选参数：1.美食,2.蛋糕,3.鲜花
		
		if($locationId > 0) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
			$location = Location::model()->findByPk($locationId, $criteria);
			if(null === $location) {
				throw new CException('location_id参数错误', ApiError::ARGS_INVALID);
			}
		} else {
			throw new CException('location_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
    	$criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
    	$criteria->with = array('tags');

		$data = Shop::getLocationShopListV2($locationId, $categoryId, $criteria);
		if(!$data['shops']) {
			return ApiBase::$noResult;
		}
		$attributes = array('id', 'shop_name', 'logoUrl', 'desc', 'announcement', 'address', 'service_avg', 'serviceStarWidth', 'tasteStarWidth', 'taste_avg', 'business_time', 'transport_condition', 'transport_time', 'is_muslim', 'is_sanitary_approve', 'is_dailymenu', 'create_time', 'goods_nums', 'visit_nums', 'telphone', 'mobile', 'qq', 'distance', 'distanceText', 'tagsText', 'isOpening');
		$array = array();
		foreach ($data['shops'] as $shop) {
			$temp = ApiBase::object2array($shop, $attributes);
			if($shop->tags) {
				foreach ($shop->tags as $t) {
					$temp['tags'] .= $t->name . ' ';
				}
				$temp['tags'] = trim($temp['tags']);
			}
			$array[] = $temp;
		}
		return $array;
	}
	
	/**
	 * 获取商铺信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getInfo</li>
	 *  <li>(required) shop_id integer GET 商铺id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$shopId = intval($args['shop_id']);
		if($shopId <=0 ) {
			throw new CException('shop_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$shop = Shop::model()->findByPk($shopId, $criteria);
		if(!$shop) {
			return ApiBase::$noResult;
		}
		$attributes = array('id', 'shop_name', 'logoUrl', 'desc', 'announcement', 'address', 'service_avg', 'serviceStarWidth', 'tasteStarWidth', 'taste_avg', 'business_time', 'transport_condition', 'transport_time', 'is_muslim', 'is_sanitary_approve', 'is_dailymenu', 'create_time', 'goods_nums', 'visit_nums', 'telphone', 'mobile', 'qq', 'distanceText', 'tagsText', 'isOpening');
		$array = array();
		return ApiBase::object2array($shop, $attributes);
	}
	
	
	/**
	 * 获取商铺评论信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getComment</li>
	 *  <li>(required) shop_id integer GET 商铺id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getComment($args, $format)
	{
		$shopId = intval($args['shop_id']);
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		if($shopId <=0 ) {
			throw new CException('ShopId参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id'=>$shopId));
		$pages = new CPagination(ShopComment::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$criteria->order = 'create_time desc';
		$shopComment = ShopComment::model()->findAll($criteria);
		
		if(!$shopComment) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'shop_id', 'order_id', 'create_time', 'content', 'reply', 'reply_time');
		$array = array();
		foreach ($shopComment as $sc) {
			$array[] = ApiBase::object2array($sc, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取商铺优惠信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getPromotion</li>
	 *  <li>(required) shop_id integer GET 商铺id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getPromotion($args, $format)
	{
		$shopId = intval($args['shop_id']);
		
		if($shopId <=0 ) {
			throw new CException('ShopId参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id'=>$shopId));
		$criteria->order = 'create_time desc';
		$shopPromotion = Promotion::model()->findAll($criteria);
		if(!$shopPromotion) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'shop_id', 'content', 'create_time', 'end_time');
		$array = array();
		foreach ($shopPromotion as $sp) {
			$array[] = ApiBase::object2array($sp, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取商铺点评信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getRating</li>
	 *  <li>(required) shop_id integer GET 商铺id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getRating($args, $format)
	{
		$shopId = intval($args['shop_id']);
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		if($shopId <=0 ) {
			throw new CException('ShopId参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id'=>$shopId));
		$pages = new CPagination(GoodsRateLog::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$criteria->order = 'create_time desc';
		$goodsrates = GoodsRateLog::model()->findAll($criteria);
		if(!$goodsrates) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'shop_id', 'goods_id', 'user_id', 'ordergoods_id', 'content', 'create_time', 'mark');
		$array = array();
		foreach ($goodsrates as $gr) {
			$array[] = ApiBase::object2array($gr, $attributes);
		}
		return $array;
	}
	
	
	/**
	 * 添加到收藏夹  (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.addFavorite</li>
	 *  <li>(required) shop_id integer POST 商铺id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function addFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$shopId = intval($_POST['shop_id']);
		
		if($shopId <=0 ) {
			throw new CException('shop_id参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$shop = Shop::model()->findByPk($shopId, $criteria);
		if(null === $shop) {
			throw new CException('shop_id参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id, 'shop_id'=>$shopId));
		$favorite = UserShopFavorite::model()->find($criteria);
		if(null === $favorite) {
			$favorite = new UserShopFavorite();
			$favorite->user_id = $user->id;
			$favorite->shop_id = $shopId;
			$favorite->shop_name = $shop->shop_name;
			if($favorite->save()) {
				return ApiBase::$success;
			} else {
				return ApiBase::$fail;
			}
		} else {
			return ApiBase::$success;
		}
	}

	
	/**
	 * 获取商铺标签
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET shop.getTags</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getTags($args, $format)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$shopTag = ShopCategory::model()->findAll($criteria);
		$attributes = array('name');
		$array = array();
		foreach ($shopTag as $tag) {
			$array[] = ApiBase::object2array($tag, $attributes);
		}
		return $array;
	}
}
