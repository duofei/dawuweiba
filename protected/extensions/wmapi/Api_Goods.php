<?php
/**
* 商品API文件
*/

/**
 * 商品API类
 */
class Api_Goods
{
	/**
	 * 获取一个商铺的美食列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET goods.getFoodList</li>
	 *  <li>(required) shop_id integer GET 商铺id</li>
	 *  <li>week integer GET 周几，只有此商铺开启每日菜单时，此参数才能有效，默认为当天的周几，可选:(1-7)</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getFoodList($args, $format)
	{
		//$shopid, $category=null, $week=null
		$shopId = intval($args['shop_id']);
		$category = intval($args['category_id']);
		$week = intval($args['week']);
		
		if($shopId <=0 ) {
			throw new CException('shop_id参数错误', ApiError::ARGS_INVALID);
		}
			
		$shop = Shop::model()->findByPk($shopId);
		$model = Goods::$goodsTbl[$shop->category_id];
		if($model != 'foodGoods') {
			throw new CException('此商铺不是餐馆', ApiError::NOT_FOOD_SHOP);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.shop_id'=>$shopId, 't.state'=>Goods::STATE_SELL));
		$criteria->order = 't.orderid desc';
		
		$withmodel = array($model, 'tags');
		$withmodel[] = $model . '.goodsCategory';
		if($shop->is_dailymenu == STATE_ENABLED) {
			if($week <=0 || $week >7) {
				$week = date('N');
			}
			$withmodel[] = 'dayList';
			$criteria->addColumnCondition(array('dayList.week'=>$week));
		}
		
		if($category) {
			$criteria->addColumnCondition(array($model . '.category_id'=>$category));
		}

		$goodslist = Goods::model()->with($withmodel)->findAll($criteria);
		
		if(!$goodslist) {
			return ApiBase::$noResult;
		}
		$array = array();
		$goods_attributes = array('id', 'name', 'shop_id', 'picUrl', 'favorite_nums', 'comment_nums', 'rate_avg', 'create_time', 'is_new', 'is_tuan', 'is_carry');
		$food_attributes = array('category_id', 'market_price', 'wm_price', 'group_price', 'is_spicy', 'desc');
		
		foreach ($goodslist as $goods) {
			if(count($goods->tags) > 0) {
				foreach($goods->tags as $tag) {
					$ttemp_tags .= $tag->name . ' ';
				}
				$temp = array('category_name'=>$goods->goodsModel->goodsCategory->name, 'tags' => trim($ttemp_tags));
			} else {
	  			$temp = array('category_name'=>$goods->goodsModel->goodsCategory->name);
			}
			$array[] = array_merge(ApiBase::object2array($goods, $goods_attributes), ApiBase::object2array($goods->goodsModel, $food_attributes), $temp);
		}
		
		return $array;
	}
	
	/**
	 * 获取一个商品信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET goods.getInfo</li>
	 *  <li>(required) goods_id integer GET 商品id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$goodsId = intval($args['goods_id']);
		if($goodsId <=0 ) {
			throw new CException('goods_id参数错误', ApiError::ARGS_INVALID);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>Goods::STATE_SELL));
		$goods = Goods::model()->findByPk($goodsId, $criteria);
		
		if(!$goods) {
			return ApiBase::$noResult;
		}
		$model = Goods::$goodsTbl[$goods->shop->category_id];
		
		if(strtolower($model) == 'foodgoods') {
			$goods_attributes = array('id', 'name', 'shop_id', 'picUrl', 'favorite_nums', 'comment_nums', 'rate_avg', 'create_time', 'is_new', 'is_tuan', 'is_carry');
			$food_attributes = array('category_id', 'market_price', 'wm_price', 'group_price', 'is_spicy', 'desc');
			if(count($goods->tags) > 0) {
				foreach($goods->tags as $tag) {
					$ttemp_tags .= $tag->name . ' ';
				}
				$temp = array('category_name'=>$goods->goodsModel->goodsCategory->name, 'tags' => trim($ttemp_tags));
			} else {
	  			$temp = array('category_name'=>$goods->goodsModel->goodsCategory->name);
			}
			
			return array_merge(ApiBase::object2array($goods, $goods_attributes), ApiBase::object2array($goods->goodsModel, $food_attributes), $temp);
		} elseif (strtolower($model) == 'cakegoods') {
			// 返回蛋糕数据
		}
		return ApiBase::$noResult;
	}
	
	/**
	 * 添加到收藏夹  (需要身份验证)
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET goods.addFavorite</li>
	 *  <li>(required) goods_id integer POST 商品id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function addFavorite($args, $format)
	{
		$user = ApiBase::checkUserPw($format);
		
		$goodsId = intval($_POST['goods_id']);
		
		if($goodsId <=0 ) {
			throw new CException('goods_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$user->id, 'goods_id'=>$goodsId));
		$favorite = UserGoodsFavorite::model()->find($criteria);
		if($favorite->goods_name) {
			return ApiBase::$success;
		}
		$goods = Goods::model()->findByPk($goodsId);
		if($goods->name) {
			$favorite = new UserGoodsFavorite();
			$favorite->user_id = $user->id;
			$favorite->goods_id = $goodsId;
			$favorite->goods_name = $goods->name;
			$favorite->goods_price = $goods->wmPrice;
			if($favorite->save()) {
				return ApiBase::$success;
			} else {
				return ApiBase::$fail;
			}
		} else {
			throw new CException('goods_id参数错误', ApiError::ARGS_INVALID);
		}
	}
	
	/**
	 * 获取商品点评信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET goods.getRating</li>
	 *  <li>(required) goods_id integer GET 商品id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getRating($args, $format)
	{
		$goodsId = intval($_GET['goods_id']);
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		if($goodsId <=0 ) {
			throw new CException('goods_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('goods_id'=>$goodsId));
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
}

