<?php
class Miaosha2Controller extends Controller
{
	public function init()
	{
		$this->layout = 'miaosha2';
	}
	
	public function getLeftData()
	{
		/* 接收时间 */
		$t = intval($_GET['t']);
		//echo param('miaoshaStartTime') . '<br />' . $t. '<br />' . param('miaoshaEndTime'). '<br />';
		if($t < param('miaoshaStartTime') || $t > param('miaoshaEndTime')) {
			$t = time();
		}
		/* 查询当天的秒杀 */
		$startTime = mktime(0,0,0, date('m',$t), date('d', $t), date('Y', $t));
		$endTime = mktime(23,59,59, date('m',$t), date('d', $t), date('Y', $t));
		$criteria = new CDbCriteria();
		$criteria->addCondition('t.state != ' . Miaosha::STATE_CLOSE);
		$criteria->addBetweenCondition('active_time', $startTime, $endTime);
		$criteria->order = 'active_time asc';
		$miaosha = Miaosha::model()->with('shop')->findAll($criteria);
		/* 当天参与的商铺 */
		$todayShops = array();
		foreach ($miaosha as $m) {
			$todayShops[$m->shop_id] = $m->shop;
		}

		/* 获取用户地址 */
		$lastLatLng = Location::getLastCoordinate();
		
		/* 判断用户是否在活动范围之内 */
		$notInArea = true;
		$region = array();
		$shopInArea = array();
		foreach ($todayShops as $shop) {
			$points = $shop->getMapRegion();
			if($lastLatLng && CdcBetaTools::pointInPolygon($points, $lastLatLng[0], $lastLatLng[1])) {
				$notInArea = false;
			} else {
				$shopInArea[$shop->id] = 'disabled';
			}
			foreach ($points as $p) {
				if(!isset($region['max_lat']) || $p[1] > $region['max_lat']) $region['max_lat'] = $p[1];
				if(!isset($region['max_lng']) || $p[0] > $region['max_lng']) $region['max_lng'] = $p[0];
				if(!isset($region['min_lat']) || $p[1] < $region['min_lat']) $region['min_lat'] = $p[1];
				if(!isset($region['min_lng']) || $p[0] < $region['min_lng']) $region['min_lng'] = $p[0];
			}
		}
		/* 地图中心点 */
		$center['lat'] = ($region['max_lat'] - $region['min_lat'])/2 + $region['min_lat'];
		$center['lng'] = ($region['max_lng'] - $region['min_lng'])/2 + $region['min_lng'];
		/* 地图颜色 */
		$colors = array('#00CCFF', '#fad401', '#F4588F','#2eb10f');
		
		return array(
			't' => $t,
			'todayShops' => $todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
		);
	}
	
	public function actionIndex()
	{
		/* 接收时间 */
		$t = intval($_GET['t']);
		if($t < param('miaoshaStartTime') || $t > param('miaoshaEndTime')) {
			$t = time();
		}
		/* 查询当天的秒杀 */
		$startTime = mktime(0,0,0, date('m',$t), date('d', $t), date('Y', $t));
		$endTime = mktime(23,59,59, date('m',$t), date('d', $t), date('Y', $t));
		$criteria = new CDbCriteria();
		$criteria->addCondition('t.state != ' . Miaosha::STATE_CLOSE);
		$criteria->addBetweenCondition('active_time', $startTime, $endTime);
		$criteria->order = 'active_time asc';
		$miaosha = Miaosha::model()->with('shop')->findAll($criteria);
		$temp = 0;
		$temp2 = 0;
		$todayCountOrderNum = 0;
		$todayCompleteOrderNum = 0;
		/* 当天参与的商铺 */
		$todayShops = array();
		foreach ($miaosha as $m) {
			$todayShops[$m->shop_id] = $m->shop;
			if($m->state == Miaosha::STATE_OPEN) {
				if($temp == 0) {
					$temp = $m->active_time;
				} elseif($temp != $m->active_time) {
					$temp2 = $m->active_time;
					break;
				}
			}
			/* 计算总共可以抢的单数 */
			$todayCountOrderNum += $m->active_num;
		}
		/* 当期秒杀列表 */
		$miaoshalist = array();
		foreach ($miaosha as $m) {
			if($temp == $m->active_time) {
				$miaoshalist[] = $m;
			} elseif($m->active_time > $temp) {
				break;
			}
		}
		/* 如果不存在当天的都已结束 */
		if(!$miaoshalist) {
			/* 如果是今天，显示今日秒杀结束 否则显示秒杀记录 */
			if($t >= mktime(0,0,0, date('m'), date('d'), date('Y'))) {
				$this->redirect(url('miaosha2/over',array('t'=>$t)));
			} else {
				$this->redirect(url('miaosha2/history', array('t'=>$t)));
			}
			exit;
		}
		/* 如果秒杀超时等到下轮还未抢完将自动结束 */
		if ($temp2 && $temp2 < time()) {
			foreach ($miaoshalist as $m) {
				$m->state = Miaosha::STATE_OVER;
				$m->save();
			}
			$this->redirect(url('miaosha2/index'));
			exit;
		}
		
		/* 获取用户地址 */
		$lastLatLng = Location::getLastCoordinate();
		
		/* 判断用户是否在活动范围之内 */
		$notInArea = true;
		$region = array();
		$shopInArea = array();
		foreach ($todayShops as $shop) {
			$points = $shop->getMapRegion();
			if($lastLatLng && CdcBetaTools::pointInPolygon($points, $lastLatLng[0], $lastLatLng[1])) {
				$notInArea = false;
			} else {
				$shopInArea[$shop->id] = 'disabled';
			}
			foreach ($points as $p) {
				if(!isset($region['max_lat']) || $p[1] > $region['max_lat']) $region['max_lat'] = $p[1];
				if(!isset($region['max_lng']) || $p[0] > $region['max_lng']) $region['max_lng'] = $p[0];
				if(!isset($region['min_lat']) || $p[1] < $region['min_lat']) $region['min_lat'] = $p[1];
				if(!isset($region['min_lng']) || $p[0] < $region['min_lng']) $region['min_lng'] = $p[0];
			}
		}
		/* 地图中心点 */
		$center['lat'] = ($region['max_lat'] - $region['min_lat'])/2 + $region['min_lat'];
		$center['lng'] = ($region['max_lng'] - $region['min_lng'])/2 + $region['min_lng'];
		/* 地图颜色 */
		$colors = array('#00CCFF', '#fad401', '#F4588F','#2eb10f');
		
		
		/* 计算已抢多少单 */
		$c = new CDbCriteria();
		$c->addCondition('t.order_id > 0 ');
		$c->addBetweenCondition('create_time', $startTime, $endTime);
		$todayCompleteOrderNum = MiaoshaResult::model()->count($c);
		
		/* 判断用户今天是否已参加秒杀 */
		$myTodayMiaosha = 0;
		if(user()->id) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('user_id'=>user()->id));
			$criteria->addBetweenCondition('create_time', mktime(0,0,0,date('m'),date('d'),date('Y')), mktime(23,59,59,date('m'),date('d'),date('Y')));
			$criteria->addCondition('order_id > 0');
			$myTodayMiaosha = MiaoshaResult::model()->count($criteria);
		}
		
		/* 取用户信息 */
		$user = null;
		$userAddressCount = 0;
		if(user()->id) {
			$user = User::model()->findByPk(user()->id);
			$c = new CDbCriteria();
			$c->addColumnCondition(array('user_id'=>user()->id));
			$userAddressCount = UserAddress::model()->count($c);
		}
		
		$this->pageTitle = "秒杀活动";
		$this->render('index', array(
			't' => $t,
			'todayShops' => $todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
			'todayCountOrderNum' => $todayCountOrderNum,
			'todayCompleteOrderNum' => $todayCompleteOrderNum,
			'miaoshalist' => $miaoshalist,
			'shopInArea' => $shopInArea,
			'userAddressCount' => $userAddressCount,
			'user' => $user,
			'myTodayMiaosha' => $myTodayMiaosha,
			'notInArea' => $notInArea
		));
	}
	
	public function actionOver()
	{
		/* 清空购物车，加入秒杀成功的物品 */
		Cart::clearCart();
		
		$data = $this->getLeftData();
		$this->pageTitle = "今天秒杀已结束";
		$this->render('over', $data);
	}

	public function actionHistory()
	{
		$data = $this->getLeftData();
		
		/* 接收时间 */
		$t = intval($_GET['t']);
		if($t < param('miaoshaStartTime') || $t > param('miaoshaEndTime')) {
			$t = time();
		}
		/* 查询当天的秒杀 */
		$startTime = mktime(0,0,0, date('m',$t), date('d', $t), date('Y', $t));
		$endTime = mktime(23,59,59, date('m',$t), date('d', $t), date('Y', $t));
		$criteria = new CDbCriteria();
		$criteria->addBetweenCondition('create_time', $startTime, $endTime);
		$criteria->addCondition('order_id > 0');
		$data['history'] = MiaoshaResult::model()->findAll($criteria);
		
		$this->pageTitle = "往期秒杀记录";
		$this->render('history', $data);
	}
	
	public function actionFail()
	{
		/* 清空购物车，加入秒杀成功的物品 */
		Cart::clearCart();
		
		$data = $this->getLeftData();
		$this->pageTitle = "这轮秒杀已结束";
		$this->render('fail', $data);
	}
	
	public function actionError()
	{
		/* 清空购物车，加入秒杀成功的物品 */
		Cart::clearCart();
		
		$data = $this->getLeftData();
		$data['error'] = user()->getFlash('error');
		
		$this->pageTitle = "秒杀提示";
		$this->render('error', $data);
	}
	
	public function actionRules()
	{
		$data = $this->getLeftData();
		$this->pageTitle = "秒杀规则";
		$this->render('rules', $data);
	}
	
	public function actionFeedback()
	{
		$data = $this->getLeftData();
		$data['model'] = new Feedback();
		$data['errormodel'] = user()->getFlash('errormodel');
		if($_GET['type']=='2') {
			$this->pageTitle = "意见反馈";
			$this->render('feedback2', $data);
		} else {
			$this->pageTitle = "秒杀感言";
			$this->render('feedback', $data);
		}
	}
	
	public function actionPostfeedback()
	{
		$type = intval($_GET['type']);
		if (app()->request->isPostRequest && isset($_POST['Feedback'])) {
			$feedback = new Feedback();
			$post = CdcBetaTools::filterPostData(array('content', 'post_id', 'validateCode'), $_POST['Feedback']);
			$feedback->attributes = $post;
			
			if($feedback->save()) {
				$data = $this->getLeftData();
				$data['type'] = $type;
				if($type==2) {
					$this->pageTitle = "意见反馈";
					$this->render('postfeedback', $data);
				} else {
					$this->pageTitle = "秒杀感言";
					$this->render('postfeedback', $data);
				}
				exit;
			}
		}
		user()->setFlash('errormodel', $feedback);
		$this->redirect(url('miaosha2/feedback', array('type'=>$type)));
	}
	
	/**
	 * 提交秒杀
	 */
	public function actionPost()
	{
		$user_id = user()->id; //intval($_POST['user_id']);
		$miaosha_id = intval($_POST['miaoshaid']);
		$goods_id = intval($_POST['goodsid']);
		/* 判断用户是不是已登陆，提交秒杀Id，提交秒杀商品 */
		if($user_id && $miaosha_id && $goods_id) {
			$miaosha = Miaosha::model()->with('miaoshaGoods')->findByPk($miaosha_id);
			
			/* 当前秒杀已闭 */
			if($miaosha->state == Miaosha::STATE_CLOSE) {
				user()->setFlash('error', '本次秒杀已关闭');
				$this->redirect(url('miaosha2/error'));
				exit;
			}
			
			/* 查看是否允许秒杀 */
			if($miaosha->active_time > time()) {
				user()->setFlash('error', '秒杀还未开始，你别来捣蛋');
				$this->redirect(url('miaosha2/error'));
				exit;
			}
			
			/* 判断goods_id是否在秒杀列表里 */
			foreach ((array)$miaosha->miaoshaGoods as $goods) {
				$goodsids[] = $goods->goods_id;
			}
			if(!in_array($goods_id, $goodsids)) {
				user()->setFlash('error', '没有对应的商品，非法操作');
				$this->redirect(url('miaosha2/error'));
				exit;
			}
			
			/* 判断用户是否已参加当天秒杀 */
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('user_id'=>$user_id));
			$criteria->addBetweenCondition('create_time', mktime(0,0,0,date('m'),date('d'),date('Y')), mktime(23,59,59,date('m'),date('d'),date('Y')));
			$criteria->addCondition('order_id > 0');
			$myMiaosha = MiaoshaResult::model()->count($criteria);
			if($myMiaosha > 0) {
				user()->setFlash('error', '您已参加当天秒杀，请明天再来参加');
				$this->redirect(url('miaosha2/error'));
				exit;
			}
			
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('user_id'=>$user_id, 'miaosha_id'=>$miaosha_id));
			$myCurrentMiaosha = MiaoshaResult::model()->find($criteria);
			if(!$myCurrentMiaosha) {
				/* 把用户加入到秒杀结果表 */
				$miaosha_result = new MiaoshaResult();
				$miaosha_result->user_id = $user_id;
				$miaosha_result->goods_id = $goods_id;
				$miaosha_result->miaosha_id = $miaosha_id;
				if(!$miaosha_result->save()) {
					user()->setFlash('error', '秒杀失败或已结束');
					$this->redirect(url('miaosha2/fail'));
					exit;
				}
			}
			
			/* 当前秒杀是否过期 */
			if($miaosha->state == Miaosha::STATE_OVER) {
				user()->setFlash('error', '当前商铺这轮秒杀已结束');
				$this->redirect(url('miaosha2/fail'));
				exit;
			}
			
			/* 判断数量是否已到 */
			$c = new CDbCriteria();
			$c->addCondition('order_id > 0 and miaosha_id='.$miaosha_id);
			$count = MiaoshaResult::model()->count($c);
			if($count >= $miaosha->active_num) {
				$miaosha->state = Miaosha::STATE_OVER;
				$miaosha->save();
				user()->setFlash('error', '您的手太慢了，本次秒杀名额已抢完了');
				$this->redirect(url('miaosha2/fail'));
				exit;
			}
			
			/* 清空购物车，加入秒杀成功的物品 */
			Cart::clearCart();
			
			/* 把秒杀商品加入到购物车 */
	    	$goods = Goods::model()->findByPk($goods_id);
	   	 	if (null === $goods) throw new CHttpException(404);
	   	 	$cart = new Cart();
		    $cart->goods_id = $goods_id;
		    $cart->goods_nums++;
	    	$cart->goods_price = $goods->wmPrice;
	    	$cart->group_price = $goods->groupPrice;
	    	$cart->goods_name = $goods->name;
		    
	    	if($cart->save()) {
	    		/* 跳转到下订单页面 */
		    	$this->redirect(url('miaosha2/order', array('miaosha_id'=>$miaosha_id)));
	    	}
		}
		user()->setFlash('error', '秒杀失败，你是不是没有进选择美食');
		$this->redirect(url('miaosha2/error'));
		exit;
	}

	/**
	 * 下订单
	 */
	public function actionOrder($miaosha_id)
	{
		$data = $this->getLeftData();
		
		if(user()->isGuest) {
			user()->setFlash('error', '您还未登陆');
			$this->redirect(url('miaosha2/error'));
			exit;
		}
		
		$cart = Cart::getGoodsList();
		/* 判断购物车是否为空 */
	    if(count($cart) == 0) {
	  		user()->setFlash('error', '没有对应的商品，非法操作');
			$this->redirect(url('miaosha2/error'));
			exit;
	    }
	    
		$miaosha_id = intval($miaosha_id);
		$data['miaosha_id'] = $miaosha_id;
		$criteria = new CDbCriteria();
    	$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id, 'user_id'=>user()->id, 'order_id'=>0));
    	$miaoshaResult = MiaoshaResult::model()->find($criteria);
    	if($miaoshaResult) {
    		if($miaoshaResult->goods_id != $cart[0]->goods_id || $cart[1]) {
    			user()->setFlash('error', '您的购物车里有非秒杀的商品');
				$this->redirect(url('miaosha2/error'));
				exit;
    		}
    	} else {
    		/* 非秒杀用户进来自动跳转到 秒杀首页 */
    		$this->redirect(url('miaosha2/index'));
    		exit;
    	}
    	
    	$data['cart'] = $cart;
    	
    	/* 取用户地址 */
		$address = UserAddress::model()->findAllByAttributes(array('user_id'=>user()->id));
	    $data['address'] = $address;
	    
	    /* 设置用户默认地址 */
    	$address_default = null;
    	if($_COOKIE['address'] && $_COOKIE['telphone'] && $_COOKIE['consignee']) {
    		$address_default = new UserAddress();
    		$address_default->id = null;
    		$address_default->address = $_COOKIE['address'];
    		$address_default->telphone = $_COOKIE['telphone'];
    		$address_default->consignee = $_COOKIE['consignee'];
    		$data['message'] = $_COOKIE['message'];
    	} else {
	    	if(!$address_default && $address) {
		    	$address_default = $address[0];
		    	foreach($address as $v) {
			    	if($v->is_default) {
			    		$address_default = $v;
			    	}
			    }
	    	}
    	}
    	$data['address_default'] = $address_default;
    	
    	/* 获取用户信息 */
	    $data['user'] = User::model()->findByPk(user()->id);
	    
		$this->pageTitle = "下订单";
		$this->render('order', $data);
	}
	
	/**
	 * 提交订单
	 */
	public function actionCheckout()
	{
		if(user()->isGuest) {
			user()->setFlash('error', '您还未登陆');
			$this->redirect(url('miaosha2/error'));
			exit;
		}
		
		$cart = Cart::getGoodsList();
		/* 判断购物车是否为空 */
	    if(count($cart) == 0) {
	  		user()->setFlash('error', '没有对应的商品，非法操作');
			$this->redirect(url('miaosha2/error'));
			exit;
	    }
	    
		$miaosha_id = intval($_GET['miaosha_id']);
		$criteria = new CDbCriteria();
    	$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id, 'user_id'=>user()->id, 'order_id'=>0));
    	$miaoshaResult = MiaoshaResult::model()->find($criteria);
    	if($miaoshaResult) {
    		if($miaoshaResult->goods_id != $cart[0]->goods_id || $cart[1]) {
    			user()->setFlash('error', '您的购物车里有非秒杀的商品');
				$this->redirect(url('miaosha2/error'));
				exit;
    		}
    	} else {
    		/* 非秒杀用户进来自动跳转到 秒杀首页 */
    		$this->redirect(url('miaosha2/index'));
    		exit;
    	}
    	$user = User::model()->findByPk(user()->id);

		if (app()->request->isPostRequest && isset($_POST['UserAddress'])) {
			$order = new Order('checkout');
	        $order->consignee = $_POST['UserAddress']['consignee'];
	        $order->address = $_POST['UserAddress']['address'];
	        $order->telphone = $_POST['UserAddress']['telphone'];
	        $order->message = $_POST['UserAddress']['message'];
	        
	        setcookie('consignee', $order->consignee, time()+600, '/');
	        setcookie('address', $order->address, time()+600, '/');
	        setcookie('telphone', $order->telphone, time()+600, '/');
	        setcookie('message', $order->message, time()+600, '/');
	        setcookie('miaosha_id', $miaosha_id, time()+600, '/');
	        
	        /* 如果用户未通过认证 */
			if($user->approve_state != User::APPROVE_STATE_VERIFY) {
				if($_POST['vfcode'] && User::checkSmsVerifyCode($user->id, $_POST['vfcode'])) {
					$user->approve_state = User::APPROVE_STATE_VERIFY;
					if(!$user->telphone) {
            			$user->telphone = $order->telphone;
            		}
            		$user->save(false);
				} else {
					/* 跳转到认证页面 */
					user()->setFlash('error', '验证码错误!');
					$this->redirect(url('miaosha2/approve'));
					exit;
				}
			}
			
			$order->mobile = $_POST['UserAddress']['mobile'];
			$order->shop_id = (int)$cart[0]->goods->shop->id;
			$order->deliver_time = $_POST['deliver_time'];
	        $order->pay_type = $cart[0]->goods->shop->pay_type;
	        $order->buy_type = $cart[0]->goods->shop->buy_type;
	        $order->dispatching_amount = $cart[0]->goods->shop->matchDispatchingAmount;
			$order->status = Order::STATUS_UNDISPOSED;
	        $order->is_carry = $_POST['iscarry'];
	        $order->city_id = $this->city['id'];
			
			/* 过滤IP */
			$ipArray = array('219.218.121.210', '219.218.121.209', '219.218.121.208' ,'219.218.121.211', '124.133.15.227');
			if(in_array($_SERVER['REMOTE_ADDR'], $ipArray)) {
				//sleep(1);
				//$this->redirect(url('miaosha2/fail'));
				//exit;
			}
			
			/* 过滤手机号 */
			$phoneArray = MiaoshaResult::getSuccessUserTelphone();
			if(in_array($order->telphone, $phoneArray)) {
				//sleep(1);
				//$this->redirect(url('miaosha2/fail'));
				//exit;
			}
			
			/* 过滤Cookie */
			if($_COOKIE['miaosha']) {
				//sleep(1);
				//$this->redirect(url('miaosha2/fail'));
				//exit;
			}
			
			/* 判断用户今天是否已抢到过订单 */
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('user_id'=>user()->id));
			$criteria->addBetweenCondition('create_time', mktime(0,0,0,date('m'),date('d'),date('Y')), mktime(23,59,59,date('m'),date('d'),date('Y')));
			$criteria->addCondition('order_id > 0');
			$myMiaosha = MiaoshaResult::model()->count($criteria);
			if($myMiaosha > 0) {
				sleep(1);
				user()->setFlash('error', '本活动，每一位会员一天仅限抢购一份！');
				$this->redirect(url('miaosha2/error'));
				exit;
			}
			
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id));
			$criteria->addCondition('order_id > 0');
			$resultCount = MiaoshaResult::model()->count($criteria);
			$miaosha = Miaosha::model()->findByPk($miaosha_id);
			if($miaosha->state!=Miaosha::STATE_OPEN || $resultCount >= $miaosha->active_num) {
				/* 如果此次秒杀数量已超，结束当前秒杀 */
				$miaoshaResult->create_time = time();
				$miaoshaResult->save();
				if($miaosha->state == Miaosha::STATE_OPEN) {
					$miaosha->state = Miaosha::STATE_OVER;
					$miaosha->save();
				}
				$this->redirect(url('miaosha2/fail'));
				exit;
			} else {
				$order->paid_amount = Cart::getGoodsAmount() + $cart[0]->goods->shop->matchDispatchingAmount - 1;
				$order->paid_remark = '一元秒杀活动优惠' . $order->paid_amount . '元';
				if($resultCount == 0) {
					MiaoshaResult::addUntrues($miaosha_id);
				}
			}
			
			if ($order->save()) {
				if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_PRINTER && $data['user']->approve_state==User::APPROVE_STATE_VERIFY) {
		            UserInviter::inviteSuccess(user()->id); //如果是被邀请增加用户白吃点
		      	}
		      	
				/* 设置order_id */
	            $miaoshaResult->order_id = $order->id;
				$miaoshaResult->create_time = time();
				$miaoshaResult->save();
				
				/* 秒杀成功后设置Cookie */
				setcookie('miaosha', '1', time()+7200, '/');
				
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
				
				/* 完成订单跳转到成功显示页面 */
				$this->redirect(url('miaosha2/success'));
			} else {
				user()->setFlash('error', '提交订单失败，可能是您的信息填写不全！');
				$this->redirect(url('miaosha2/error'));
			}
		}
		$this->redirect(url('miaosha2/index'));
	}
	
	/**
	 * 用户认证
	 */
	public function actionApprove()
	{
		$data = $this->getLeftData();
		$data['address'] = $_COOKIE['address'];
		$data['telphone'] = $_COOKIE['telphone'];
		$data['consignee'] = $_COOKIE['consignee'];
		$data['message'] = $_COOKIE['message'];
		$data['miaosha_id'] = $_COOKIE['miaosha_id'];
		
		if(user()->isGuest) {
			user()->setFlash('error', '您还未登陆！');
			$this->redirect(url('miaosha2/error'));
		}
		
		$data['error'] = null;
		
		if($_COOKIE['isSend'] != '1') {
			if(SendSms::filter_mobile($data['telphone'])) {
				if(User::sendSmsVerifyCode(user()->id, $data['telphone'])){
					setcookie('isSend', 1, time()+120, '/');
				}
			} elseif(SendVoice::filter_phone($data['telphone'])) {
				if(User::sendVoiceVerifyCode(user()->id, $data['telphone'])){
					setcookie('isSend', 1, time()+120, '/');
				}
			} else {
				$data['error'] = '手机号码有误!';
			}
		}
		$data['error'] = user()->getFlash('error');
		$this->pageTitle = "用户认证";
		$this->render('approve', $data);
	}
	
	/**
	 * 秒杀成功
	 */
	public function actionSuccess()
	{
		$data = $this->getLeftData();
		$this->pageTitle = "秒杀成功";
		$this->render('success', $data);
	}
}