<?php

class CartController extends Controller
{
    /**
     * 将商品放入购物车
     * @param integer $goodsid 商品ID
     */
	public function actionCreate($goodsid, $is_group=null)
	{
	    $goodsid = (int)$goodsid;
	    $is_group = (int)$is_group;
	    /*
	     * 如果商品不存在，直接抛出异常，显示404错误
	     */
	    $goods = Goods::model()->findByPk($goodsid);
	    if (null === $goods) throw new CHttpException(404);
	    
	     /* 判断当前购物车里是否有其它商家商品 */
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('guest_id'=>$this->token));
	    $criteria->addCondition('goods.shop_id !=' . $goods->shop->id);
	    $nums = Cart::model()->with('goods')->count($criteria);
	    if($nums > 0) {
	    	echo -1;
	    	exit;
	    }
	    
	    /* 判断当前位置在商家送餐范围 */
	    $points = array();
	    $points = $goods->shop->getMaxMapRegion();
	    $coordinate = Location::getLastCoordinate();
	    $lat = $coordinate[0];
	    $lon = $coordinate[1];
    	if(!CdcBetaTools::pointInPolygon($points, $lat, $lon)) {
    		echo -2;
    		exit;
    	}
	    
		/* 如果是同楼订餐,并且还没有选择楼宇弹窗提示 */
    	if($is_group) {
    		$location_id = Location::getLastVisit();
    		if(is_array($location_id)) {
    			echo -3;
		    	exit;
    		}
    		$location = Location::model()->findByPk($location_id);
    		if($location->type != Location::TYPE_OFFICE) {
    			echo -3;
    			exit;
    		}
    	}
	
    	/* 如果是同楼订餐,并且购物车里非同楼订餐的商品在里头 */
    	$carts = Cart::getGoodsList();
    	if($is_group) {
    		foreach ($carts as $cart) {
    			if($cart->is_group == STATE_DISABLED) {
    				echo -4;
    				exit;
    			}
    		}
    	} else {
    		foreach ($carts as $cart) {
    			if($cart->is_group == STATE_ENABLED) {
    				echo -5;
    				exit;
    			}
    		}
    	}
    	
	    /*
	     * 如果购物车中已经存在此商品，则将数量加1，否则创建新数据
	     */
    	$cakepriceid = intval($_POST['cakepriceid']);
    	if($cakepriceid) {
	    	$cart = Cart::model()->findByAttributes(array('goods_id'=>$goodsid, 'guest_id'=>$this->token, 'cakeprice_id'=>$cakepriceid));
    	} else {
    		$cart = Cart::model()->findByAttributes(array('goods_id'=>$goodsid, 'guest_id'=>$this->token));
    	}
	    $cart = (null === $cart) ? new Cart() : $cart;
	    
	    $cart->goods_id = $goodsid;
	    $cart->goods_nums++;
	    if($cakepriceid) {
	    	$cakeprice = CakePrice::model()->findByPk($cakepriceid);
	    	$cart->goods_price = $cakeprice->wmPrice;
	    	$cart->goods_name = $goods->name . '(' . $cakeprice->size. '寸)';
	    	$cart->cakeprice_id = $cakepriceid;
	    } else {
	    	$cart->goods_price = $goods->wmPrice;
	    	$cart->group_price = $goods->groupPrice;
	    	$cart->goods_name = $goods->name;
	    	//如果点击同楼订餐并且商家支持同楼订餐
	    	if($is_group && $goods->shop->is_group) {
	    		$cart->is_group = STATE_ENABLED;
	    	}
	    }
	    
	    $cart->save();
	    if (app()->request->isAjaxRequest) {
	        $data = Cart::getGoodsList();
	        $this->renderPartial('small_cart', array('cart'=>$data));
	    } else {
	        //if (request()->urlReferrer) $this->redirect(request()->urlReferrer);
	        $this->redirect(url('shop/show', array('shopid'=>$goods->shop_id)));
	    }
	}

	/**
	 * 更改购买数量
	 */
	public function actionUpdateNums($goodsid)
	{
	    $goodsid = (int)$goodsid;
	    $num = (int)app()->request->getPost('num');
	    $goods = Cart::model()->findByPk($goodsid);
	    if (null === $goods) throw new CHttpException(404);
	    $goods->goods_nums = $num;
	    $goods->save();
	    $data = Cart::getGoodsList();
	    $view = strip_tags(trim($_POST['view']));
        $this->renderPartial($view, array('cart'=>$data));
	}

	/**
	 * 显示购物车内商品列表
	 */
	public function actionShow()
	{
		$this->render('show');
	}

	/**
	 * 清除购物车内所有商品
	 */
	public function actionClear()
	{
		Cart::clearCart();
		$referer = request()->urlReferrer ? request()->urlReferrer : app()->homeUrl;
		$this->redirect($referer);
	}

	/**
	 * 转换成同楼订餐
	 */
	public function actionSwitchGounpon()
	{
		$carts = Cart::getGoodsList();
		foreach($carts as $cart) {
			if($cart->is_group == STATE_DISABLED) {
				$cart->is_group = STATE_ENABLED;
				$cart->save();
			}
		}
		$referer = request()->urlReferrer ? request()->urlReferrer : app()->homeUrl;
		$this->redirect($referer);
	}
	
	/**
	 * 转换成普通订餐
	 */
	public function actionSwitchNoGounpon()
	{
		$carts = Cart::getGoodsList();
		foreach($carts as $cart) {
			if($cart->is_group == STATE_ENABLED) {
				$cart->is_group = STATE_DISABLED;
				$cart->save();
			}
		}
		$referer = request()->urlReferrer ? request()->urlReferrer : app()->homeUrl;
		$this->redirect($referer);
	}
	
	
	/**
	 * 删除购物车内某一件商品
	 * @param integer $cartid 要删除的商品在购物车中的ID
	 */
	public function actionDelete($cartid)
	{
	    $cartid = (int)$cartid;
	    Cart::model()->deleteByPk($cartid);
	    $data = Cart::getGoodsList();

	    $view = strip_tags(trim($_POST['view']));
	    $this->renderPartial($view, array('cart'=>$data));
	}

	/**
	 * 提交购物车内商品
	 */
	public function actionCheckout()
	{
		$cart = Cart::getGoodsList();
		/* 判断购物车是否为空 */
	    if(count($cart) == 0) {
	  		$this->redirect(url('cart/empty'));
	    }
	    $data['cart'] = $cart;
	    
		$data['user'] = User::model()->findByPk(user()->id);
	    /* 白吃点处理 */
	    $data['usebcnum'] = intval((Cart::getGoodsAmount()+$cart[0]->goods->shop->matchDispatchingAmount)/10);
	    $data['allowUseBcnum'] = 0;
	    if($data['user']->bcnums > $data['usebcnum']) {
	    	$data['allowUseBcnum'] = $data['usebcnum'];
	    } else {
	    	$data['allowUseBcnum'] = $data['user']->bcnums;
	    }
	    
	    /* 秒杀处理 */
	    $data['miaosha_state'] = false;
	    if($_GET['miaosha_id']) {
	    	$miaosha_id = intval($_GET['miaosha_id']);
	    	$criteria = new CDbCriteria();
	    	$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id, 'user_id'=>user()->id, 'order_id'=>0));
	    	$miaoshaResult = MiaoshaResult::model()->find($criteria);
	    	if($miaoshaResult) {
	    		if($miaoshaResult->goods_id == $cart[0]->goods_id && !$cart[1]) {
	    			$data['miaosha_state'] = true;
	    		}
	    	}
	    }
	    if (app()->request->isPostRequest && isset($_POST['UserAddress'])) {
	    	if(Cart::getGoodsAmount() < $cart[0]->goods->shop->matchTransportAmount) {
	    		$this->redirect(url('cart/checkout'));
	    	}
	        /*
	         * 保存或添加收货人地址
	         */
	        $uaid = (int)$_POST['UserAddress']['id'];
	        if (empty($uaid))
	            $userAddress = new UserAddress();
	        elseif ($_POST['editAddress'])
                $userAddress = UserAddress::model()->findByPk($uaid);
            if ($userAddress) {
            	$post = CdcBetaTools::filterPostData(array('id', 'consignee', 'address', 'telphone', 'mobile', 'city_id'),$_POST['UserAddress']);
                $userAddress->attributes = $post;
                try {$userAddress->save();} catch (CException $e) {}
            }
            
            if($data['user']->approve_state == User::APPROVE_STATE_VERIFY || User::checkSmsVerifyCode($data['user']->id, $_POST['vcode'])) {
            	if($data['user']->approve_state == User::APPROVE_STATE_UNSETTLED) {
            		$data['user']->approve_state = User::APPROVE_STATE_VERIFY;
            		if(!$data['user']->telphone) {
            			$data['user']->telphone = $_POST['UserAddress']['telphone'];
            		}
            		$data['user']->save(false);
            	}
	            $order = new Order('checkout');
	            $order->shop_id = (int)$_POST['shop_id'];
	            $order->consignee = $_POST['UserAddress']['consignee'];
	            $order->address = $_POST['UserAddress']['address'];
	            $order->telphone = $_POST['UserAddress']['telphone'];
	            $order->mobile = $_POST['UserAddress']['mobile'];
	            $order->message = $_POST['UserAddress']['message'];
	            $order->deliver_time = $_POST['deliver_time'];
	            $order->pay_type = $cart[0]->goods->shop->pay_type;
	            $order->buy_type = $cart[0]->goods->shop->buy_type;
	            $order->dispatching_amount = $cart[0]->goods->shop->matchDispatchingAmount;
	            
	            /* 如果是打印机订餐订单 */
	            if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_PRINTER) {
	            	if(Setting::getValue(param('s_orderApprove')) == STATE_ENABLED) { // 如果关闭审核，订单直接通过
		            	if($data['user']->approve_state==User::APPROVE_STATE_VERIFY) {
		            		$order->verify_state = STATE_ENABLED;
		            	} else {
		            		$order->verify_state = STATE_DISABLED;
		            	}
	            	} else {
	            		$order->verify_state = STATE_ENABLED;
	            	}
	            }
	            
	            $order->status = Order::STATUS_UNDISPOSED;
	            $order->is_carry = $_POST['iscarry'];
	            $order->city_id = $this->city['id'];
				
	            $is_group = $cart[0]->is_group;
	           	$lastvisit = Location::getLastVisit();
	           	if(is_array($lastvisit))  $lastvisit = 0;
	           	$order->building_id = $lastvisit;
	           	
	           	/* 使用白吃点 */
	           	$useBcnumState = false;
		    	$postBcnum = intval($_POST['bcnum']);
	            if($postBcnum > 0 && $postBcnum <= $data['allowUseBcnum']) {
	            	$order->paid_amount = $postBcnum;
	            	$order->paid_remark = '使用' . $postBcnum . '点白吃点';
	            	$useBcnumState = true;
	            }
	            
	            /* 秒杀活动处理 */
	            if($data['miaosha_state']) {
			    	$criteria = new CDbCriteria();
			    	$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id));
			    	$criteria->addCondition('order_id > 0');
			    	$resultCount = MiaoshaResult::model()->count($criteria);
			    	$miaosha = Miaosha::model()->findByPk($miaosha_id);
			    	if($miaosha->state!=Miaosha::STATE_OPEN || $resultCount >= $miaosha->active_num) {
			    		$miaoshaResult->create_time = time();
			    		$miaoshaResult->save();
			    		if($miaosha->state == Miaosha::STATE_OPEN) {
			    			$miaosha->state = Miaosha::STATE_OVER;
			    			$miaosha->save();
			    		}
			    		$this->redirect(url('miaosha/fail'));
			    		exit;
			    	} else {
			    		$order->paid_amount = Cart::getGoodsAmount() + $cart[0]->goods->shop->matchDispatchingAmount - 1;
	            		$order->paid_remark = '一元秒杀活动优惠' . $order->paid_amount . '元';
	            		if($resultCount == 0) {
	            			MiaoshaResult::addUntrues($miaosha_id);
	            		}
			    	}
	            }
	            
	            if ($order->save()) {
	            	/* 如果是在线付款的订单 跳转到付款页面 */
		            if($cart[0]->goods->shop->pay_type == Shop::PAYTYPE_ONLINE) {
		            	$this->redirect(url('alipay/pay', array('orderid'=>$order->id)));
		            }
		            //if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_SMS) {
		            	// 发送短信
		            //}
		            if($is_group && $lastvisit) {
		            	Groupon::addOrder($order);
		            }
		            /* 如果是打印机订单&&用户已认证 */
		            if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_PRINTER && $data['user']->approve_state==User::APPROVE_STATE_VERIFY) {
		            	UserInviter::inviteSuccess(user()->id);
		            }
		            /* 如果使用了白吃点 */
		            if($useBcnumState) {
		            	$userbclog = new UserBcintegralLog();
		            	$userbclog->user_id = user()->id;
		            	$userbclog->source = UserBcintegralLog::SOURCE_CONSUME;
		            	$userbclog->integral = $postBcnum * -1;
		            	$userbclog->save();
		            }
		            /* 如果秒杀活动 */
		            if($data['miaosha_state'] && $miaoshaResult) {
		            	$miaoshaResult->order_id = $order->id;
		            	$miaoshaResult->create_time = time();
		            	$miaoshaResult->save();
		            	/* 如果秒杀数量达到 结束当前秒杀 */
		            	$criteria = new CDbCriteria();
			    		$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id));
			    		$criteria->addCondition('order_id > 0');
			    		$resultCount = MiaoshaResult::model()->count($criteria);
		            	if($resultCount >= $miaosha->active_num) {
			            	if($miaosha->state == Miaosha::STATE_OPEN) {
				    			$miaosha->state = Miaosha::STATE_OVER;
				    			$miaosha->save();
				    		}
		            	}
		            }
		            /* 完成订单跳转到成功显示页面 */
	                $this->redirect(url('order/view', array('orderid'=>$order->id, 'ordersn'=>$order->orderSn)));
	            	exit(0);
	            } else {
	            	$data['errorSummary'] = CHtml::errorSummary($order);
	            }
            }
            /* 提交过后的信息再给返回 */
            $address_default = new UserAddress();
	    	$address_default->attributes = $_POST['UserAddress'];
	    }
	 	
		if(user()->isGuest) {
	    	$data['loginModel'] = new LoginForm();
	    } else {
	    	$address = UserAddress::model()->findAllByAttributes(array('user_id'=>user()->id));
	    	$data['address'] = $address;
	    	if(!$address_default) {
		    	foreach($address as $value) {
			    	if($value->is_default) {
			    		$address_default = $value;
			    	}
			    }
	    	}
	    }
		$data['address_default'] = $address_default;
		
	    $view = 'checkout';
	    /* 如果蛋糕商家下的商品 */
	    if($cart[0]->goods->shop->category_id == ShopCategory::CATEGORY_CAKE) {
	    	$view = 'checkout_cake';
	    	/* 是否自提商品处理 */
	    	$data['isCarry'] = false;
	    	foreach($cart as $v) {
	    		if($v->goods->is_carry == STATE_DISABLED) {
	    			$data['isCarry'] = false;
	    			break;
	    		} else {
	    			$data['isCarry'] = true;
	    		}
	    	}
	    }
	    
	    $this->breadcrumbs = array(
	        '我的购物车' => url('cart/checkout'),
	    );
		
	    $this->pageTitle = '提交订单';
		$this->render($view, $data);
	}

	public function actionPhoneCheckout()
	{
		/* 判断购物车是否为空 */
		$cartNum = Cart::getGoodsCount();
	    if($cartNum == 0) {
	  		exit;
	    }

		$order = new Order();
      	$order->shop_id = (int)$_POST['shop_id'];
     	$order->message = $_POST['message'];
     	$loc = Location::getLastVisit();
     	if (is_numeric($loc)) {
     	    $location = Location::model()->findByPk($loc);
     	    if ($location) $order->address = $location->name;
     	} elseif (is_array($loc)) {
     	    $order->address = implode(',', $loc);
     	}
   		$order->city_id = $this->city['id'];
      	$order->buy_type = Shop::BUYTYPE_TELPHONE;
      	$order->status = Order::STATUS_COMPLETE;
      	if($order->save()) {
      		$integral['user_integral'] = $_SESSION['integral'];
	    	$integral['lastintegral'] = $integral['user_integral'] - param('markUserAddOrder');
	    	$integral['min_integral'] = $integral['lastintegral'];
	    	$integral['max_integral'] = $integral['user_integral'] + floor(param('markUserAddOrder')/3);
	    	$integral['mid_integral'] = $integral['lastintegral'] + floor(($integral['max_integral'] - $integral['min_integral'])/2);
		    $this->renderPartial('phoneCheckout', array('order' => $order, 'integral'=>$integral), false);
      	}
      	exit;
	}

	/**
	 * 更新购物车内商品资料
	 */
	public function actionUpdate()
	{
		$this->render('update');
	}

	public function actionEmpty()
	{
		$this->render('empty');
	}

	/**
	 *  购物车里的商品与商家有冲突
	 */
	public function actionConflict()
	{
		$cart = Cart::model()->findByAttributes(array('guest_id'=>$this->token));
		$this->renderPartial('conflict', array('cart' => $cart));
	}

	/**
	 * 添加购物车商品备注
	 */
	public function actionAddblessing()
	{
		$cakeblessing = strip_tags($_POST['cakeblessing']);
		$cardblessing = strip_tags($_POST['cardblessing']);
		$cartid = intval($_POST['cartid']);
		$cart = Cart::model()->findByPk($cartid);
		if($cart) {
			$cart->remark = $cakeblessing . CakeGoods::SEPARATOR_BLESSING . $cardblessing;
			if($cart->save()) {
				exit;
			}
		}
		echo -1;
	}

	/**
	 * 提示：选择楼宇
	 */
	public function actionSelectBuilding($shopid=null, $goodsid=null)
	{
		$shopid = intval($shopid);
		$referer = aurl('shop/show', array('shopid'=>$shopid));
		if(!$shopid) {
			$goodsid = intval($goodsid);
			$goods = Goods::model()->findByPk($goodsid);
			$shopid = $goods->shop_id;
			$referer = aurl('goods/show', array('goodsid'=>$goodsid));
		}
		$buildings = Shop::getSupportingBuilding($shopid, $this->city['id']);
		$this->renderPartial('selectbuilding', array('buildings' => $buildings, 'referer'=>$referer));
	}
	
	/**
	 * 提示：您的购物车里有非同楼订餐的商品
	 */
	public function actionNoGroupInCart()
	{
		$this->renderPartial('nogroupincart');
	}

	/**
	 * 提示：您的购物车里有同楼订餐的商品
	 */
	public function actionGroupInCart()
	{
		$this->renderPartial('groupincart');
	}
}