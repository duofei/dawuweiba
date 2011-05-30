<?php

class SiteController extends Controller
{
    /**
     * 用户注册
     */
	public function actionSignup($invite='')
	{
	    /*
	     * 如果已经登陆了则直接跳转到首页
	     */
	    if (!user()->isGuest) $this->redirect(app()->homeUrl);
	    
	    $loginModel = new LoginForm('insert');
	    $loginModel->service = 1;
	    if (app()->request->isPostRequest && isset($_POST['LoginForm'])) {
	        $loginModel->attributes = $_POST['LoginForm'];
	        /*
	         * 如果输入验证成功并且生成新用户成功，则模拟登陆并跳转到首页
	         */
    		if ($loginModel->validate() && $loginModel->createUser()) {
    		    $loginModel->login();
    		    if($loginModel->email) {
    		    	// 发送邮件
    		    	$subject = '欢迎加入我爱外卖网';
			        $body = $this->renderPartial('/public/register_email', array(
			            'username' => $loginModel->username,
			        ), true);
			        $result = SendMail::addMailQueue($subject, $body, $loginModel->email, param('priorityRegister'));
    		    }
    		    
    		    /* 如果是被推荐 增加积分 */
    		    $invite_id = intval($_POST['invite_id']);
    		    if($invite_id > 0) {
    		    	$user = User::model()->findByPk($invite_id);
    		    	if($user) {
    		    		$hcode = $_GET['hcode'];
    		    		$integral = UserInviter::getInviterIntegral($hcode);
    		    		$userinviter = new UserInviter();
    		    		$userinviter->user_id = $user->id;
    		    		$userinviter->invitee_id = user()->id;
    		    		$userinviter->integral = $integral;
    		    		$userinviter->save();
    		    	}
    		    }
    		    
    		    $referer = $_POST['referer'];
    		    if($referer) {
    		    	$this->redirect($referer);
    		    } else {
    		    	$this->redirect(app()->homeUrl);
    		    }
    	   	}
	    }
	    
	    $this->pageTitle = '注册我爱外卖网会员';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    
	    $data['loginModel'] = $loginModel;
	    
	    /**
	     * 如果有邀请码进行如下处理
	     */
	    if($invite) {
	    	$user = User::model()->find('md5(id) = ?', array($invite));
			if($user) {
				$data['invite_username'] = $user->username;
				$data['invite_id'] = $user->id;
			}
	    }
	    $this->render('signup', $data);
	}

	/**
	 * 整站首页
	 * @param integer $f 如果$f = 1，则强制显示地址整站搜索首页，如果不为1则跳转到cookie地址的商家列表页
	 */
	public function actionIndex2($f = STATE_DISABLED)
	{
	    $f = (int)$f;
	    $currentAtId = Location::getLastVisit();
	    //var_dump($currentAtId);exit;
	   
	    if (app()->request->isPostRequest && isset($_POST['OpenSuggest'])) {
	    	$opensuggest = new OpenSuggest();
	    	$post = CdcBetaTools::filterPostData(array('province','city','email'), $_POST['OpenSuggest']);
	    	$opensuggest->attributes = $post;
	    	if($opensuggest->save()) {
	    		$success = '感谢您为我爱外卖网提供建议';
	    		// 发送邮件
	    		$subject = '感谢您为我爱外卖网提供建议';
			 	$body = $this->renderPartial('/public/opensuggest_email', null, true);
			   	$result = SendMail::addMailQueue($subject, $body, $opensuggest->email);
	    	} else {
	    		$success = '感谢您为我爱外卖网提供建议';
	    	}
	    }
	    if ($f || empty($currentAtId)) {
	        $this->layout = 'classic';
	        $criteria = new CDbCriteria();
	        $criteria->addColumnCondition(array('state'=>STATE_ENABLED));
	        $shopCount = Shop::model()->count($criteria);
	        $order24Count = Order::getCountOf24Hours();
	        $categoryShopCount = Shop::getShopCount($this->city['id']);
	        $this->pageTitle = '';
	        $this->setPageKeyWords();
	        $this->setPageDescription();
		    $this->render('index', array(
		        'shopCount' => $shopCount,
		        'order24Count' => $order24Count,
		        'categoryShopCount' => $categoryShopCount,
		    	'success' => $success
		    ));
	    } else {
	        $param = is_array($currentAtId) ?
	            array('lat'=>$currentAtId[0], 'lon'=>$currentAtId[1]) :
	            array('atid'=>$currentAtId, 'cid'=>ShopCategory::CATEGORY_FOOD);
	        $this->redirect(url('shop/list', $param));
	    }
	}
	
	public function actionIndex($f = STATE_DISABLED)
	{
		$f = (int)$f;
	    $currentAtId = Location::getLastVisit();
		if ($f || empty($currentAtId)) {
	        $this->pageTitle = '';
	        $this->setPageKeyWords();
	        $this->setPageDescription();
	        $this->layout = 'newmain';
		    $this->render('newindex', array(
		        'categoryShopCount' => $categoryShopCount,
		    	'success' => $success
		    ));
	    } else {
	        $param = is_array($currentAtId) ?
	            array('lat'=>$currentAtId[0], 'lon'=>$currentAtId[1]) :
	            array('atid'=>$currentAtId, 'cid'=>ShopCategory::CATEGORY_FOOD);
	        $this->redirect(url('shop/list', $param));
	    }
	}

	/**
	 * 用户登陆
	 */
	public function actionLogin()
	{
	    /*
	     * 如果已经登陆了则直接跳转到首页
	     */
	    if (!user()->isGuest) $this->redirect(app()->homeUrl);
	    
	    /*
	     * 处理登陆成功的跳转地址，如果有来源则跳转回去，如果没有则跳到首页
	     */
	    $loginModel = new LoginForm('login');
	    if (app()->request->isPostRequest && isset($_POST['LoginForm'])) {
	        $loginModel->attributes = $_POST['LoginForm'];
    		if ($loginModel->validate()) {
    		    $loginModel->login();
    		    
    		    $returnUrl = CdcBetaTools::getReferrer();
    		    
    		    /* 如果有白吃点赠送 */
    		    $key = 'getBcnum_' . user()->id;
				$bc_cache = app()->fileCache->get($key);
				if($bc_cache) {
					$this->redirect(url('user/getbcnum', array('uid'=>user()->id, 'sid'=>$bc_cache, 'referer'=>urlencode($returnUrl))));
					exit;
				}
			
				/* 如果是商铺管理员 */
    		    if ($_SESSION['super_shop'] || $_SESSION['shop'])
    		        $this->redirect(url('shopcp/default/index'));
    		    
    		    /* 跳回上次页面 */
    		    $this->redirect($returnUrl);
    	   	}
	    }
	    
	    /*
	     * 生成新浪微博登陆url地址
	     */
	    $sinat = new SinaTApp(param('sinaApiKey'), param('sinaApiSecret'));
	    $sinat->setCallback(param('sinaApiCallback'));
	    $sinaUrl = $sinat->getConnectUrl();
	    
	    $this->pageTitle = '登录我爱外卖网';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    $this->render('login', array(
	        'loginModel' => $loginModel,
	        'sinaUlr' => $sinaUrl,
	        'referer' => $referer,
	    ));
	}
	
	public function actionAirLogin()
	{
	    if (app()->request->isPostRequest && isset($_POST['LoginForm'])) {
	        $loginModel = new LoginForm('airLogin');
	        $loginModel->attributes = $_POST['LoginForm'];
    		if ($loginModel->validate()) {
    		    if (app()->session['shop']) {
    		        //$loginModel->login();
    		        echo 1;
    		    }
    		    else
    		        echo '没有权限';
    	   	} else {
    	   	    echo $loginModel->getError('password');
    	   	    exit(0);
    	   	}
	    } else {
	        echo '非法请求';
	    }
	    exit(0);
	}
	
	/**
	 * 用户退出
	 */
	public function actionLogout()
	{
	    user()->clearStates();
		user()->logout();
		$this->redirect(app()->homeUrl);
	}
	
	/**
	 * 找回密码
	 */
	public function actionForgetPassword()
	{
		if(app()->request->isPostRequest && isset($_POST['email']) && isset($_POST['username'])) {
			$email = trim(strip_tags($_POST['email']));
			$username = trim(strip_tags($_POST['username']));

			$criteria = new CDbCriteria();
			$criteria->select = 'id, email, username';
		   	$criteria->addColumnCondition(array('t.email'=>$email));
		   	$criteria->addColumnCondition(array('t.username'=>$username));
		   	$user = User::model()->find($criteria);
		   	if ($user) {
		   		$validate = md5($user->id . $_SERVER['REQUEST_TIME']);
		   		app()->cache->set($validate, $user->id, param('getPasswordUrlExpire'));
		   		
			    $subject = '我爱外卖网密码找回邮件';
		        
		        $body = $this->renderPartial('/public/forget_passwd_email', array(
		            'username' => $username,
		            'setPasswdUrl' => aurl('site/setPassword', array('validate'=>$validate)),
		        ), true);
		        $result = SendMail::addMailQueue($subject, $body, $user->email, param('priorityForgetPasswd'));

		        if(!$result) {
		          	$error = '邮件发送失败，请重试';
				} else {
		          	$success = true;
		        }
		   	} else {
		   		$error = '您的用户或邮箱不正确，请重新填写！';
		   	}
		}
		$this->pageTitle = '找回密码';
		$this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('forgetpassword', array('error'=>$error, 'username'=>$username, 'email'=>$email, 'success'=>$success));
	}
	
	/**
	 * 重设密码
	 */
	public function actionSetPassword()
	{
		$validate = $_GET['validate'];
		$user_id = app()->cache->get($validate);
		if(app()->request->isPostRequest && isset($_POST['password'])) {
			$password = trim(strip_tags($_POST['password']));
			$repassword = trim(strip_tags($_POST['re-password']));
			if($password == $repassword) {
				$user = User::model()->findByPk($user_id);
				if($user) {
					$user->clear_password = $password;
					$user->password = md5($password);
					if($user->save()) {
						app()->cache->delete($validate);
						$success = true;
					} else {
						$error = '重设密码失败， 请重试！';
					}
				} else {
					$error = '您的重设密码链接已失效！';
				}
			} else {
				$error = '两次密码一致，请重新输入密码';
			}
		}
		if(!$user_id) {
			$error_fail = '您的重设密码链接已失效！';
		}
		
		$this->pageTitle = '设置新密码';
		$this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('setpassword', array('validate'=>$validate, 'error'=>$error, 'success'=>$success, 'error_fail'=>$error_fail));
	}
	/**
	 * 新浪微博用户登陆callback地址，主要用来创建用户和同步登陆
	 * 如果第一次登陆会在系统中添加一个用户，第二次登陆的时候会检测是否已经存在，如果存在就直接登陆
	 */
	public function actionSinaT()
	{
	    if (!user()->isGuest) $this->redirect(app()->homeUrl);

	    $sina = new SinaTApp(param('sinaApiKey'), param('sinaApiSecret'));
	    $sina->getAccessToken((int)$_GET['oauth_verifier']);
        $sina->getUserInfo();
        $user = $sina->create52WmId();
        $identity = new UserIdentity($user->username, $user->clear_password);
        
        if ($identity->authenticate()) {
            user()->login($identity);
            $this->redirect(app()->homeUrl);
        } else {
            user()->loginRequired();
        }
        exit(0);
	}
	
	public function actionRenren()
	{
	    if (!user()->isGuest) $this->redirect(app()->homeUrl);
	    $renren = new RenrenApp(param('renrenApiKey'), param('renrenApiSecert'));
	    $renren->getUserInfo();
	    $user = $renren->create52WmId();
        $identity = new UserIdentity($user->username, $user->clear_password);
        
        if ($identity->authenticate()) {
            user()->login($identity);
            $this->redirect(app()->homeUrl);
        } else {
            user()->loginRequired();
        }
        exit(0);
	}
	
	public function actionTest()
	{
	    $this->render('test');
	}
	
	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if (!$error) exit;
	    
	    if (app()->request->isAjaxRequest) {
	        echo $error['message'];
	        exit(0);
	    }
	    
	    $errno = $error['code'];
	    if ($errno == '404')
	        $this->render('/system/error404', array('error'=>$error));
	    else
	        $this->render('/system/error', array('error'=>$error));
	}
	
	/**
	 * 开业之前 - 首页显示提示层
	 */
	public function actionOverlaybox()
	{
		$this->renderPartial('overlaybox');
	}
	
	/**
	 * 开业之前 - 下订单页显示提示层
	 */
	public function actionCartpopbox()
	{
		$this->renderPartial('cartpopbox');
	}
	
	public function actionChecksignup($type)
	{
		if('username' == $type) {
			$username = strip_tags($_GET['username']);
			if(preg_match("/^\w+$/", $username)) {
				if(strlen($username)<3 || strlen($username)>15) {
					echo -2;
					exit;
				}
			} else {
				if(strlen($username)<4 || strlen($username)>15) {
					echo -2;
					exit;
				}
			}
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('username'=>$username));
			$nums = User::model()->count($criteria);
			if($nums >= 1) {
				echo -1;
				exit;
			}
		} elseif ('email' == $type) {
			$email = strip_tags($_GET['email']);
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('email'=>$email));
			$nums = User::model()->count($criteria);
			if($nums >= 1) {
				echo -1;
				exit;
			}
		}
	}
	/*public function filters()
	{
	    return array(
	        array(
	        	'COutputCache + index',
	            'duration' => 60*60,
	            'varyByParam' => array('f'),
	            'cacheID' => 'fileCache',
	        ),
	    );
	}*/
	
	public function actionTrack(){
		setcookie('track52wm', '1', time()+3600*24*360, '/', param('cookieDomain'));
		$this->redirect(app()->homeUrl);
	}
}