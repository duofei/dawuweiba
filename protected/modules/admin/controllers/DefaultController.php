<?php

class DefaultController extends Controller
{
    private $_menus;
    
    public function init()
    {
        parent::init();
        $this->_menus = require(app()->basePath . '/config/admin_menu.php');
    }
	public function actionIndex()
	{
	    $this->pageTitle = '管理平台首页';
		$this->renderPartial('index');
	}
	
	public function actionTop()
	{
	    $this->render('top', array('menus'=>$this->_menus));
	}
	
	public function actionLeft()
	{
	    $sub = trim($_GET['sub']);
	    $menu = $this->_menus[$sub];
	    $this->render('left', array('menu'=>$menu));
	}
	
	public function actionStart()
	{
	    $cityId = $_SESSION['manage_city_id'];
	    $this->render('start', array('cityId' => $cityId));
	}
	
	
	public function actionLogin()
	{
	    $loginModel = new LoginForm('login');
	    if (app()->request->isPostRequest && isset($_POST['LoginForm'])) {
	        $loginModel->attributes = $_POST['LoginForm'];
    		if ($loginModel->validate()) {
    		    $loginModel->login();
    		    LoginForm::clearErrorLoginNums();
    		    $this->redirect(url('admin/default/index'));
    	   	} else {
    	   	    // 此处有意设置登陆不成功直接跳转到登陆页，而不是提示错误信息，防止恶意用户获取相关登陆信息
    	   	    $this->redirect(url('admin/default/login'));
    	   	}
	    }
	    //echo CHtml::errorSummary($loginModel);
	    if (!user()->isGuest)
	        $this->redirect(url('admin/default/index'));

	    $this->pageTitle = '登录';
	    $this->render('login', array(
	        'loginModel' => $loginModel,
	    ));
	}
	
	public function actionLogout()
	{
	    user()->logout();
	    $this->redirect(url('admin/default/login'));
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
	
	public function filters()
	{
	    return array(
	        array(
                'COutputCache + start',
                'duration' => 300,
            ),
	    );
	}
	
}