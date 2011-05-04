<?php

class RegionController extends Controller
{
	/**
	 * 分页
	 */
	private function _getPages($criteria)
	{
		$pages = new CPagination(MapRegion::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 地图区域列表
	 */
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$criteria->order = 'id desc';
		$region = MapRegion::model()->findAll($criteria);
		$pages = $this->_getPages($criteria);
		
		$this->render('list', array(
			'region' => $region,
			'pages' => $pages
		));
	}

	/**
	 * 添加修改地图区域
	 */
	public function actionEdit($id=0)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		if($id) {
			$region = MapRegion::model()->findByPk(intval($id), $criteria);
			$op = '更改';
		} else {
			$region = new MapRegion();
			$op = '添加';
			$_POST['url'] = '';
		}
		if(app()->request->isPostRequest && isset($_POST['MapRegion'])) {
			$region->attributes = $_POST['MapRegion'];
			$region->city_id = $_SESSION['manage_city_id'];
			if($region->save()) {
				AdminLog::saveManageLog($op . '地图区域(' . $region->name . ')信息');
			}
			if(isset($_POST['url']) && $_POST['url']) {
				$this->redirect($_POST['url']);
			}
		}
		$this->render('edit', array(
			'region'=>$region,
			'url' => app()->request->urlReferrer
		));
	}
	
	/**
	 * 删除地图区域
	 */
	public function actionDelete($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$district = District::model()->findByPk($id, $criteria);
		AdminLog::saveManageLog('删除了地图区域(' . $district->name . ')记录');
		$district->delete();
		
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array( 'edit', 'delete'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array( 'edit', 'delete'),
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