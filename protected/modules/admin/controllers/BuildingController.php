<?php

class BuildingController extends Controller
{
	/**
	 * 待审核楼宇
	 */
	public function actionUnverify()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state' => STATE_DISABLED, 't.city_id'=>$_SESSION['manage_city_id'], 'type'=>Location::TYPE_OFFICE));
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id asc';
		$building = Location::model()->with('district')->findAll($criteria);
		$this->render('unverify', array(
			'building' => $building,
			'pages' => $pages
		));
	}
	
	/**
	 * 楼宇搜索
	 */
	public function actionSearch($k = '', $type = '', $district = '')
	{
		$type = (int)$type;
		$district = (int)$district;
		$k = strip_tags(trim($k));
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.city_id'=>$_SESSION['manage_city_id'], 'type'=>Location::TYPE_OFFICE));
		
		if($type) {
			$criteria->addColumnCondition(array('type'=>$type));
		}
		if($district) {
			$criteria->addColumnCondition(array('district_id'=>$district));
		}
		if($k) {
			$criteria->addSearchCondition('t.name', $k);
		}
		
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.name asc';
		$building = Location::model()->with('district')->findAll($criteria);
		$this->render('search', array(
			'building' => $building,
			'pages' => $pages
		));
	}
	
	/**
	 * 分页
	 */
	private function _getPages($criteria)
	{
		$pages = new CPagination(Location::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 添加修改楼宇
	 */
	public function actionEdit($id=0)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.city_id'=>$_SESSION['manage_city_id'], 'type'=>Location::TYPE_OFFICE));
		
		if($id) {
			$building = Location::model()->findByPk(intval($id), $criteria);
			$op = '更改';
		} else {
			$building = new Location();
			$op = '添加';
			$_POST['url'] = '';
			$building->type = Location::TYPE_OFFICE;
			$building->city_id = $_SESSION['manage_city_id'];
		}
		
		if(app()->request->isPostRequest && isset($_POST['Location'])) {
			$building->attributes = $_POST['Location'];
			if($building->save()) {
				AdminLog::saveManageLog($op . '楼宇(' . $building->name . ')信息');
			}
			if(isset($_POST['url']) && $_POST['url']) {
				$this->redirect($_POST['url']);
			} else {
				$url = url('admin/building/edit');
				$this->redirect($url);
			}
			exit;
		}
		if($_GET['verify'] == 1) {
			$building->state = STATE_ENABLED;
		}
		$this->render('edit', array(
			'building' => $building,
			'url' => app()->request->urlReferrer
		));
	}
	
	/**
	 * 删除楼宇
	 */
	public function actionDelete($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.city_id'=>$_SESSION['manage_city_id'], 'type'=>Location::TYPE_OFFICE));
		$building = Location::model()->findByPk($id, $criteria);
		AdminLog::saveManageLog('删除了楼宇(' . $building->name . ')记录');
		$building->delete();
		
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	/**
	 * 楼宇统计
	 */
	public function actionStatistics()
	{
		
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('unverify', 'search', 'edit', 'delete'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array('unverify', 'search', 'edit', 'delete'),
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