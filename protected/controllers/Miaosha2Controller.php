<?php
class Miaosha2Controller extends Controller
{
	public function init()
	{
		$this->layout = 'miaosha2';
	}
	
	public function actionIndex()
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
			$this->redirect(url('miaosha2/over',array('t'=>$t)));
			exit;
		}
		/* 如果秒杀超时等到下轮还未抢完将自动结束 */
		if ($temp2 && $temp2 < time()) {
			foreach ($miaoshalist as $m) {
				$m->state = Miaosha::STATE_OVER;
				$m->save();
			}
			$this->redirect(url('miaosha/index'));
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
		));
	}
	
	public function actionOver()
	{
		$data = $this->getLeftData();
		$this->pageTitle = "今天秒杀已结束";
		$this->render('over', $data);
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
			'todayShops' => $todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
		);
	}
}