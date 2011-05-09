<?php

class ShopController extends Controller
{
    /**
     * 商铺列表页
     * @param integer $atid 地址的ID，如果为空则直接取用户最后访问的地址的ID
     * @param integer $cid 分类ID
     */
	public function actionList($atid = null, $cid = ShopCategory::CATEGORY_FOOD, $lat = null, $lon = null)
	{
	    $atid = (int)$atid;
	    
    	$criteria = new CDbCriteria();
    	$criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
    	$criteria->with = array('tags');
    	//$criteria->order = 't.id desc'; //排序暂时未定
    	if (0 == $atid && $lat != null && $lon != null) {
	        $lat = strip_tags(trim($lat));
	        $lon = strip_tags(trim($lon));
	        $data = Shop::getLocationShopList(array($lat, $lon), $cid, $criteria);
	        /*
    	     * 设置搜索记录
    	     */
    	    Location::addSearchHistory(array($lat, $lon));
    	    /*
    	     * 设置面包屑导航
    	     */
    	    $label = '位置：' . $lat . ':' . $lon;
    	    $this->breadcrumbs = array(
    			$label => app()->request->url,
    	    );
    	    $this->pageTitle = $label . '外卖';
	    } else {
    	    if (0 == $atid) $atid = Location::getLastVisit();
    	    if (0 == $atid) $this->redirect(app()->homeUrl);
    	    if (is_array($atid))
    	        $location = $atid;
    	    else
    	        $location = Location::model()->findByPk($atid);

    	    if (null == $location) throw new CHttpException(404);
    	    
    	    $data = Shop::getLocationShopList($location, $cid, $criteria);
    	    /*
    	     * 设置搜索记录
    	     */
    	    Location::addSearchHistory($atid);
    	    /*
    	     * 设置面包屑导航
    	     */
    	    $this->breadcrumbs = array(
    			$location->name => app()->request->url,
    	    );
    	    $this->pageTitle = $location->name . '外卖';
	    }
	    
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    /*
	     * 获取优惠信息
	     */
	    $promotions = Promotion::getPromotionFromShopIds($shopIds);
	    /*
	     * 获取热卖美食
	     */
	    $goods = Goods::getHotGoods($shopIds);
	    
	    $filters = ShopCategory::getFilterTags($cid);
	    
	    /*
	     * 生成排序class名称，注意生成的变量需要与排序字段对应，如下面的：
	     * $taste_avg，$order_nums，$service_avg
	     */
	    $order = explode('.', trim(strip_tags($_GET['sort'])));
	    ${$order[0]} = 'checked' . $order[1];
	    
	    $data = array_merge($data, array(
	        'filters' => $filters,
		    'promotions' => $promotions,
		    'goods' => $goods,
	        'tasteSort' => $taste_avg,
	        'orderSort' => $order_nums,
	        'serviceSort' => $service_avg,
	    	'cid' => $cid
	    ));
	    // 如果没有商铺
	    if(0 == count($data['shops'])) {
	    	$maxMin = unserialize(app()->cache->get('dituGenerateImage'));
	    	$data['maxMin'] = $maxMin;
	    	$data['center'] = array(
	    		'map_y' => $maxMin['min']['y'] + ($maxMin['max']['y']-$maxMin['min']['y'])/2,
	    		'map_x' => $maxMin['min']['x'] + ($maxMin['max']['x']-$maxMin['min']['x'])/2,
	    	);
	    }
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('list', $data);
	}

	public function actionBoot($nick)
	{
	    $nick = strip_tags(trim($nick));
	    $criteria = new CDbCriteria();
	    $criteria->select = 'id, shop_name, nick';
	    $criteria->addColumnCondition(array('nick'=>$nick));
	    $model = Shop::model()->find($criteria);
	    if (null === $model)
	        //throw new CHttpException(404, '您请求的店铺不存在');
	        $this->redirect('http://www.52wm.com/');
	    
	    $this->redirect('http://www.52wm.com' . url('shop/show', array('shopid'=>$model->id, 's'=>$_GET['s'])));
	}
	
	/**
	 * 商家详情页（在线菜单）
	 * @param integer $shopid
	 */
	public function actionShow($shopid)
	{
	    $shopid = (int)$shopid;
	    /* tab(在线菜单 热门点评 用户留言 优惠信息 ) */
	    $tabArray = array('rating', 'comment', 'promotion');
	    $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
	    $criteria = new CDbCriteria();
	    /* 如果是商铺自己查看，不检查状态都显示 */
	    if(($_SESSION['shop'] && $shopid == $_SESSION['shop']->id) || $_SESSION['manage_city_id'] || $_SESSION['super_admin']) {
	    	
	    } else {
	    	$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
	    }
	   	$shop = Shop::model()->with('goodsCategories')->findByPk($shopid, $criteria);
	   	if (null == $shop) throw new CHttpException(404);
	   	
	    $cart = Cart::getGoodsList();
	    
	    if(in_array($tab, $tabArray)) {
			$data[$tab] = $this->{load.$tab}($shopid);
			/* 处理tab切换效果 */
	        $data['tab_'.$tab] = 'bg-pic active';
	        $title = array('rating'=>'热门评论', 'comment'=>'用户留言', 'promotion'=>'优惠信息');
	        $this->pageTitle = $shop->shop_name . $title[$tab];
	    } else {
	    	if($shop->category_id == ShopCategory::CATEGORY_FOOD) {
	    		$tab = 'foodMenu';
	    		$data[$tab] = $this->loadFoodData($shop, $cart);
	    	} elseif ($shop->category_id == ShopCategory::CATEGORY_CAKE) {
	    		$tab = 'cakeMenu';
	    		$data[$tab] = $this->loadCakeData($shop, $cart);
	    	}
	    	$data['tab_menu'] = 'bg-pic active';
	    	$this->pageTitle = $shop->shop_name . '在线菜单';
	    }
		
	    
	    /*
	     * 设置面包屑导航
	     */
	    $atid = Location::getLastVisit();
	    $location = Location::model()->findByPk($atid);
	    if ($location)
    	    $this->breadcrumbs = array(
    			$location->name => url('shop/list', array('atid'=>$location->id, 'cid'=>$shop->category_id)),
    			$shop->shop_name => $shop->relativeUrl,
    		);
    	else
	        $this->breadcrumbs = array(
    			$shop->shop_name => $shop->relativeUrl,
    	    );
	    
    	$this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    
	    /* 如果支持同楼订餐 */
	 	if($shop->is_group) {
	    	$endtime = strtotime(param('grouponEndTime'));
		    $remaintime = getdate(mktime(0,0,0,1,1,1970) + $endtime - $_SERVER['REQUEST_TIME']);
		    $data['remaintime'] = $remaintime;
		    if($location && $location->type==Location::TYPE_OFFICE) {
		    	$criteria = new CDbCriteria();
		    	$criteria->addColumnCondition(array(
		    		'location_id' => $atid,
		    		'shop_id' => $shopid,
		    		'date' => Groupon::getMkDate()
		    	));
		    	$data['location'] = $location;
		    	$data['groupon'] = Groupon::model()->find($criteria);
		    }
	    }
	    
    	$data['shop'] = $shop;
		$data['cartGoods'] = CHtml::listData($cart, 'id', 'goods_id');
		$data['tab'] = $tab;
		$this->render('show', $data);
	}
	
	private function loadCakeData($shop, $cart){
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.shop_id'=>$shop->id, 't.state'=>Goods::STATE_SELL));
		$sort = new CSort('Goods');
		$sort->attributes = array('favorite_nums', 'rate_avg');
		$sort->applyOrder($criteria);
		$goods = Goods::model()->with(array('cakeGoods.Purposes', 'cakeGoods.Varietys', 'cakeGoods', 'cakeGoods.cakePrices'))->findAll($criteria);
		$arrayCategory = array();
		$arrayShape = array();
		$arrayPurpose = array();
		$arrayVariety = array();
		foreach($goods as $cg) {
			$arrayCategory[$cg->cakeGoods->category_id] = CakeGoods::$categorys[$cg->cakeGoods->category_id];
			$arrayShape[$cg->cakeGoods->shape_id] = CakeGoods::$shapes[$cg->cakeGoods->shape_id];
			foreach((array)$cg->cakeGoods->Purposes as $p) {
				$arrayPurpose[$p->id] = $p->name;
			}
			foreach((array)$cg->cakeGoods->Varietys as $v) {
				$arrayVariety[$v->id] = $v->name;
			}
		}

		
		$order = explode('.', trim(strip_tags($_GET['sort'])));
	    $array[$order[0]] = 'checked' . $order[1];
	    
		/* 判断当前位置在商家送餐范围 */
    	$points = $shop->maxMapRegion;
    	$coordinate = Location::getLastCoordinate();
    	$lat = $coordinate[0];
    	$lon = $coordinate[1];
		
    	$data = array(
    		'shop' => $shop,
    		'goods' => $goods,
    		'sortclass' => $array,
    		'sort' => $sort,
    		'cartConflict' => $cart[0] && $cart[0]->goods->shop->id!=$shop->id ? true : false,
    		'locationConflict' => !CdcBetaTools::pointInPolygon($points, $lat, $lon),
    		'cartGoods' => CHtml::listData($cart, 'id', 'goods_id'),
    		'arrayCategory' => $arrayCategory,
    		'arrayPurpose' => $arrayPurpose,
    		'arrayVariety' => $arrayVariety,
    		'arrayShape' => $arrayShape,
    	);
    	return $data;
	}
	
	private function loadFoodData($shop, $cart)
	{
	    /* 查询商品列表 */
		$criteria = new CDbCriteria();
    	if($shop->is_dailymenu == Shop::DAILYMENU_SUPPORT) {
    		$week = isset($_GET['week']) && intval($_GET['week']) > 0 && intval($_GET['week']) < 8 ? intval($_GET['week']) : date('N');
    		$criteria->addColumnCondition(array('week'=>$week));
    	}

	   	$criteria->addColumnCondition(array('t.shop_id'=>$shop->id, 't.state'=>Goods::STATE_SELL));
	   	$criteria->order = 't.orderid desc';
	   	$sort = new CSort('Goods');
	   	$sort->attributes = array(
	    	'wm_price'=> array(
	    		'label' => '价格',
	    		'asc' => 'foodGoods.wm_price asc',
	    		'desc' => 'foodGoods.wm_price desc',
	    	), 'favorite_nums', 'rate_avg'
	    );
	   	$sort->applyOrder($criteria);
	   	if($shop->is_dailymenu == Shop::DAILYMENU_SUPPORT) {
	   		$with = array('shop', 'foodGoods', 'foodGoods.goodsCategory', 'dayList');
	   	} else {
	   		$with = array('shop', 'foodGoods', 'foodGoods.goodsCategory');
	   	}
	   	
	   	$goods = Goods::model()->with($with)->findAll($criteria);
	   	$goods = Goods::getSortGoods($goods);

    	/*
	     * 生成排序class名称，注意生成的变量需要与排序字段对应，如下面的：
	     * $goods_price，$favorite_nums，$rate_avg
	     */
	    $order = explode('.', trim(strip_tags($_GET['sort'])));
	    $array[$order[0]] = 'checked' . $order[1];
    	
	    $weekdayClass[$week] = 'link-selected';
    	
    	/* 判断当前位置在商家送餐范围 */
    	$points = $shop->maxMapRegion;
    	$coordinate = Location::getLastCoordinate();
    	$lat = $coordinate[0];
    	$lon = $coordinate[1];

    	$data = array(
    		'shop' => $shop,
    		'goods' => $goods,
    		'sortclass' => $array,
    		'sort' => $sort,
    		'week' => $week,
    		'cartConflict' => $cart[0] && $cart[0]->goods->shop->id!=$shop->id ? true : false,
    		'locationConflict' => !CdcBetaTools::pointInPolygon((array)$points, $lat, $lon),
    		'weekdayClass' => $weekdayClass,
    		'cartGoods' => CHtml::listData($cart, 'id', 'goods_id')
    	);
    	return $data;
	}

	/**
	 * 热门点评
	 */
	private function loadrating($shopid)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('t.shop_id =' . $shopid);
		$criteria->order = 't.id desc';
		$criteria->limit = 20;
		
		$pages = new CPagination(GoodsRateLog::model()->count($criteria));
	    $pages->pageSize = 20;
		$pages->applyLimit($criteria);
		
		$goodsratelog = GoodsRateLog::model()->with('goods', 'user', 'goods.foodGoods', 'goods.cakeGoods', 'orderGoods')->findAll($criteria);
		
		$data = array(
			'goodsratelog' => $goodsratelog,
			'pages' => $pages
		);
		return $data;
	}
	/**
	 * 用户留言
	 */
	private function loadcomment($shopid)
	{
		$criteria = new CDbCriteria();
	    $criteria->addCondition('t.shop_id = ' .$shopid);
	    $criteria->order = 't.id desc';
	    $criteria->limit = 10;
	    $pages = new CPagination(ShopComment::model()->count($criteria));
	    $pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$shopcomment = ShopComment::model()->with('user', 'order')->findAll($criteria);
		
		$model = new ShopComment();
		$model->shop_id = $shopid;
		
		$data = array(
			'list' => $shopcomment,
			'model' => $model,
			'pages' => $pages
		);
		return $data;
	}
	/**
	 * 优惠信息
	 */
	private function loadpromotion($shopid)
	{
		$criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('shop_id' => $shopid));
		$promotion = Promotion::model()->findAll($criteria);

		$data = array(
			'list' => $promotion,
		);
		return $data;
	}
	
	/**
	 * 马上开店，店铺登记
	 */
	public function actionCheckin()
	{
		if (user()->isGuest) {
	        user()->loginRequired();
	        exit(0);
	    }
	    /*
	     * 设置面包屑导航
	     */
		$atid = Location::getLastVisit();
	    $location = Location::model()->findByPk($atid);
	    if ($location)
    	    $this->breadcrumbs = array(
    			$location->name => url('shop/list', array('atid'=>$location->id)),
    			'我要开店'
    		);
    	else
	        $this->breadcrumbs = array(
    			'我要开店'
    	    );
    	    
	    // 查看是否已申请过商铺
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('user_id' => user()->id));
	    $shoplist = Shop::model()->findAll($criteria);
	    if (count($shoplist) > 0) {
	    	$this->render('checkinexists', array('shop'=>$shoplist[0]));
	    	exit;
	    }
	    
	    $shopTag = ShopCategory::getShopCategoryArray();
	    
	    $city_id = $this->city['id'];
	    $shop = new Shop();
	    $shop->category_id = 1;
	    if (app()->request->isPostRequest && isset($_POST['Shop'])) {
	    	$post = CdcBetaTools::filterPostData(array('owner_name', 'owner_card', 'shop_name', 'category_id', 'district_id', 'address', 'telphone', 'buy_type'), $_POST['Shop']);
	    	$shop->attributes = $post;
	    	$city_id = $_POST['city_id'];
	    	if ($shop->save()) {
	    		if($_POST['tags']) {
	    			$tagArray = array();
	    			foreach($_POST['tags'] as $v) {
	    				$tagArray[] = $shopTag[$v];
	    			}
	    			Tag::addShopTag($shop->id, $tagArray);
	    		}
	    		$session = app()->session;
	    		$session['shop'] = $shop;
	    		$this->redirect(url('shopcp/default/index'));
	    		//$this->redirect(url('shop/checkinsuccess'));
	    		exit;
	    	}
	    }
		
	    $this->pageTitle = '我要开店';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    
		$this->render('checkin', array(
			'shop' => $shop,
			'shopTag' => $shopTag,
			'city' => City::getCityArray(),
			'district' => District::getDistrictArray($city_id),
			'city_id' => $city_id,
			'errorSummary' => CHtml::errorSummary($shop)
		));
	}
	
	/**
	 * 马上开店，店铺登记 成功显示页
	 */
	public function actionCheckinSuccess()
	{
		$this->pageTitle = '开店申请提交成功';
		$this->render('checkinsuccess');
	}
	
	/**
	 * 加入我的收藏夹
	 * @param integer $shopid 商铺ID
	 */
	public function actionFavorite($shopid)
	{
	    $shopid = (int)$shopid;
	    if (user()->isGuest) {
	        user()->loginRequired();
	        exit(0);
	    }

	    $shop = Shop::model()->findByPk($shopid);
	    if (null === $shop) throw new CHttpException(404);
	    
	    $favorite = UserShopFavorite::model()->findByAttributes(array('shop_id'=>$shopid, 'user_id'=>user()->id));
	    if ($favorite) {
	        echo '<span class="note-favorite bg-icon success cgreen">已收藏</span>';
	        if (!app()->request->isAjaxRequest) $this->redirect($shop->absoluteUrl);
	        exit(0);
	    }
	    
	    $favorite = new UserShopFavorite();
	    $favorite->shop_id = $shopid;
	    $favorite->user_id = user()->id;
	    $favorite->shop_name = $shop->shop_name;
	    if ($favorite->save()) {
	        echo '<span class="note-favorite bg-icon success cgreen">已收藏</span>';
	    } else {
	        echo '<span class="note-favorite bg-icon failed cred">出错</span>';
	    }
	    if (!app()->request->isAjaxRequest) $this->redirect($shop->absoluteUrl);
	    exit(0);
	}

	/**
	 * 商家资料页面
	 */
	public function actionInfo()
	{
		$this->render('info');
	}

	/**
	 * 添加评论
	 */
	public function actionNewComment($shopid=0)
	{
	    $shopid = (int)$shopid;
		if(user()->isGuest) {
			$this->redirect(url('shop/show', array('shopid'=>$shopid, 'tab'=>'comment')));
		}
		
		$comment = new ShopComment();
		if (app()->request->isPostRequest && isset($_POST['ShopComment'])) {
			$post = CdcBetaTools::filterPostData(array('shop_id', 'content', 'validateCode'), $_POST['ShopComment']);
			$comment->attributes = $post;
			if($comment->save()) {
				$this->redirect(url('shop/show', array('shopid'=>$shopid, 'tab'=>'comment')));
			}
		}
		
		$errorsummary = CHtml::errorSummary($comment);
		$comment->shop_id = $shopid;
		
		/*
	     * 设置面包屑导航
	     */
		$atid = Location::getLastVisit();
	    $location = Location::model()->findByPk($atid);
	    $shop = Shop::model()->findByPk($shopid);
	    if ($location)
    	    $this->breadcrumbs = array(
    			$location->name => url('shop/list', array('atid'=>$location->id, 'cid'=>$shop->category_id)),
    			$shop->shop_name => $shop->relativeUrl,
    			'用户留言' => url('shop/newComment', array('shopid'=>$shopid))
    		);
    	else
	        $this->breadcrumbs = array(
    			$shop->shop_name => $shop->relativeUrl,
    			'用户留言' => url('shop/newComment', array('shopid'=>$shopid))
    	    );
		
		$this->render('newComment',array(
			'model' => $comment,
			'errorsummary' => $errorsummary
		));
	}

	/**
	 * 商铺及商品搜索
	 * @param string $kw 搜索关键字
	 */
	public function actionSearch($kw, $cid = 0)
	{
	    $cid = (int)$cid;
	    $atid = Location::getLastVisit();
    	$kw = urldecode(strip_tags(trim($_GET['kw'])));
	    if (empty($atid) || empty($kw)) $this->redirect(app()->homeUrl);
    	
	    if(!is_array($atid)) {
	    	$location = Location::model()->findByPk($atid);
	    	$atid = $location;
	    }
    	
    	/*
	     * 获取商铺
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
	    $data = Shop::getLocationShopList($atid, $cid, $criteria);
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    unset($data);
	    
	    $criteria = new CDbCriteria();
		$criteria->addInCondition('shop_id', $shopIds);
		$criteria->addSearchCondition('name', $kw);
		    
	    if($cid == ShopCategory::CATEGORY_FOOD) {
		    $sort = new CSort('Goods');
		    $sort->attributes = array(
		    	'wm_price'=> array(
		    		'label' => '价格',
		    		'asc' => 'foodGoods.wm_price asc',
		    		'desc' => 'foodGoods.wm_price desc',
		    	), 'favorite_nums', 'rate_avg'
		    );
		    $sort->defaultOrder = 't.shop_id asc, t.id asc';
		    $sort->applyOrder($criteria);
		    $data['sort'] = $sort;
		    //$criteria->order = 't.shop_id asc, t.id asc';
		    
		    $goods = Goods::model()->with('shop', 'foodGoods')->findAll($criteria);
		    
		    $goodscount = 0;
		    foreach ($goods as $v) {
		        $data['goods'][$v->shop->shop_name][] = $v;
		        $goodscount++;
		    }
		    $data['goodscount'] = $goodscount;
		    $data['cart'] = Cart::getGoodsList();
		    $data['cartGoods'] = CHtml::listData($data['cart'], 'id', 'goods_id');
		    
		    if($location) {
		    	 $this->breadcrumbs[$location->name] = url('shop/list', array('atid'=>$location->id));
		    }
		    $this->breadcrumbs[] = '搜索：' . $kw;
		    
			/*
		     * 生成排序class名称，注意生成的变量需要与排序字段对应，如下面的：
		     * $goods_price，$favorite_nums，$rate_avg
		     */
		    $order = explode('.', trim(strip_tags($_GET['sort'])));
		    ${$order[0]} = 'checked' . $order[1];
		    $data['wm_price'] = $wm_price;
		    $data['favorite_nums'] = $favorite_nums;
		    $data['rate_avg'] = $rate_avg;
		    
		    $this->pageTitle = "美食搜索：{$kw}";
		    $this->setPageKeyWords($this->pageTitle);
	        $this->setPageDescription($this->pageTitle);
	    
		    $this->render('foodgoodssearch', $data);
	    } elseif ($cid == ShopCategory::CATEGORY_CAKE) {
	    	$goods = Goods::model()->with('shop', 'cakeGoods', 'cakeGoods.cakePrices')->findAll($criteria);
		    
		    $goodscount = 0;
		    foreach ($goods as $v) {
		        $data['goods'][$v->shop->shop_name][] = $v;
		        $goodscount++;
		    }
		    $data['goodscount'] = $goodscount;
		    $data['cart'] = Cart::getGoodsList();
		    $data['cartGoods'] = CHtml::listData($data['cart'], 'id', 'goods_id');
		    
		    $this->breadcrumbs = array(
				$location->name => url('shop/list', array('atid'=>$location->id)),
				'搜索：' . $kw,
			);
			
			$this->pageTitle = "蛋糕搜索：{$kw}";
		    $this->render('cakegoodssearch', $data);
	    }
	}
}