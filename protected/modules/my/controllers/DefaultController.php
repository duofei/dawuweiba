<?php

class DefaultController extends Controller
{
    /**
     * 我的用户中心起始页，显示用户基本信息
     */
	public function actionIndex()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '账户预览'
	    );
		$user = User::model()->findByPk(user()->id);
		$this->pageTitle = '账户预览';
		$this->render('summary', array('user' => $user));
	}

	/**
	 * 编辑查看用户资料
	 */
	public function actionProfile()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '个人资料' => url('my/default/profile'),
	        '基本资料'
	    );
		// 截入pager.css
		$path = Yii::getPathOfAlias('system.web.widgets.pagers');
		$url = app()->assetManager->publish($path) . '/';
		cs()->registerCssFile($url . 'pager.css', 'screen');
		
		$user = User::model()->findByPk(user()->id);
		
		if(app()->request->isPostRequest && isset($_POST['User'])) {
			$post = CdcBetaTools::filterPostData(array('realname', 'city_id', 'district_id', 'office_building_id', 'office_map_x', 'office_map_y','home_building_id', 'home_map_x', 'home_map_y', 'gender', 'birthday', 'telphone', 'mobile', 'qq', 'msn'), $_POST['User']);
			$user->attributes = $post;
			if($user->save()) {
				$session = app()->session;
				$session['realname'] = $user->realname;
				user()->setState('screenName', $user->screenName);
			} else {
				user()->setFlash('errorSummary',CHtml::errorSummary($user));
			}
		}
		
		// 城市和行政区域下拉列表
		$city = City::model()->findAll();
		$cityarray = array();
		$districtarray = array();
		if($user->city_id) {
			$city_id = $user->city_id;
		} else {
			$city_id = $this->city['id'];
		}

		foreach ($city as $row) {
			$cityarray[$row->id] = $row->name;
			if($row->id == $city_id) {
				foreach($row->districts as $r) {
					$districtarray[$r->id] = $r->name;
				}
			}
		}
		$this->pageTitle = '基本资料';
		$this->render('profile', array('user' => $user, 'cityarray'=>$cityarray, 'districtarray'=>$districtarray));
	}

	/**
	 * 修改头像
	 */
	public function actionPortrait()
	{
		$user = User::model()->findByPk(user()->id);
		$oldfile = param('staticBasePath') . $user->portrait;
		
		$file = CUploadedFile::getInstanceByName('User[portrait]');
		
	    if ($file->hasError || !$file) {
	        $error = '您未选择图片或您选择的图片有误！';
	    } else {
			$filePath = CdcBetaTools::makeUploadPath('portrait');
	        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
	        $fileSavePath = $filePath['absolute'] . $filename;
	        
	        if ($file->saveAs($fileSavePath)) {
	        	$fileUrl = $filePath['relative'] . $filename;
		        $user->portrait = $fileUrl;
				$user->save();
				// 没有错误直接删除老的头像
				$error = CHtml::errorSummary($user);
				if(!$error) {
					$session = app()->session;
					$session['portraitLinkHtml'] = $user->portraitLinkHtml;
					if(file_exists($oldfile)) {
						@unlink($oldfile);
					}
					$script = '<script>top.location.href="' . url('my/default/profile') . '";</script>';
				}
	        } else {
	        	$error = '文件保存失败';
	        }
	    }
		$this->renderPartial('portrait',array('error'=>$error, 'script'=>$script));
	}
	
	/**
	 * 修改email地址
	 */
	public function actionEmail()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '个人资料' => url('my/default/profile'),
	        '修改邮箱'
	    );
	    $this->pageTitle = '修改邮箱';
		$this->render('email');
	}
	public function actionAjaxEmail()
	{
		$user = User::model()->findByPk(user()->id);
		if($user->password == md5($_POST['password'])) {
			$user->email = $_POST['email'];
			if(!$user->save()) {
				echo '邮箱修改失败，请重试！';
			}
		} else {
			echo '密码不正确！';
		}
		exit;
	}

	/**
	 * 修改用户密码
	 */
	public function actionPasswd()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '个人资料' => url('my/default/profile'),
	        '修改密码'
	    );
	    $this->pageTitle = '修改密码';
		$this->render('passwd');
	}
	public function actionAjaxPasswd()
	{
		$user = User::model()->findByPk(user()->id);
		if($user->password == md5($_POST['old'])) {
			$user->password = md5($_POST['new_passwd']);
			$user->clear_password = $_POST['new_passwd'];
			if(!$user->save()) {
				echo '修改密码失败，请重试！';
			}
		} else {
			echo '旧密码不正确！';
		}
		exit;
	}
	
	/**
	 * 获取邀请链接
	 */
	public function actionInviteurl()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '邀请好友'
	    );
	    $this->pageTitle = '邀请好友';
		
		$this->render('inviteurl', array(
			'invite' => md5(user()->id)
		));
	}
	
	/**
	 * 用户认证操作 
	 */
	public function actionApprove()
	{
		$user = User::model()->findByPk(user()->id);
		
		$errorcode = '';
		if(app()->request->isPostRequest && isset($_POST)) {
			$vcode = intval($_POST['vcode']);
			if($vcode && User::checkSmsVerifyCode(user()->id, $vcode)) {
				$user->approve_state = User::APPROVE_STATE_VERIFY;
				if(!$user->mobile) {
					$user->mobile = trim($_POST['phone']);
				}
				$user->save();
				$this->redirect(url('my/default/profile'));
			} else {
				$errorcode = '验证码错误';
			}
		}
		
		if(!$user->mobile || !SendSms::filter_mobile($user->mobile)) {
			$user->mobile = '';
		}
		
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '个人资料' => url('my/default/profile'),
	        '用户认证'
	    );
	    $this->pageTitle = '用户认证';
		$this->render('approve', array(
			'user' => $user,
			'errorcode' => $errorcode
		));
	}
	
	/**
	 * 用户手机验证码 
	 */
	public function actionAjaxverifycode()
	{
		$phone = trim($_POST['phone']);
		if(User::sendSmsVerifyCode(user()->id, $phone)) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function filters()
	{
	    return array(
	        'ajaxOnly + ajaxEmail, ajaxPasswd, ajaxverifycode',
	    	'postOnly + ajaxEmail, ajaxPasswd, portrait',
	    );
	}
}