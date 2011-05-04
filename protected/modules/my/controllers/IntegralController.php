<?php

class IntegralController extends Controller
{
	public function actionBcintegral($id=0)
	{
		$this->pageTitle = '白吃点使用记录';
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '白吃点使用记录'
	    );
	    $criteria = new CDbCriteria();
	    $criteria->order = 't.id desc';
	    $criteria->addColumnCondition(array('user_id'=>user()->id));
	    $pages = new CPagination(UserBcintegralLog::model()->count($criteria));
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$bclog = UserBcintegralLog::model()->findAll($criteria);
		$this->render('bcintegral', array(
			'bclog' => $bclog,
			'pages' => $pages
		));
	}
	
	public function actionChange()
	{
		$this->pageTitle = '积分兑换白吃点';
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '积分兑换白吃点'
	    );
	    
	    $user = User::model()->findByPk(user()->id);
	    
	    $integral = 0;
	    if(app()->request->isPostRequest && $_POST['integral']) {
	    	$integral = intval($_POST['integral']);
	    	if($integral*1000 <= $user->integral) {
	    		UserIntegralLog::addUserIntegralLog(UserIntegralLog::SOURCE_BCINTEGRAL, $integral*1000*-1, user()->id);
	    		$user->integral = $user->integral - $integral*1000;
	    		$user->bcnums += $integral;
	    	} else {
	    		$error = '您的积分不够兑换, 最多只能兑换' . intval($user->integral/1000) . '点白吃点';
	    	}
	    }
	    
	    $this->render('change', array(
	    	'user' => $user,
	    	'integral' => $integral,
	    	'error' => $error
	    ));
	}
}