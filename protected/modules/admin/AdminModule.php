<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		app()->errorHandler->errorAction = '/admin/default/error';

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
		$this->setComponents(array('errorHandler' => array('errorAction'=>'/admin/default/error')));
	}

	public function beforeControllerAction($controller, $action)
	{
		$controllers = array();
	    $actions = array('captcha', 'login');
	    
	    if (in_array($controller->id, $controllers) || in_array($action->id, $actions)) return true;
	        
		if (parent::beforeControllerAction($controller, $action)) {
			/*
    	     * 如果管理员没有登陆则重定向到管理员登陆页面
    	     */
    		if (user()->isGuest) {
    		    $controller->redirect(url('admin/default/login'));
    		}
    		$city = City::model()->findByPk($_SESSION['manage_city_id']);
    		if (null === $city) {
                user()->logout();
                $controller->redirect(url('admin/default/login'));
    		}
    		
			return true;
		} else
			return false;
	}
}
