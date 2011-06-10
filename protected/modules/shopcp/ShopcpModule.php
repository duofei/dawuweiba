<?php

class ShopcpModule extends CWebModule
{
	public function init()
	{
		app()->errorHandler->errorAction = '/shopcp/default/error';

		// import the module-level models and components
		$this->setImport(array(
			'shopcp.models.*',
			'shopcp.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
	    $controllers = array();
	    $actions = array('handlenoCount');

	    if (in_array($controller->id, $controllers) || in_array($action->id, $actions)) return true;

		if (parent::beforeControllerAction($controller, $action)) {
			/*
    	     * 如果管理员没有登陆则重定向到管理员登陆页面
    	     */
    		if (user()->isGuest) {
    		    user()->loginRequired();
    		    exit(0);
    		}
    		$controllers = array('shop');
	        $actions = array('list', 'create');
    		if ($_SESSION['super_shop'] && (in_array($controller->id, $controllers) || in_array($action->id, $actions))) return true;
    		
    		$shop = $_SESSION['shop'];
		    if (empty($shop) && $_SESSION['super_shop']==0) {
    		    $controller->redirect(app()->homeUrl);
    		    exit(0);
    		//} elseif ($shop->state != STATE_ENABLED) {
    		    //$controller->redirect(url('shop/checkin'));
    		    //exit(0);
    		}
    		return true;
		}
		else
			return false;
	}
}
