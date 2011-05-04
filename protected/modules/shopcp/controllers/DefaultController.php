<?php

class DefaultController extends Controller
{
    /**
     * 商家管理中心首页
     */
	public function actionIndex()
	{
		if($_SESSION['super_shop']) {
			$this->redirect(url('shopcp/shop/list'));
			exit;
		}
		$shop = $_SESSION['shop'];
		if($shop->state == STATE_ENABLED) {
			if($shop->buy_type==Shop::BUYTYPE_PRINTER) {
				$this->redirect(url('shopcp/orderprinter/finish'));
				exit;
			}
			/**
			 * 直接跳转未加工的订单
			 */
			$this->redirect(url('shopcp/order/handleno'));
			exit;
		} else {
			$this->render('index');
		}
	}
	
	public function actionHandleNoOrderNum()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('status' => Order::STATUS_UNDISPOSED));
	    $condition->addColumnCondition(array('cancel_state' => STATE_DISABLED));
	    $condition->order = 'id desc';
	    $num = (int)Order::model()->count($condition);
		echo (int)$num;
		exit;
	}
	
	public function actionAnnouncement()
	{
	    $this->render('announcement');
	}
	
	public function filters()
	{
	    return array(
	        'ajaxOnly + handleNoOrderNum',
	    );
	}
	
	public function actionError()
	{
	    $this->pageTitle = '很抱歉您访问的页面出错了';
	    $error = app()->errorHandler->error;
	    if (!$error) exit;
	    
	    if (app()->request->isAjaxRequest) {
	        echo $error['message'];
	        exit(0);
	    }
	    
	    $errno = $error['code'];
	    if ($errno == '404')
	        $this->renderPartial('/system/error404', array('error'=>$error));
	    else
	        $this->renderPartial('/system/error500', array('error'=>$error));
	}
}