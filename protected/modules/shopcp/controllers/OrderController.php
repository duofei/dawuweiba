<?php

class OrderController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Order::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}

	/**
     * 定单列表
     */
	public function actionList()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->order = 'id desc';

	    $pages = $this->_getPages($condition);
	    $order = Order::model()->findAll($condition);

	    $this->pageTitle = '全部订单';
	    $this->render('list', array('order' => $order, 'pages' => $pages));
	}

	/**
	 * 未加工订单
	 */
	public function actionHandleno()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('status' => Order::STATUS_UNDISPOSED));
	    $condition->addColumnCondition(array('cancel_state' => STATE_DISABLED));
	    $condition->order = 't.id desc';

	    $order = Order::model()->with('user')->findAll($condition);

	    $this->pageTitle = '未加工订单';

	    $this->render('handleno', array('order' => $order));
	}

	public function actionHandlenoCount($username = '')
	{
	    $username = trim($username);
	    $user = User::model()->findByAttributes(array('username'=>$username));
	    if (null === $user) throw new CHttpException(500);

	    $condition = new CDbCriteria();
	    $condition->addCondition('shop.user_id=' . $user->id);
	    $condition->addColumnCondition(array('status' => Order::STATUS_UNDISPOSED));
	    $order = Order::model()->with('shop')->count($condition);
	    echo (int)$order;
	    exit(0);
	}

	public function actionHandleing()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addCondition('status=' . Order::STATUS_PROCESS);
	    $condition->addColumnCondition(array('cancel_state' => STATE_DISABLED));
	    $condition->order = 't.groupon_id desc ,id desc';
	    $order = Order::model()->findAll($condition);

	    $condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('state' => DeliveryMan::STATUS_NORMAL));
	    $deliveryMan = DeliveryMan::model()->findAll($condition);

	    $this->pageTitle = '加工中订单';
	    $this->render('handleing', array('order' => $order, 'deliveryMan' => $deliveryMan));
	}

	public function actionDispatching()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addCondition('status=' . Order::STATUS_DELIVERING);
	    $condition->addColumnCondition(array('cancel_state' => STATE_DISABLED));
	    $condition->order = 'id desc';
	    $order = Order::model()->findAll($condition);

	    $condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('state' => DeliveryMan::STATUS_NORMAL));
	    $deliveryMan = DeliveryMan::model()->findAll($condition);

	    $this->pageTitle = '配送中订单';
	    $this->render('dispatching', array('order' => $order, 'deliveryMan' => $deliveryMan));
	}

	public function actionFinish()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addCondition('status=' . Order::STATUS_COMPLETE);
	    $condition->addColumnCondition(array('cancel_state' => STATE_DISABLED));
	    $condition->order = 'id desc';
	    $pages = $this->_getPages($condition);
	    $order = Order::model()->findAll($condition);

	    $condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('state' => DeliveryMan::STATUS_NORMAL));
	    $deliveryMan = DeliveryMan::model()->findAll($condition);

	    $this->pageTitle = '已完成订单';
	    $this->render('finish', array('order' => $order, 'deliveryMan' => $deliveryMan, 'pages' => $pages));
	}

	public function actionCancel()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addCondition('status=' . Order::STATUS_CANCEL);
	    $condition->order = 'id desc';
	    $pages = $this->_getPages($condition);
	    $order = Order::model()->findAll($condition);

	    $this->pageTitle = '已取消订单';
	    $this->render('cancel', array('order' => $order, 'pages' => $pages));
	}

	public function actionCancelstate($id = 0)
	{
		$id = (int)$id;
		if ($id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$order = Order::model()->findByPk($id, $condition);
			if($order) {
				$orderlog = new OrderLog();
				$orderlog->order_id = $id;
				$orderlog->type_id = Order::STATUS_CANCEL;
				if (!$orderlog->save()) {
					user()->setFlash('errorSummary',CHtml::errorSummary($orderlog));
				}
			}
		}

		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array(
	    	'shop_id' => $_SESSION['shop']->id,
	    	'cancel_state' => STATE_ENABLED,
	    ));
		$condition->addCondition('status!=' . Order::STATUS_CANCEL);
	    $condition->order = 'id desc';
	    $pages = $this->_getPages($condition);
	    $order = Order::model()->findAll($condition);

	    $this->pageTitle = '申请取消订单';
	    $this->render('cancelstate', array('order' => $order, 'pages' => $pages));
	}

	/*
	 * 同意取消申请并差评
	 */
	public function actionCancelandmark($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$order = Order::model()->findByPk($id, $criteria);
		// 存在此订单， 非游客的订单， 状态不等于已取消订单和无效订单
		if($order && $order->user_id && $order->status!=Order::STATUS_CANCEL && $order->status!=Order::STATUS_INVAIN) {
			$orderlog = new OrderLog();
			$orderlog->order_id = $id;
			$orderlog->type_id = Order::STATUS_CANCEL;
			$orderlog->save();
			
			$userCreditLog = new UserCreditLog();
			$userCreditLog->order_id = $id;
			$userCreditLog->evaluate = STATE_DISABLED;
			$userCreditLog->user_id = $order->user_id;
			if (!$userCreditLog->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($userCreditLog));
			}
		}
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	public function actionInvain()
	{
		if (isset($_POST['id'])) {
			$id = (int)$_POST['id'];
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$content = strip_tags($_POST['content']);
			$order = Order::model()->findByPk($id, $condition);
			$order->status = Order::STATUS_INVAIN;
			$order->cancel_reason = $content;
			if (!$order->save()) {
				echo '取消申请失败！';
			}else{
				echo '1';
			}
		}

	}

	/**
	 * 设置定单状态，加工中/派送中/完成
	 * @param integer $orderid 定单号
	 */
	public function actionState($id = 0, $type = 0, $manid = 0, $actual_money = 0)
	{
		$id = (int)$id;
		$type = (int)$type;
		if ($id) {
			$orderlog = new OrderLog();
			$orderlog->order_id = $id;
			$orderlog->type_id = $type;
			if (!$orderlog->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($orderlog));
			}
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$order = Order::model()->findByPk($id, $condition);
			if ($manid && $type == Order::STATUS_DELIVERING) {
				$order->delivery_id = (int)$manid;
			}
			if ($actual_money && $type == Order::STATUS_COMPLETE) {
				$order->actual_money = (int)$actual_money;
			}
			if (!$order->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($order));
			}
		}
//		if (isset($_POST['delivery'])) {
//			$orderlog = new OrderLog();
//			$orderlog->order_id = $_POST['id'];
//			$orderlog->type_id = 2;
//			if ($orderlog->save()) {
//				user()->setFlash('errorSummary',CHtml::errorSummary($orderlog));
//			}
//
//			$order = Order::model()->findByPk($_POST['id']);
//			$order->delivery_id = $_POST['delivery'];
//			$order->save();
//		}
		if ($type == Order::STATUS_PROCESS) {
			$this->redirect(url("shopcp/order/handleno"));
		} else if ($type == Order::STATUS_DELIVERING) {
			$this->redirect(url("shopcp/order/handleing"));
		} else if ($type == Order::STATUS_COMPLETE) {
			$this->redirect(url("shopcp/order/dispatching"));
		}
	}

	/**
	 * 商家评价用户
	 */
	public function actionEvaluate($id = 0, $evaluate = 0, $user_id = 0)
	{
		$id = (int)$id;
		$evaluate = (int)$evaluate;
		$user_id = (int)$user_id;
		if(isset($id)) {
			$userCreditLog = new UserCreditLog();
			$userCreditLog->order_id = $id;
			$userCreditLog->evaluate = $evaluate;
			$userCreditLog->user_id = $user_id;
			if (!$userCreditLog->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($userCreditLog));
			}
		}
		$this->redirect(url('shopcp/order/finish'));
	}

	/**
	 * 显示一个定单详情
	 * @param integer $orderid 定单号
	 */
	public function actionShow($orderid)
	{
	    $orderid = (int)$orderid;
		$this->render('show');
	}

	/**
	 * 搜索订单
	 */
	public function actionSearch()
	{
		foreach ((array)$_GET['Order'] as $key=>$val){
			$order_get[$key] = strip_tags(trim($val));
		}
		if($order_get) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('t.shop_id' => $_SESSION['shop']->id));
		    if ($order_get['order_sn']) {
				$order_id = Order::getOrderId($order_get['order_sn']);
		    	$condition->addColumnCondition(array('id' => $order_id));
		    }
		    if ($order_get['create_time_start']) {
				$start_time = strtotime($order_get['create_time_start']);
		    	$condition->addCondition('create_time>=\'' . $start_time . '\'');
		    }
		    if ($order_get['create_time_end']) {
		    	$end_time = strtotime($order_get['create_time_end']);
				$end_time = strtotime('next Day', $end_time);
		    	$condition->addCondition('create_time<=\'' . $end_time . '\'');
		    }
		    if ($order_get['username']) {
		    	$condition->addSearchCondition('consignee', $order_get['username']);
		    }
		    $condition->order = 'id desc';
		    $pages = $this->_getPages($condition);
		    $order = Order::model()->findAll($condition);

		    $this->pageTitle = '全部订单';
		    $this->render('list', array('order' => $order,'order_get'=>$order_get, 'pages' => $pages));
		}
	}

	/**
	 * 打印订单
	 */
	public function actionPrint($id)
	{
		$criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
	    $order = Order::model()->with('orderGoods')->findByPk($id, $criteria);
	    $this->renderPartial('print', array(
	    	'order' => $order
	    ));
	}
}