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
	 * Enter description here ...
	 */
    public function actionList()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'state desc, id desc';
		$pages = $this->_getPages($condition);
    	$user = User::model()->findAll($condition);
	    $this->render('list', array('user'=>$user, 'pages'=>$pages));
    }

    /**
     * 被禁用用户
     * Enter description here ...
     */
    public function actionDenyuser()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addColumnCondition(array('state' => STATE_DISABLED));
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$user = User::model()->findAll($condition);
	    $this->render('denyuser', array('user'=>$user, 'pages'=>$pages));
    }
    
    /**
     * 管理组
     * Enter description here ...
     */
	public function actionGroup()
	{
	    $roles = auth()->roles;
	    $this->render('group', array('roles' => $roles));
	}
	
	/**
	 * 禁用启用操作
	 */
	public function actionState($id = 0, $state = 0)
	{
		exit;
	    $user_id = (int)$id;
		if ($user_id){
		    $user = User::model()->findByPk($user_id);
		    $user->state = (int)$state;
			if(!$user->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($user));
			} else {
				AdminLog::saveManageLog('更改用户(' . $user->username . ')状态');
			}
			$this->redirect(url('admin/user/list'));
		}
	}
	
    /**
     * 搜索
     */
	public function actionSearch()
	{
		foreach ((array)$_GET['User'] as $key=>$val){
			$user[$key] = strip_tags(trim($val));
		}
//		$user['state'] = (int)$user['state'];

		// 截入pager.css
		$path = Yii::getPathOfAlias('system.web.widgets.pagers');
		$url = app()->assetManager->publish($path) . '/';
		cs()->registerCssFile($url . 'pager.css', 'screen');
		
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
		    if ($user['email'] != '') {
		    	$condition->addColumnCondition(array('email' => $user['email']));
		    }
		    if ($user['telphone'] != '') {
		    	$condition->addColumnCondition(array('telphone' => $user['telphone']));
		    }
		    if ($user['mobile'] != '') {
		    	$condition->addColumnCondition(array('mobile' => $user['mobile']));
		    }
		    if ($user['office_building_id'] != '') {
		    	$condition->addColumnCondition(array('office_building_id' => $user['office_building_id']));
		    }
		    if ($user['home_building_id'] != '') {
		    	$condition->addColumnCondition(array('home_building_id' => $user['home_building_id']));
		    }
	    	$condition->order = 'state desc, id desc';
		    $pages = $this->_getPages($condition);
		    $users = User::model()->with('shops')->findAll($condition);
		    
		    $this->render('search', array('users' => $users, 'user'=>$user, 'pages' => $pages));
		}else{
		    $this->render('search', array());
		}
	}
	
    /**
     * 查看用户资料
     */
	public function actionInfo($id)
	{
	    $user_id = (int)$id;
	    $user_info = User::model()
	        ->with('yewuOpenNums', 'yewuSuspendNums', 'yewuCloseNums', 'shops', 'city', 'district')
	        ->findByPk($user_id);
		if(!$user_info) {
			throw new CException('该用户不存在', 0);
		}
		$this->render('info', array('user_info'=>$user_info));
	}

	/**
	 * 绑定商铺
	 */
	public function actionBindshop($id)
	{
		$message = '';
		if(app()->request->isPostRequest) {
			$user_id = $_POST['userid'];
			$shop_id = $_POST['shopid'];
			$message = '绑定失败';
			if($user_id && $shop_id) {
				$shop = Shop::model()->findByPk($shop_id);
				if($shop) {
					$shop->user_id = $user_id;
					if($shop->save()) {
						$message = '绑定成功';
					}
				}
			}
		}
		
		$id = intval($id);
		$user = User::model()->with('shops')->findByPk($id);
		if(!$user) {
			$this->redirect(url('admin/user/search'));
		}
		$this->render('bindshop', array(
			'user' => $user,
			'message' => $message
		));
	}

	/**
	 * 管理人员
	 */
	public function actionTeam()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('manage_city_id'=>$_SESSION['manage_city_id'], 'super_admin'=>STATE_DISABLED));
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
		$user = User::model()->findByPk($id);
		if(!$user) {
			throw new CException('该用户不存在', 0);
		}
		$user->manage_city_id = 0;
		if($user->save()) {
			AdminLog::saveManageLog('删除管理人员(' . $user->username . ')');
			auth()->revoke(key(auth()->getRoles($id)), $id);
		}
		$this->redirect('/admin/user/team');
	}
	
	/**
	 * 设置管理人员
	 */
	public function actionSetmanager($id = 0)
	{
		$user = User::model()->findByPk($id);
		if(!$user) {
			throw new CException('该用户不存在', 0);
		}
		$authItem = current(auth()->getRoles($id));
		$roles = array(
			'Editor' => '编辑',
			'CustomerService' => '客服'
		);
		
		if(app()->request->isPostRequest && isset($_POST['role'])) {
			if($authItem) {
				auth()->revoke(key(auth()->getRoles($id)), $id);
			}
			$user->manage_city_id = $_SESSION['manage_city_id'];
			if($user->save()) {
				AdminLog::saveManageLog('设置(' . $user->username . ')成为(' . $roles[$_POST['role']] . ')');
				auth()->assign($_POST['role'], $id);
				$this->redirect('/admin/user/team');
			}
		}

		$this->render('setmanager', array(
			'user' => $user,
			'authItem' => $authItem,
			'roles' => $roles
		));
	}
	
	/**
	 * 已成功下过订单的待认证用户列表
	 */
	public function actionApprove()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.approve_state'=>User::APPROVE_STATE_UNSETTLED));
		$user = User::model()->with(array(
			'orders'=>array(
				'select'=>'id, status, create_time',
				'condition'=>'status=' . Order::STATUS_COMPLETE . ' and buy_type=' . Shop::BUYTYPE_PRINTER,
				'order'=>'orders.id desc'
			)
		))->findAll($criteria);
		$this->render('approve', array(
			'user' => $user
		));
	}
	
	public function actionApproveStateOprate($userid, $state)
	{
		$user = User::model()->findByPk($userid);
		if($user && key_exists($state, User::$approve_states)) {
			$user->approve_state = $state;
			$user->save();
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}
	
	public function actionAddbcnums()
	{
		$user_id = intval($_GET['id']);
		if(app()->request->isPostRequest && isset($_POST)) {
			$bcnums = intval($_POST['bcnums']);
			if($bcnums > 0) {
				$model = new UserBcintegralLog();
				$model->user_id = $user_id;
				$model->source = UserBcintegralLog::SOURCE_ADMINADD;
				$model->integral = $bcnums;
				$model->save();
			}
		}
		$user = User::model()->findByPk($user_id);
		$this->render('addbcnums', array(
			'user' => $user
		));
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('denyuser','state'),
	            'roles' => array('Editor'),
	        ),
	        array('allow',
	            'actions' => array('team', 'removeManager', 'setManager'),
	            'roles' => array('CityAdmin'),
	        ),
	        array('deny',
	            'actions' => array('team', 'removeManager', 'setManager', 'denyuser', 'state'),
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