<?php

class ManageController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(AdminLog::model()->with('user')->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 管理员管理记录  
	 */
    public function actionRecord()
    {
    	$criteria = new CDbCriteria();
	   	$criteria->addCondition('user.manage_city_id=' . $_SESSION['manage_city_id']);
	   	if(isset($_GET['user_id']) && $_GET['user_id']) {
	   		$criteria->addColumnCondition(array('t.user_id'=>intval($_GET['user_id'])));
	   	}
	   	if($_GET['begin_time'] && $_GET['end_time']) {
	   		$begin_time = strtotime($_GET['begin_time']);
	   		$end_time = strtotime($_GET['end_time']) + 86400;
	   		$criteria->addBetweenCondition('t.create_time', $begin_time, $end_time);
	   	}
	   	$criteria->order = 't.id desc';
		$pages = $this->_getPages($criteria);
    	$log = AdminLog::model()->with('user')->findAll($criteria);
    	
    	$criteria = new CDbCriteria();
	   	$criteria->addCondition('manage_city_id=' . $_SESSION['manage_city_id']);
    	$user = User::model()->findAll($criteria);
	    $this->render('record', array(
	    	'log' => $log, 
	    	'user' => $user, 
	    	'pages' => $pages
	    ));
    }

	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('record'),
	            'roles' => array('CityAdmin'),
	        ),
	        array('deny',
	            'actions' => array('record'),
	            'users' => array('*'),
	        ),
	    );
	}
	
	public function filters()
	{
	    return array(
	        'accessControl',
	    );
	}
}