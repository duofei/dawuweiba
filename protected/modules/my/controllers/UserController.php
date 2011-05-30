<?php

class UserController extends Controller
{
	/**
	 * 邮件订阅
	 */
	public function actionEmailquit()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '邮件订阅' => url('my/user/emailquit')
	    );
	    
		$user = User::model()->findByPk(user()->id);
		if(app()->request->isPostRequest && $_POST) {
			$state = $_POST['state'] ? 1 : 0;
			$user->is_sendmail = $state;
			$user->save();
		}
		$this->pageTitle = '邮件订阅';
		$this->render('emailquit', array(
			'user' => $user,
		));
	}
	
}