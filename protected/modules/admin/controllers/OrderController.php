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
     * 未加工订单
     * Enter description here ...
     */
	public function actionHandleno()
	{
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_UNDISPOSED);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('handleno', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 加工中
     * Enter description here ...
     */
	public function actionHandleing()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_PROCESS);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('handleing', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 配送中
     * Enter description here ...
     */
	public function actionDispatching()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_DELIVERING);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('dispatching', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 已完成
     * Enter description here ...
     */
	public function actionFinish()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_COMPLETE);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('finish', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 申请取消
     */
	public function actionCancelstate()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('cancel_state='.STATE_ENABLED);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('cancelstate', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 已取消
     * Enter description here ...
     */
	public function actionCancel()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_CANCEL);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('cancel', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 无效订单
     * Enter description here ...
     */
	public function actionInvalid()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('status='.Order::STATUS_INVAIN);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('invalid', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 今日订单
     * Enter description here ...
     */
	public function actionToday()
	{
		$date = date('Y-m-d');
		$start = strtotime($date);
		$end = strtotime('next Day', $start);
    	$condition = new CDbCriteria();
	   	$condition->addCondition('city_id = ' . $_SESSION['manage_city_id']);
	   	$condition->addCondition("create_time >= $start");
	   	$condition->addCondition("create_time < $end");
	    $condition->order = 't.id desc';
		$pages = $this->_getPages($condition);
    	$order = Order::model()->findAll($condition);
	    $this->render('today', array('order'=>$order, 'pages'=>$pages));
	}
	
    /**
     * 搜索
     * Enter description here ...
     */
	public function actionSearch()
	{
		foreach ((array)$_GET['Order'] as $key=>$val){
			$order_get[$key] = strip_tags(trim($val));
		}
//		$order_get['status'] = (int)$order_get['status'];
//		$order_get['category_id'] = (int)$order_get['category_id'];
		if($order_get) {
			$start_time = strtotime($order_get['create_time_start']);
			$end_time = strtotime($order_get['create_time_end']);
			$end_time = strtotime('next Day', $end_time);
			$order_id = Order::getOrderId($order_get['order_sn']);
			
			$condition = new CDbCriteria();
	   		$condition->addColumnCondition(array('t.city_id' => $_SESSION['manage_city_id']));
		    if ($order_get['order_sn']) {
		    	$condition->addColumnCondition(array('t.id' => $order_id));
		    }
		    if ($order_get['create_time_start']) {
		    	$condition->addCondition('t.create_time>=' . $start_time);
		    }
		    if ($order_get['create_time_end']) {
		    	$condition->addCondition('t.create_time<=' . $end_time);
		    }
		    if ($order_get['consignee']) {
		    	$condition->addSearchCondition('t.consignee', $order_get['consignee']);
		    }
		    if ($order_get['status'] != '') {
		    	$condition->addColumnCondition(array('t.status' => $order_get['status']));
		    }
		    if ($order_get['category_id'] != '') {
		    	$condition->addColumnCondition(array('shop.category_id' => $order_get['category_id']));
		    }
			if ($order_get['shop_name'] != '') {
		    	$condition->addSearchCondition('shop.shop_name', $order_get['shop_name']);
		    }
			if ($order_get['username'] != '') {
		    	$condition->addSearchCondition('user.username', $order_get['username']);
		    }
		    $condition->order = 't.id desc';
		    $condition->limit = 20;
		    $pages = new CPagination(Order::model()->with('shop','user')->count($condition));
			$pages->pageSize = 20;
			$pages->applyLimit($condition);
		    $order = Order::model()->with('shop','user')->findAll($condition);
		}
	    $this->render('search', array('order'=>$order, 'order_get'=>$order_get, 'pages'=>$pages));
	}
	
	/**
	 * 统计
	 */
	public function actionStatistics()
	{
	    $this->render('statistics');
	}
	
	/**
	 * 客服待审核订单列表
	 */
	public function actionApprove()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id' => $_SESSION['manage_city_id'], 'verify_state'=>STATE_DISABLED, 'buy_type'=>Shop::BUYTYPE_PRINTER, 'status'=>Order::STATUS_UNDISPOSED));
		$orderlist = Order::model()->findAll($criteria);
		$this->render('approve', array('orderlist'=>$orderlist));
	}
	
    /**
     * 客服需要处理的申请取消订单
     */
	public function actionCustomerCancelstate()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id'], 'cancel_state'=> STATE_ENABLED, 'buy_type'=>Shop::BUYTYPE_PRINTER));
	   	$condition->addCondition('status!=' . Order::STATUS_CANCEL);
	   	$condition->addCondition('status!=' . Order::STATUS_INVAIN);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$order = order::model()->findAll($condition);
	    $this->render('customercancelstate', array(
	    	'order'=>$order,
	    	'pages'=>$pages
	    ));
	}
	
	/**
	 * 订单状态改变操作
	 */
	public function actionStatusOprate($orderid, $status)
	{
		$order = Order::model()->findByPk($orderid);
		if($order) {
			if(key_exists($status, Order::$states)) {
				$orderlog = new OrderLog();
				$orderlog->order_id = $orderid;
				$orderlog->type_id = $status;
				if ($orderlog->save() && $status==Order::STATUS_COMPLETE) {
					if($order->buy_type==Shop::BUYTYPE_PRINTER) {
						UserInviter::inviteSuccess($order->user_id);
					}
				}
			}
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}
	
	/**
	 * 订单审核状态改变操作
	 */
	public function actionVerifyStateOprate($orderid, $state)
	{
		$order = Order::model()->findByPk($orderid);
		if($order) {
			$order->verify_state = $state ? 1 : 0;
			$order->save();
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}
	
	/**
	 * 电话处理订单
	 */
	public function actionPhoneorder()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array(
			't.city_id' => $_SESSION['manage_city_id'],
			't.buy_type'=>Shop::BUYTYPE_TELPHONE,
		));
		$criteria->addCondition('t.consignee != ""');
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.create_time desc';
		$orderlist = Order::model()->with('shop')->findAll($criteria);
		$this->render('phoneorder', array(
			'orderlist'=>$orderlist,
			'pages' => $pages
		));
	}
	public function actionPhoneorderdeal($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array(
			't.city_id' => $_SESSION['manage_city_id'],
			't.buy_type'=>Shop::BUYTYPE_TELPHONE,
		));
		$id = intval($id);
		$order = Order::model()->with('shop', 'orderGoods')->findByPk($id, $criteria);
		$this->render('phoneorderdeal', array(
			'order' => $order
		));
	}
	public function actionPhoneorderpost()
	{
		if(app()->request->isPostRequest && $_POST) {
			$order_id = intval($_POST['order_id']);
			$status = intval($_POST['status']);
			$sendsms = intval($_POST['sendsms']);
			$deliver_time = $_POST['deliver_time'];
			$cancel_reason = $_POST['cancel_reason'];
			$sms_content = strip_tags($_POST['sms_content']);
			$order = Order::model()->findByPk($order_id);
			// 订单存在，并且是当前管理员城市下。
			if($order && $order->shop->district->city_id==$_SESSION['manage_city_id'] && ($status==Order::STATUS_COMPLETE || $status==Order::STATUS_CANCEL)) {
				if($status == Order::STATUS_CANCEL) {
					$order->cancel_reason = $cancel_reason;
				} elseif($status == Order::STATUS_COMPLETE) {
					$order->deliver_time =$deliver_time;
				}
				$order->save();
				
				$orderLog = new OrderLog();
				$orderLog->order_id = $order_id;
				$orderLog->type_id = $status;
				if($orderLog->save()) {
					if($sendsms==STATE_ENABLED && SendSms::filter_mobile($order->telphone)) {
						$content = iconv('utf-8', 'gb2312', $sms_content);
						// 发送短信
						$d = SendSms::send_sms($order->telphone, $content);
					}
				}
			}
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}

}