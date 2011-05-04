<?php

class GoodsController extends Controller
{
    /**
     * 显示单个商品的资料
     * @param integer $goodsid 商品ID
     */
	public function actionShow($goodsid)
	{
	    $goodsid = (int)$goodsid;
	    if (0 == $goodsid) throw new CException('参数错误', 0);
	    $goods = Goods::model()->with('shop')->findByPk($goodsid);
	    if (null === $goods) throw new CHttpException(404);
	    if ($goods->state != Goods::STATE_SELL) throw new CHttpException(404);
	    
		/* 如果是商铺自己查看，不检查状态都显示 */
	    if($goods->shop->state == STATE_DISABLED && (!$_SESSION['shop'] || $goods->shop->id != $_SESSION['shop']->id)) {
	    	throw new CHttpException(404);
	    }
	    
	    $this->breadcrumbs = array(
			$goods->shop->shop_name => $goods->shop->relativeUrl,
			$goods->name => $goods->relativeUrl,
	    );
	    
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('content = ""');
	    $dianping = $this->loadRateLog($goodsid, $criteria);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('content != ""');
	    $textDianping = $this->loadRateLog($goodsid, $criteria);
	    
	    $this->pageTitle = $goods->name . ',' . $goods->shop->shop_name;
	    $view = 'show';
	    if($goods->shop->category_id == ShopCategory::CATEGORY_CAKE) {
	    	if($goods->goodsModel->category_id == CakeGoods::CATEGROY_CAKE) {
	    		$view = 'cakeshow';
	    	} else {
	    		$view = 'breadshow';
	    	}
	    }
	    
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render($view, array(
			'goods'=>$goods,
		    'dianping' => (array)$dianping,
			'textDianping' => (array)$textDianping,
		));
	}
	
	private function loadRateLog($goodsid, CDbCriteria $criteria)
	{
	    $criteria->addColumnCondition(array('goods_id'=>$goodsid));
	    $criteria->order = 'id desc';
	    $pages = new CPagination(GoodsRateLog::model()->count($criteria));
	    $pages->pageSize = 15;
	    $pages->applyLimit($criteria);
	    $data['logs'] = GoodsRateLog::model()->findAll($criteria);
	    $data['pages'] = $pages;
	    return $data;
	}

	/**
	 * 将商品加入收藏夹
     * @param integer $goodsid 商品ID
	 */
	public function actionFavorite($goodsid)
	{
	    $goodsid = (int)$goodsid;
	    if (user()->isGuest) {
	        user()->loginRequired();
	        exit(0);
	    }
	    
	    $goods = Goods::model()->findByPk($goodsid);
	    if (null === $goods) throw new CHttpException(404);
	    
	    $favorite = UserGoodsFavorite::model()->findByAttributes(array('goods_id'=>$goodsid, 'user_id'=>user()->id));
	    if ($favorite) {
	        echo '收藏成功';
	        exit(0);
	    }
	    
	    $favorite = new UserGoodsFavorite();
	    $favorite->goods_id = $goodsid;
	    $favorite->user_id = user()->id;
	    $favorite->goods_price = $goods->wmPrice;
	    $favorite->goods_name = $goods->name;
	    if ($favorite->save()) {
	        echo '收藏成功';
	    } else {
	        echo '收藏出错';
	    }
	    exit(0);
	}

	/**
	 * 商品点评列表，以ajax方式载入列表显示在goods/show中
     * @param integer $goodsid 商品ID
	 */
	public function actionComment($goodsid)
	{
		$this->render('comment');
	}

	public function actionTop($cid, $sort = 'order_nums')
	{
		$cid = (int)$cid;
		$sort = strip_tags(trim($sort));
		$atid = Location::getLastVisit();
    	
	    if (empty($atid)) $this->redirect(app()->homeUrl);
    	
	    $location = Location::model()->findByPk($atid);
    	if (null == $location) throw new CHttpException(404);
    	
    	/*
	     * 获取商铺
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
	    $data = Shop::getLocationShopList($location, $cid, $criteria);
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    unset($data);
	    
	    $criteria = new CDbCriteria();
		$criteria->addInCondition('shop_id', $shopIds);
		$criteria->order = 't.order_nums desc';
		$criteria->limit = 100;
		
	    if ($cid == ShopCategory::CATEGORY_FOOD) {
		    $sort = new CSort('Goods');
		    $sort->attributes = array(
		    	'wm_price'=> array(
		    		'label' => '价格',
		    		'asc' => 'foodGoods.wm_price asc',
		    		'desc' => 'foodGoods.wm_price desc',
		    	), 'favorite_nums', 'rate_avg'
		    );
		    $sort->defaultOrder = 't.order_nums desc';
		    $sort->applyOrder($criteria);
		    $data['sort'] = $sort;
		    
		    $goods = Goods::model()->with('shop', 'foodGoods')->findAll($criteria);
		    $data['goods'] = $goods;
		    
		    $data['cart'] = Cart::getGoodsList();
		    $data['cartGoods'] = CHtml::listData($data['cart'], 'id', 'goods_id');
		    
		    $this->breadcrumbs = array(
				$location->name => url('shop/list', array('atid'=>$location->id)),
				'热卖美食',
			);
		    
			/*
		     * 生成排序class名称，注意生成的变量需要与排序字段对应，如下面的：
		     * $goods_price，$favorite_nums，$rate_avg
		     */
		    $order = explode('.', trim(strip_tags($_GET['sort'])));
		    ${$order[0]} = 'checked' . $order[1];
		    $data['wm_price'] = $wm_price;
		    $data['favorite_nums'] = $favorite_nums;
		    $data['rate_avg'] = $rate_avg;
		    
		    $this->pageTitle = "{$location->name}周边热卖美食";
			$viewFile = 'foodgoodstop';
	    } elseif($cid == ShopCategory::CATEGORY_CAKE) {
		    $goods = Goods::model()->with('shop', 'cakeGoods', 'cakeGoods.cakePrices')->findAll($criteria);
		    $data['goods'] = $goods;
		    
		    $data['cart'] = Cart::getGoodsList();
		    $data['cartGoods'] = CHtml::listData($data['cart'], 'id', 'goods_id');
		    
		    $this->breadcrumbs = array(
				$location->name => url('shop/list', array('atid'=>$location->id)),
				'热卖蛋糕',
			);
		    
			$this->pageTitle = "{$location->name}周边热卖蛋糕";
			$viewFile = 'cakegoodstop';
	    } else
	        throw new CHttpException(500);
	        
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render($viewFile, $data);
	}
}