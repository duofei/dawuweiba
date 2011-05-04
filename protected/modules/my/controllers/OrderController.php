<?php

class OrderController extends Controller
{
	/**
	 * 查询订单
	 * @param object $condition 查询条件
	 * @param boolean $noRecommend 是否是未点评订单
	 * @return array 订单列表
	 */
	public function loadOrderList($condition, $orderName='')
	{
		if($orderName != 'NoRecommend') {
			$pages = new CPagination(Order::model()->count($condition));
			$pages->pageSize = 10;
			$pages->applyLimit($condition);
		}
		
		$orderList = Order::model()->with(array(
			'shop',
			'groupon',
			'shopCreditLogs',
			'orderGoods',
			'orderGoods.goodsRateLog',
			'orderLogs'=>array('order'=>'orderLogs.create_time desc'),
		))->findAll($condition);
		
		$orderIsNoRating = array();
		$newOrderList = array();
		
		foreach ($orderList as $row) {
			$orderIsNoRating[$row->id] = false; // 是否未点评
			if(!$row->shopCreditLogs->id && ($row->status==Order::STATUS_COMPLETE || $row->status==Order::STATUS_DELIVERING)) {
				$orderIsNoRating[$row->id] = true;
			} else {
				foreach($row->orderGoods as $goods) {
					if(($row->status==Order::STATUS_COMPLETE || $row->status==Order::STATUS_DELIVERING) && !$goods->goodsRateLog->goods_id) {
						$orderIsNoRating[$row->id] = true;
						break;
					}
				}
			}
			if($orderIsNoRating[$row->id]) {
				$newOrderList[] = $row;
			}
		}
		
		if($orderName == 'NoRecommend') {
			$orderList = $newOrderList;
		}
		
		return array('data'=>$orderList, 'orderIsNoRating'=>$orderIsNoRating, 'pages'=>$pages);
	}
	
    /**
     * 全部订单列表
     */
	public function actionList()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '全部订单'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition);
		$this->pageTitle = '全部订单';
		$this->render('list', array('data' => $result['data'], 'pages'=>$result['pages'], 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'list'));
	}
	
	/**
	 *  未完成订单
	 */
	public function actionUncomplete()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '未完成订单'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->addCondition('t.status!=' . Order::STATUS_CANCEL);
		$condition->addCondition('t.status!=' . Order::STATUS_COMPLETE);
		$condition->addCondition('t.status!=' . Order::STATUS_INVAIN);
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition, 'UnComplete');
		$this->pageTitle = '未完成订单';
		$this->render('list', array('data' => $result['data'], 'pages'=>$result['pages'], 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'uncomplete'));
	}
	
	/**
	 *  未点评订单
	 */
	public function actionNorating()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '未点评订单'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->addInCondition('t.status', array(Order::STATUS_COMPLETE, Order::STATUS_DELIVERING));
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition, 'NoRecommend');
		$data = array();
		$norationPages = '<ul id="yw0" class="yiiPager">';
		$page = intval($_GET['page']) ? intval($_GET['page']) : 1;
		if($page > 1) {
			$norationPages .= '<li class="previous"><a href="'. url('my/order/norating', array('page'=>($page-1))) .'">上一页</a></li>';
		}
		$pagenum = 10;
		$i = 0;
		foreach ($result['data'] as $row) {
			if($i >= ($page-1)*$pagenum && $i<($page)*$pagenum) {
				$data[] = $row;
			} elseif ($i >= ($page)*$pagenum) {
				$norationPages .= '<li class="next ma-l5px"><a href="'. url('my/order/norating', array('page'=>($page+1))) .'">下一页</a></li>';
				break;
			}
			$i++;
		}
		$norationPages .= '</ul>';
		$this->pageTitle = '未点评订单';
		$this->render('list', array('data' => $data, 'norationPages'=>$norationPages, 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'norating'));
	}
	
	/**
	 *  网络订单
	 */
	public function actionOnline()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '网络订单'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->addCondition('t.buy_type in (' . Shop::BUYTYPE_NETWORK . ',' . Shop::BUYTYPE_PRINTER . ')');
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition);
		$this->pageTitle = '网络订单';
		$this->render('list', array('data' => $result['data'], 'pages'=>$result['pages'], 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'online'));
	}
	
	/**
	 * 同楼订餐
	 */
	public function actionGroupon()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '同楼订餐'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->addCondition('t.groupon_id > 0');
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition);
		$this->pageTitle = '网络订单';
		$this->render('list', array('data' => $result['data'], 'pages'=>$result['pages'], 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'groupon'));
	}
	
	/**
     * 电话订单列表
     */
	public function actionTelphone()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的订单' => url('my/order/uncomplete'),
	        '电话订单'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->addCondition('t.buy_type=' . Shop::BUYTYPE_TELPHONE);
		$condition->order = 't.create_time desc';
		$result = $this->loadOrderList($condition);
		$this->pageTitle = '电话订单';
		$this->render('list', array('data' => $result['data'], 'pages'=>$result['pages'], 'orderIsNoRating'=>$result['orderIsNoRating'], 'id'=>'telphone'));
	}

	/**
     * 团购订单列表
     * @param integer $state 订单状态
     */
	public function actionTuan($state = 0)
	{
	    $state = (int)$state;
		$this->render('tuan');
	}

	/**
	 * 取消一个订单
	 * @param integer $orderid 订单ID
	 */
	public function actionCancel()
	{
	    $orderid = (int)$_POST['order_id'];
	    $content = strip_tags($_POST['content']);
	    $order = Order::model()->findByPk($orderid);
		if($order->user_id != user()->id) {
			echo '非法操作';
			exit;
		}

		if($order->status==Order::STATUS_UNDISPOSED) {
			$order->cancel_reason = $content;
			if(!$order->save()) {
				echo '取消申请失败！';
			}
			$orderlog = new OrderLog();
			$orderlog->order_id = $orderid;
			$orderlog->type_id = Order::STATUS_CANCEL;
			if($orderlog->save()) {
				// 如果当前订单是同楼订餐的话
				if($order->groupon_id > 0) {
					Groupon::loseOrder($order);
				}
				echo 1;
			}
		} else {
			$order->cancel_reason = $content;
			$order->cancel_state = STATE_ENABLED;
			if(!$order->save()) {
				echo '取消申请失败！';
			}
			echo 2;
		}
	}

	/**
	 * 口味评分
	 */
	public function actionPostRate()
	{
		$ordergoodsid = intval($_POST['ordergoodsid']);
		$mark = intval($_POST['value']);
		$ordergoods = OrderGoods::model()->findByPk($ordergoodsid);
		if(!$ordergoods) {
			exit;
		}

		$goodsratelog = new GoodsRateLog();
		$goodsratelog->goods_id = $ordergoods->goods_id;
		$goodsratelog->shop_id = $ordergoods->order->shop_id;
		$goodsratelog->mark = $mark;
		$goodsratelog->ordergoods_id = $ordergoodsid;
		if(!$goodsratelog->save()){
			exit;
		} else {
			echo $goodsratelog->id;
			// 增加用户积分
			UserIntegralLog::addUserIntegralLog(UserIntegralLog::SOURCE_GOODSEVALUATE, intval($ordergoods->goods_amount));
		}
		exit;
	}

	/**
	 * 口味点评内容
	 */
	public function actionPostRateContent()
	{
		$id = intval($_POST['id']);
		$goodsratelog = GoodsRateLog::model()->findByPk($id);
		
		if($goodsratelog->user_id != user()->id) {
			echo '非法操作';
			exit;
		}
		
		$goodsratelog->content = trim(strip_tags($_POST['content']));
		if(!$goodsratelog->save()) {
			echo CHtml::errorSummary($goodsratelog);
		}
		exit;
	}

	/**
	 * 商家服务点评内容
	 */
	public function actionPostService()
	{
		$order_id = intval($_POST['order_id']);
		$evaluate = intval($_POST['evaluate']);
		$order = Order::model()->findByPk($order_id);
		
		if($order->user_id != user()->id) {
			echo '非法操作';
			exit;
		}
		
		if(!$order) {
			echo '不存在此订单';
			exit;
		}
		
		$shopcreditlog = new ShopCreditLog();
		$shopcreditlog->order_id = $order_id;
		$shopcreditlog->shop_id = $order->shop_id;
		$shopcreditlog->evaluate = $evaluate;

		if(!$shopcreditlog->save()) {
			echo CHtml::errorSummary($goodsratelog);
			exit;
		} else {
			// 增加用户积分
			UserIntegralLog::addUserIntegralLog(UserIntegralLog::SOURCE_SERVEEVALUATE, intval($order->amount));
		}
		exit;
	}

	public function filters()
	{
	    return array(
	        'ajaxOnly + postRateContent, postRate, postService',
	    	'postOnly + postRateContent, postRate, postService',
	    );
	}
}