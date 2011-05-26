<?php

class UserController extends Controller
{
	public function actionGetbcnum()
	{
		$uid = intval($_GET['uid']);
		$sid = $_GET['sid'];
		
		if(!$uid || !$sid) {
			$this->render('error', array('message'=>'对不起您的操作有误!'));
			exit;
		}

		$user = User::model()->findByPk($uid);
		if($user === null) {
			$this->render('error', array('message'=>'对不起您的操作有误!'));
			exit;
		}
		
		$key = 'getBcnum_' . $uid;
		$cacheSid = app()->fileCache->get($key);
		if(!$cacheSid) {
			$this->render('fail', array('message'=>'您的白吃点已领取或已过期!'));
			exit;
		}
		// 过期时间2011-07-31
		if(strtotime('2011-07-31') < time()) {
			$this->render('fail', array('message'=>'您的白吃点已领取或已过期!'));
			exit;
		}
		
		if($sid == $cacheSid) {
			// 模拟登陆
			$identity=new UserIdentity($user->username,$user->clear_password);
			$identity->authenticate();
			user()->login($identity);
			// 给用户增加白吃点
			$bcnum = new UserBcintegralLog();
			$bcnum->source = UserBcintegralLog::SOURCE_SYSGIVE;
			$bcnum->integral = 10;
			$bcnum->user_id = $uid;
			if($bcnum->save()) {
				app()->fileCache->set($key, '', 1);
				$this->render('success', array('message'=>'恭喜您成功领取10点白吃点!'));
			}
		} else {
			$this->render('fail', array('message'=>'您的白吃点已领取或已过期!'));
		}
	}
	
	public function actionQuitsubscription()
	{
		$uid = intval($_GET['uid']);
		$sid = $_GET['sid'];
		
		if(!$uid || !$sid) {
			$this->render('error', array('message'=>'对不起您的操作有误!'));
			exit;
		}

		$user = User::model()->findByPk($uid);
		if($user === null) {
			$this->render('error', array('message'=>'对不起您的操作有误!'));
			exit;
		}
		
		$key = 'quit_subscription_' . $uid;
		$cacheSid = app()->fileCache->get($key);
		if($sid == $cacheSid) {
			// 模拟登陆
			$identity=new UserIdentity($user->username,$user->clear_password);
			$identity->authenticate();
			user()->login($identity);
			app()->fileCache->set($key, '', 1);
			$this->redirect(url('my/user/emailquit'));
		}
		$this->render('error', array('message'=>'对不起您的操作有误!'));
	}
}