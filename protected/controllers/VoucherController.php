<?php

class VoucherController extends Controller
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('t.end_time > ' . time());
		$criteria->order = 't.shop_id desc';
		
		$list = Voucher::model()->with('shop')->findAll($criteria);
		
	    $this->pageTitle = "优惠券";
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('index', array(
			'list'=>$list
		));
	}
	
	public function actionPrint()
	{
		$ids = explode(',', $_GET['id']);
		$c = new CDbCriteria();
		$c->addInCondition('id', $ids);
		$list = Voucher::model()->findAll($c);
		$this->layout = 'blank';
		$this->render('print', array(
			'list' => $list
		));
	}
}