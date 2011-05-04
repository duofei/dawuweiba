<?php
class OrderController extends Controller
{
	public function actionToday()
	{
		$citylist = City::model()->findAll();
		
		$order = new Order();
		foreach ($citylist as $key=>$val){
			$ordercount[$val->name]['count']= $order->getCountOfToday($val->id);
			$ordercount[$val->name]['amount']= $order->getAmountOfToday($val->id);
		}
	    $this->render('today', array('ordercount'=>$ordercount));
	}
	
	public function actionSearch()
	{
		foreach ((array)$_GET['Order'] as $key=>$val){
			$order_get[$key] = strip_tags(trim($val));
		}
		if($order_get) {
			$start_time = strtotime($order_get['create_time_start']);
			$end_time = strtotime($order_get['create_time_end']);
			$end_time = strtotime('next Day', $end_time);
			$citylist = City::getCityArray();
		    if ($order_get['city_id'] != '') {
				$condition = new CDbCriteria();
			    if ($order_get['create_time_start']) {
			    	$condition->addCondition('t.create_time>=' . $start_time);
			    }
			    if ($order_get['create_time_end']) {
			    	$condition->addCondition('t.create_time<=' . $end_time);
			    }
			    if ($order_get['status'] != '') {
			    	$condition->addCondition('t.status = ' . $order_get['status']);
			    }
			    if ($order_get['category_id'] != '') {
			    	$condition->addCondition('shop.category_id = ' . $order_get['category_id']);
			    }
	   			$condition->addCondition('t.city_id = ' . $order_get['city_id']);
				$ordercount[$citylist[$order_get['city_id']]]['count'] = Order::model()->with('shop')->count($condition);
    			$ordercount[$citylist[$order_get['city_id']]]['amount'] = app()->db->createCommand("select SUM(amount) FROM {{Order}} `t`  LEFT OUTER JOIN {{Shop}} `shop` ON (`t`.`shop_id`=`shop`.`id`) WHERE " . $condition->condition)->queryColumn();
		    } else {
				foreach ($citylist as $key=>$val){
					$condition = new CDbCriteria();
				    if ($order_get['create_time_start']) {
				    	$condition->addCondition('t.create_time>=' . $start_time);
				    }
				    if ($order_get['create_time_end']) {
				    	$condition->addCondition('t.create_time<=' . $end_time);
				    }
				    if ($order_get['status'] != '') {
			    		$condition->addCondition('t.status = ' . $order_get['status']);
				    }
				    if ($order_get['category_id'] != '') {
			    		$condition->addCondition('shop.category_id = ' . $order_get['category_id']);
				    }
	   				$condition->addCondition('t.city_id = ' . $key);
					$ordercount[$val]['count'] = Order::model()->with('shop')->count($condition);
    				$ordercount[$val]['amount'] = app()->db->createCommand("select SUM(amount) FROM {{Order}} `t`  LEFT OUTER JOIN {{Shop}} `shop` ON (`t`.`shop_id`=`shop`.`id`) WHERE " . $condition->condition)->queryColumn();
				}
		    }
		    $this->render('statistics', array('ordercount'=>$ordercount, 'citylist'=>$citylist, 'order_get'=>$order_get, 'pages'=>$pages));
		}else{
			$this->redirect(url('admin/shop/statistics'));
		}
	}
	
	/**
	 * 统计
	 * Enter description here ...
	 */
	public function actionStatistics()
	{
		$citylist = City::getCityArray();
		
		$order = new Order();
		foreach ($citylist as $key=>$val){
			$ordercount[$val]['count']= $order->getCountOfCity($key);
			$ordercount[$val]['amount']['0']= $order->getAmountOfCity($key);
		}
	    $this->render('statistics', array('ordercount'=>$ordercount, 'citylist'=>$citylist));
	}
}