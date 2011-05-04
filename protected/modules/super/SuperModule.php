<?php

class SuperModule extends CWebModule
{
	public function init()
	{
		app()->errorHandler->errorAction = '/super/default/error';

		// import the module-level models and components
		$this->setImport(array(
			'super.models.*',
			'super.components.*',
		));
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
    		    $controller->redirect(url('super/default/login'));
    		}
    		
    		if (!$_SESSION['super_admin']) $controller->redirect(app()->homeUrl);
    		
			return true;
		} else
			return false;
	}
}
