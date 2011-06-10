<?php

class OrderprinterController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Order::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}

	/**
	 * 已完成订单
	 */
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
	
	/**
	 * 申请取消订单操作
	 */
	public function actionCancelorder($id = 0)
	{
		$id = (int)$id;
		if ($id) {
			$criteria = new CDbCriteria();
		    $criteria->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$order = Order::model()->findByPk($id, $criteria);
			if($order) {
				$order->cancel_state = STATE_ENABLED;
				$order->save();
			}
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}
	
	/**
	 * 已申请取消的订单
	 */
	public function actionCancelstate($id = 0)
	{
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
	
	/**
     * 订单列表
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

}