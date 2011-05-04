<?php

class UserController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(User::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 用户列表
	 */
    public function actionList()
    {
    	$condition = new CDbCriteria();
	   	
	    $condition->order = 'state desc, id desc';
		$pages = $this->_getPages($condition);
    	$user = User::model()->findAll($condition);
	    $this->render('list', array('user'=>$user, 'pages'=>$pages));
    }

    /**
     * 禁用户列表
     */
    public function actionDenyuser()
    {
    	$condition = new CDbCriteria();
	   	
	   	$condition->addColumnCondition(array('state' => STATE_DISABLED));
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$user = User::model()->findAll($condition);
	    $this->render('denyuser', array('user'=>$user, 'pages'=>$pages));
    }
    
    /**
     * 管理角色
     */
	public function actionGroup()
	{
	    $roles = auth()->roles;
	    $this->render('group', array('roles' => $roles));
	}
	
	/**
	 * 更改状态
	 */
	public function actionState($id = 0, $state = 0)
	{
	    $user_id = (int)$id;
		if ($user_id){
		    $user = User::model()->findByPk($user_id);
		    $user->state = (int)$state;
			if(!$user->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($user));
			}
			$this->redirect(url('super/user/list'));
		}
	}
	
	public function actionSearch()
	{
		foreach ((array)$_GET['User'] as $key=>$val){
			$user[$key] = strip_tags(trim($val));
		}
		if($user) {
			$start_time = strtotime($user['create_time_start']);
			$end_time = strtotime($user['create_time_end']);
			$end_time = strtotime('next Day', $end_time);
			$condition = new CDbCriteria();
	   		
		    if ($user['username'] != '') {
		    	$condition->addSearchCondition('username', $user['username']);
		    }
		    if ($user['create_time_start']) {
		    	$condition->addCondition('create_time>=' . $start_time);
		    }
		    if ($user['create_time_end']) {
		    	$condition->addCondition('create_time<=' . $end_time);
		    }
		    if ($user['state'] != '') {
		    	$condition->addColumnCondition(array('state' => $user['state']));
		    }
	    	$condition->order = 'state desc, id desc';
		    $pages = $this->_getPages($condition);
		    $users = User::model()->findAll($condition);
		    
		    $this->render('search', array('users' => $users, 'user'=>$user, 'pages' => $pages));
		}else{
		    $this->render('search', array());
		}
	}
	
	public function actionProfile($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST['User'])) {
		    $user_id = (int)$_POST['id'];
		    $user_info = User::model()->findByPk($user_id);
			$user_info->attributes = $_POST['User'];
			$user_info->password = md5($_POST['User']['clear_password']);
			if(!$user_info->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($user_info));
			}
			$this->redirect(url('super/user/list'));
		}else {
	    	$condition = new CDbCriteria();
		   	
	    	$district = District::model()->findAll($condition);
	    	$district = CHtml::listData($district, 'id', 'name');
	    	
		    $user_id = (int)$id;
		    $user_info = User::model()->findByPk($user_id);
		    $this->render('profile', array('user_info'=>$user_info, 'district'=>$district));
		}
	}
	
	public function actionInfo($id = 0)
	{
	    $user_id = (int)$id;
	    $user_info = User::model()->findByPk($user_id);
		$this->render('info', array('user_info'=>$user_info));
	}

	public function actionIntegral($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST['id'])) {
		    $user_id = (int)$_POST['id'];
		    $user_info = User::model()->findByPk($user_id);
		    if ($_POST['integral']<0 && $user_info->integral<abs($_POST['integral'])) {
				user()->setFlash('errorSummary','扣除的分数不能小于用户已有分数！');
		    	$this->render('integral', array('user_info'=>$user_info));exit;
		    }
		    
		    $integral = new UserIntegralLog();
		    $integral->user_id = $user_id;
		    $integral->integral = $_POST['integral'];
		    $integral->remark = $_POST['remark'];
		    $integral->source = '12';
		    
			if(!$integral->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($integral));
		    	$this->render('integral', array('user_info'=>$user_info));
			}
			$this->redirect(url('super/user/list'));
		}else {
		    $user_id = (int)$id;
		    $user_info = User::model()->findByPk($user_id);
		    $this->render('integral', array('user_info'=>$user_info));
		}
	}

	/**
	 * 管理人员 
	 */
	public function actionTeam()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('super_admin' => STATE_ENABLED));
		$managers = User::model()->findAll($criteria);
		$roles = array();
		foreach ($managers as $m) {
			$roles[$m->id] = current(auth()->getRoles($m->id));
		}
		$this->render('team', array(
			'managers' => $managers,
			'roles' => $roles
		));
	}
	
	/**
	 * 删除管理人员 
	 */
	public function actionRemoveManager($id = 0)
	{
	    $id = (int)$id;
		$user = User::model()->findByPk($id);
		if(!$user) {
			throw new CException('该用户不存在', 0);
		}
		$user->super_admin = STATE_DISABLED;
		if($user->save()) {
			//AdminLog::saveManageLog('删除管理人员(' . $user->username . ')');
			auth()->revoke(key(auth()->getRoles($id)), $id);
		}
		$this->redirect('/super/user/team');
	}
	
	/**
	 * 设置管理人员
	 */
	public function actionSetmanager($id = 0)
	{
	    $id = (int)$id;
		$user = User::model()->findByPk($id);
		if(!$user) {
			throw new CException('该用户不存在', 0);
		}
		$authItem = current(auth()->getRoles($id));
		$roles = array(
			'SuperAdmin' => '超级管理员',
			'SuperEditor' => '编辑'
		);
		
		if(app()->request->isPostRequest && isset($_POST['role'])) {
			if($authItem) {
				auth()->revoke(key(auth()->getRoles($id)), $id);
			} 
			$user->super_admin = STATE_ENABLED;
			if($user->save()) {
				//AdminLog::saveManageLog('设置(' . $user->username . ')成为(' . $roles[$_POST['role']] . ')');
				auth()->assign($_POST['role'], $id);
				$this->redirect('/super/user/team');
			}
		}

		$this->render('setmanager', array(
			'user' => $user,
			'authItem' => $authItem,
			'roles' => $roles
		));
	}
	
}