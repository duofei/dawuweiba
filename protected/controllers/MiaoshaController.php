<?php
class MiaoshaController extends Controller
{
	public function init() {
		$this->redirect(url('miaosha2/index'));
		exit;
		$this->layout = 'miaosha';
	}
	public function actionIndex()
	{
		$startTime = param('miaoshaStartTime');
		$endTime = param('miaoshaEndTime');
		$criteria = new CDbCriteria();
		$criteria->addCondition('t.state != ' . Miaosha::STATE_CLOSE);
		$criteria->addBetweenCondition('active_time', $startTime, $endTime);
		$criteria->order = 'active_time asc';
		$miaosha = Miaosha::model()->with('shop')->findAll($criteria);
		$miaoshalist = array();
		$temp = 0;
		$temp2 = 0;
		foreach ($miaosha as $m) {
			if($m->state == Miaosha::STATE_OPEN) {
				if($temp == 0) {
					$temp = $m->active_time;
				} elseif($temp != $m->active_time) {
					$temp2 = $m->active_time;
					break;
				}
			}
		}
		foreach ($miaosha as $m) {
			if($temp == $m->active_time) {
				$miaoshalist[] = $m;
			} elseif($m->active_time > $temp) {
				break;
			}
		}
		
		if(!$miaoshalist) {
			$this->render('over');
			exit;
		}
		
		if ($temp2 && $temp2 < time()) {
			foreach ($miaoshalist as $m) {
				$m->state = Miaosha::STATE_OVER;
				$m->save();
			}
			$this->redirect(url('miaosha/index'));
		}
		
		$lastLatLng = Location::getLastCoordinate();
		$notInArea = true;
		$region = array();
		$shopInArea = array();
		foreach ($miaoshalist as $m) {
			$points = $m->shop->getMapRegion();
			if($lastLatLng && CdcBetaTools::pointInPolygon($points, $lastLatLng[0], $lastLatLng[1])) {
				$notInArea = false;
			} else {
				$shopInArea[$m->shop->id] = 'disabled';
			}
			foreach ($points as $p) {
				if(!isset($region['max_lat']) || $p[1] > $region['max_lat']) $region['max_lat'] = $p[1];
				if(!isset($region['max_lng']) || $p[0] > $region['max_lng']) $region['max_lng'] = $p[0];
				if(!isset($region['min_lat']) || $p[1] < $region['min_lat']) $region['min_lat'] = $p[1];
				if(!isset($region['min_lng']) || $p[0] < $region['min_lng']) $region['min_lng'] = $p[0];
			}
		}
		
		$center['lat'] = ($region['max_lat'] - $region['min_lat'])/2 + $region['min_lat'];
		$center['lng'] = ($region['max_lng'] - $region['min_lng'])/2 + $region['min_lng'];
		
		$colors = array('#00CCFF', '#fad401', '#F4588F','#2eb10f');
		
		$error = false;
		if($notInArea) $error = true;
		if(user()->isGuest) {
			$error = true;
		}
		
		/* 判断用户今天是否已参加秒杀 */
		$myTodayMiaosha = 0;
		if(user()->id) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('user_id'=>user()->id));
			$criteria->addBetweenCondition('create_time', mktime(0,0,0,date('m'),date('d'),date('Y')), mktime(23,59,59,date('m'),date('d'),date('Y')));
			$criteria->addCondition('order_id > 0');
			$myTodayMiaosha = MiaoshaResult::model()->count($criteria);
			if($myTodayMiaosha > 0) {
				$error = true;
			}
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
			'miaoshalist' => $miaoshalist,
			'miaosha' => $miaosha,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
			'error' => $error,
			'error_flash' => user()->getFlash('error'),
			'notInArea' => $notInArea,
			'shopInArea' => $shopInArea,
			'myTodayMiaosha' => $myTodayMiaosha,
			'user' => $user,
			'userAddressCount' => $userAddressCount
		));
	}
	
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
				$this->redirect(url('miaosha/error'));
				exit;
			}
			
			/* 查看是否允许秒杀 */
			if($miaosha->active_time > time()) {
				user()->setFlash('error', '秒杀还未开始，你别来捣蛋');
				$this->redirect(url('miaosha/index'));
				exit;
			}
			
			/* 判断goods_id是否在秒杀列表里 */
			foreach ((array)$miaosha->miaoshaGoods as $goods) {
				$goodsids[] = $goods->goods_id;
			}
			if(!in_array($goods_id, $goodsids)) {
				user()->setFlash('error', '没有些对应的商品，非法操作');
				$this->redirect(url('miaosha/index'));
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
				$this->redirect(url('miaosha/index'));
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
					$this->redirect(url('miaosha/index'));
					exit;
				}
			} else {
				$myCurrentMiaosha->user_id = $user_id;
				$myCurrentMiaosha->goods_id = $goods_id;
				$myCurrentMiaosha->miaosha_id = $miaosha_id;
				if(!$myCurrentMiaosha->save()) {
					user()->setFlash('error', '秒杀失败或已结束');
					$this->redirect(url('miaosha2/fail'));
					exit;
				}
			}
			
			/* 当前秒杀是否过期 */
			if($miaosha->state == Miaosha::STATE_OVER) {
				user()->setFlash('error', '当前商铺这轮秒杀已结束');
				$this->redirect(url('miaosha/error'));
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
				$this->redirect(url('miaosha/error'));
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
		$this->redirect(url('miaosha/index'));
		exit;
	}
	
	public function actionError()
	{
		/* 清空购物车，加入秒杀成功的物品 */
		Cart::clearCart();
		
		$error = user()->getFlash('error');
		$this->render('error', array('error'=>$error));
	}
	
	public function actionRules()
	{
		$this->layout = 'black';
		$this->render('rules');
	}
	
	public function actionFail()
	{
		/* 清空购物车，加入秒杀成功的物品 */
		Cart::clearCart();
		
		$this->pageTitle = '秒杀失败';
		$this->render('fail');
	}

	public function actionHistory()
	{
		$d = $_GET['d'];
		if(!$d) { $d = time(); }
		$s = mktime(0,0,0,date('m', $d),date('d', $d),date('Y', $d));
		$e = mktime(23,59,59,date('m', $d),date('d', $d),date('Y', $d));
		$criteria = new CDbCriteria();
		$criteria->addBetweenCondition('create_time', $s, $e);
		$criteria->addCondition('order_id > 0');
		$pages = new CPagination(MiaoshaResult::model()->count($criteria));
		$pages->pageSize = 50;
		$pages->applyLimit($criteria);
		$history = MiaoshaResult::model()->findAll($criteria);
		$this->pageTitle = '秒杀历史记录';
		$this->render('history', array(
			'history' => $history,
			'd' => $d,
			'pages' => $pages
		));
	}

}