<?php

class StatisticsController extends Controller
{
	/**
	 * 销量统计
	 */
	public function actionSales($type = 0)
	{
	    $date = date('Y-m-d');
		$LastDay = strtotime($date);
		$today = strtotime('next Day', $LastDay);
		
		$LastWeek = $today - (8 * 24 * 60 * 60);
		$LastMonth = $today - (31 * 24 * 60 * 60);
		$sixMonth = $today - (181 * 24 * 60 * 60);
		
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
	    $goods = Goods::model()->findAll($condition);
	    
	    $condition->addCondition('create_time>='.$sixMonth);
	    $condition->addColumnCondition(array('status'=>'3'));
	    $order = Order::model()->findAll($condition);
		
		$statistics = array(
			'1Num'=>0,
			'1Amount'=>0,
			'7Num'=>0,
			'7Amount'=>0,
			'30Num'=>0,
			'30Amount'=>0,
		);
		$type = (int)$type;
		$typetext = $type?$type:'7';
		$type = $type?$type:'7';
	
		foreach ($order as $key=>$val) {
			if ($val->create_time >= $LastDay) {
				$statistics['1Num']++;
				$statistics['1Amount'] += $val->amount;
			}
			if ($val->create_time >= $LastWeek) {
				$statistics['7Num'] ++;
				$statistics['7Amount'] += $val->amount;
				if ($type == '7') {
					foreach ($val->orderGoods as $k=>$v) {
						foreach ($goods as $e=>$c) {
							if ($c->id == $v->goods_id) {
								$statistics['7goodsNum'][$c->name] += $v->goods_nums;
								$statistics['7goodsAmount'][$c->name] += $v->goods_amount;
								$max = max($statistics['7goodsNum']);
								$maxkey = array_search($max, $statistics['7goodsNum']);
								$statistics['7goodsAmountMax'] = $maxkey;
							}
						}
					}
				}
			}
			if ($val->create_time >= $LastMonth) {
				$statistics['30Num']++;
				$statistics['30Amount'] += $val->amount;
				if ($type == '30') {
					foreach ($val->orderGoods as $k=>$v) {
						foreach ($goods as $e=>$c) {
							if ($c->id == $v->goods_id) {
								$statistics['30goodsNum'][$c->name] += $v->goods_nums;
								$statistics['30goodsAmount'][$c->name] += $v->goods_amount;
								$max = max($statistics['30goodsNum']);
								$maxkey = array_search($max, $statistics['30goodsNum']);
								$statistics['30goodsAmountMax'] = $maxkey;
							}
						}
					}
				}
			}
			if ($val->create_time >= $sixMonth) {
				$statistics['180Amount'] += $val->amount;
				if ($type == '180') {
					foreach ($val->orderGoods as $k=>$v) {
						foreach ($goods as $e=>$c) {
							if ($c->id == $v->goods_id) {
								$statistics['180goodsNum'][$c->name] += $v->goods_nums;
								$statistics['180goodsAmount'][$c->name] += $v->goods_amount;
								$max = max($statistics['180goodsNum']);
								$maxkey = array_search($max, $statistics['180goodsNum']);
								$statistics['180goodsAmountMax'] = $maxkey;
							}
						}
					}
				}
			}
		}
		foreach ((array)$_GET['Order'] as $key=>$val){
			$order_get[$key] = strip_tags(trim($val));
		}
		if ($order_get) {
			$start_time = strtotime($order_get['create_time_start']);
			$end_time = strtotime('next Day', strtotime($order_get['create_time_end']));
			
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		    if ($order_get['create_time_start']) {
		    	$condition->addCondition('create_time>=' . $start_time);
		    }
		    if ($order_get['create_time_end']) {
		    	$condition->addCondition('create_time<=' . $end_time);
		    }
	    	$condition->addColumnCondition(array('status'=>'3'));
		    $order = Order::model()->findAll($condition);
		    
		    $typetext = $order_get['create_time_start'].'到'.$order_get['create_time_end'];
			$type = '';
		    
			foreach ($order as $key=>$val) {
				$statistics['Amount'] += $val->amount;
				foreach ($val->orderGoods as $k=>$v) {
					foreach ($goods as $e=>$c) {
						if ($c->id == $v->goods_id) {
							$statistics['goodsNum'][$c->name] += $v->goods_nums;
							$statistics['goodsAmount'][$c->name] += $v->goods_amount;
							$max = max($statistics['goodsNum']);
							$maxkey = array_search($max, $statistics['goodsNum']);
							$statistics['goodsAmountMax'] = $maxkey;
						}
					}
				}
			}
		}
		if ($statistics[$type.'goodsNum']) { arsort($statistics[$type.'goodsNum']);}
	    $data = array(
	    	'销量统计' => array(
	    		'id' => 'sales',
	    		'content' => $this->renderPartial('sales', array('statistics' => $statistics, 'typetext'=>$typetext, 'type'=>$type, 'order_get'=>$order_get), true)
	    	),
	    );
	    $this->pageTitle = '销量统计';
	    $this->render('/public/tab', array('tabs'=>$data));
	}
	
	/**
     * 配送员统计
     */
	public function actionDelivery()
	{
	    $condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
	    $condition->addColumnCondition(array('state' => DeliveryMan::STATUS_NORMAL));
	    $deliveryMan = DeliveryMan::model()->findAll($condition);
	    
		foreach ((array)$_GET['Order'] as $key=>$val){
			$order_get[$key] = strip_tags(trim($val));
		}
//		$order_get['delivery'] = (int)$order_get['delivery'];
	    if($order_get) {
			$start_time = strtotime($order_get['create_time_start']);
			$end_time = strtotime('next Day', strtotime($order_get['create_time_end']));
			
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('t.shop_id' => $_SESSION['shop']->id));
		    if ($order_get['delivery']) {
		    	$condition->addColumnCondition(array('t.delivery_id' => $order_get['delivery']));
		    }
		    if ($order_get['create_time_start']) {
		    	$condition->addCondition('t.create_time>=\'' . $start_time . '\'');
		    }
		    if ($order_get['create_time_end']) {
		    	$condition->addCondition('t.create_time<=\'' . $end_time . '\'');
		    }
	    	$condition->order = 't.id desc';
		    $order = Order::model()->with('orderGoods', 'deliveryMan', 'orderDeliveringLog', 'orderCompleteLog')->findAll($condition);
		    
	    }
		    $data = array(
    	    	'配送员业绩' => array(
    	    		'id' => 'delivery',
    	    		'content' => $this->renderPartial('delivery', array('order'=>$order, 'deliveryMan'=>$deliveryMan, 'order_get'=>$order_get), true)
    	    	),
	        );
    	    
	    $this->pageTitle = '配送员统计';
	    $this->render('/public/tab', array('tabs'=>$data));
	}
	
	
    /**
     * 结算中心
     */
	public function actionBalance()
	{
		$this->render('balance');
	}

	/**
	 * 定单统计
	 */
	public function actionOrder()
	{
		$this->render('order');
	}

	/**
	 * 商品统计
	 */
	public function actionGoods()
	{
		$this->render('goods');
	}

	/**
	 * 用户统计
	 */
	public function actionUser()
	{
		$this->pageTitle = '用户统计';
		$this->render('user');
	}

	/**
	 * 统计概况页面
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	public function filters()
	{
	    return array(
	    	'postOnly + search',
	    );
	}
}