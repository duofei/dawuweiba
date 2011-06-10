<?php

class CityadminController extends Controller
{
	/**
	 * 增加或修改城市信息
	 * @param integer $id 城市id
	 */
	public function actionAddcity($id=null)
	{
		$id = intval($id);
		if($id) {
			$city = City::model()->findByPk($id);
		} else {
			$city = new City();
		}
		
		if(app()->request->isPostRequest && isset($_POST['City'])) {
			$city->attributes = $_POST['City'];
			if($city->save()) {
				//AdminLog::saveManageLog();
			}
		}
		
		$this->render('addcity', array(
			'city' => $city,
		));
	} 
	
	/**
	 * 城市列表 
	 */
	public function actionCitylist()
	{
		$city = City::model()->findAll();
		$this->render('citylist', array(
			'city' => $city
		));
	}
	
	/**
	 * 增加管理人员
	 */
	public function actionAddmanager()
	{
		$roles = array(
			'CityAdmin' => '分站管理员',
			'Editor' => '编辑',
			'CustomerService' => '客服'
		);
		
		if(app()->request->isPostRequest && isset($_POST['user_id'])) {
			$user_id = intval($_POST['user_id']);
			$user = User::model()->findByPk($user_id);
			if(!$user) {
				throw new CException('该用户不存在', 0);
			}
			if($user->super_admin == STATE_ENABLED) {
				throw new CException('该用户是超级管理员', 0);
			}
			$city_id = intval($_POST['city_id']);
			$role = $_POST['role'];
			if(!key_exists($role, $roles)) {
				throw new CException('该角色不存在', 0);
			}
			if(key(auth()->getRoles($user_id))) {
				auth()->revoke(key(auth()->getRoles($user_id)), $user_id);
			}
			$user->manage_city_id = $city_id;
			if($user->save()) {
				auth()->assign($role, $user_id);
				$this->redirect('/super/cityadmin/managerlist');
			}
		}
		
		
			
		$city = City::getCityArray();	
		$this->render('addmanager', array(
			'city' => $city,
			'roles' => $roles
		));
	}
	
	/**
	 * 分站管理人员列表
	 */
	public function actionManagerlist()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('manage_city_id > 0 and super_admin=0');
		$managers = User::model()->findAll($criteria);
		$roles = array();
		foreach ($managers as $m) {
			$roles[$m->id] = current(auth()->getRoles($m->id));
		}
		$citys = City::getCityArray();
		$this->render('managerlist', array(
			'managers' => $managers,
			'roles' => $roles,
			'citys' => $citys
		));
	}
	
	/**
	 * 删除分站管理人员 
	 */
	public function actionRemoveManager($id)
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
		$this->redirect('/super/cityadmin/managerlist');
	}
	
	/**
	 * 搜索用户
	 */
	public function actionSearchuser()
	{
		$name = trim($_POST['name']);
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('username', $name);
		$criteria->select = 'id, username';
		$user = User::model()->findAll($criteria);
		$this->renderPartial('searchuser', array(
			'user' => $user
		));
	}
	
}