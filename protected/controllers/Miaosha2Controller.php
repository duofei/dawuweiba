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
			if($t > mktime(0,0,0, date('m'), date('d'), date('Y'))) {
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
		$this->pageTitle = "秒杀感言";
		$this->render('feedback', $data);
	}
	
	public function actionPostfeedback()
	{
		if (app()->request->isPostRequest && isset($_POST['Feedback'])) {
			$feedback = new Feedback();
			$post = CdcBetaTools::filterPostData(array('content', 'post_id', 'validateCode'), $_POST['Feedback']);
			$feedback->attributes = $post;
			
			if($feedback->save()) {
				$data = $this->getLeftData();
				$this->pageTitle = "秒杀感言";
				$this->render('postfeedback', $data);
				exit;
			}
		}
		user()->setFlash('errormodel', $feedback);
		$this->redirect(url('miaosha2/feedback'));
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
				user()->setFlash('error', '没有些对应的商品，非法操作');
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
		    	$this->redirect(url('cart/checkout', array('miaosha_id'=>$miaosha_id)));
	    	}
		}
		user()->setFlash('error', '秒杀失败，你是不是没有进选择美食');
		$this->redirect(url('miaosha2/error'));
		exit;
	}

	/**
	 * 下订单
	 */
	public function actionOrder()
	{
		$data = $this->getLeftData();
		
		$this->pageTitle = "下订单";
		$this->render('order', $data);
	}
	
	/**
	 * 提交订单
	 */
	public function actionCheckout()
	{

	}
}