<?php

class DistrictController extends Controller
{
	/**
	 * 行政区域列表
	 */
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$district = District::model()->findAll($criteria);
		$this->render('list', array(
			'district' => $district,
		));
	}

	/**
	 * 添加修改行政区域
	 */
	public function actionEdit($id=0)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		if($id) {
			$district = District::model()->findByPk(intval($id), $criteria);
			$op = '更改';
		} else {
			$district = new District();
			$op = '添加';
			$_POST['url'] = '';
		}
		$district->city_id = $_SESSION['manage_city_id'];
		if(app()->request->isPostRequest && isset($_POST['District'])) {
			$district->attributes = $_POST['District'];
			if($district->save()) {
				AdminLog::saveManageLog($op . '行政区域(' . $district->name . ')信息');
			}
			if(isset($_POST['url']) && $_POST['url']) {
				$this->redirect($_POST['url']);
			} else {
				$url = url('admin/district/edit');
				$this->redirect($url);
			}
			exit;
		}
		$this->render('edit', array(
			'district' => $district,
			'url' => app()->request->urlReferrer
		));
	}
	
	/**
	 * 删除行政区域
	 */
	public function actionDelete($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$district = District::model()->findByPk($id, $criteria);
		AdminLog::saveManageLog('删除了行政区域(' . $district->name . ')记录');
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