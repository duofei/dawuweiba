<?php

class LocationController extends Controller
{
	/**
	 * 待审核地址
	 */
	public function actionUnverify()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state' => STATE_DISABLED));
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id desc';
		$location = Location::model()->with('city')->findAll($criteria);
		$this->render('unverify', array(
			'location' => $location,
			'pages' => $pages
		));
	}
	
	/**
	 * 地址搜索
	 */
	public function actionSearch()
	{
		$k = $_GET['k'];
		$city = intval($_GET['city']);
		$criteria = new CDbCriteria();
		if($city)
			$criteria->addColumnCondition(array('t.city_id'=>$city));
		if($k)
			$criteria->addSearchCondition('t.name', $k);
		
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id asc';
		$location = Location::model()->with('city')->findAll($criteria);
		$this->render('search', array(
			'location' => $location,
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
	 * 删除地址
	 */
	public function actionDelete($id)
	{
		Location::model()->deleteByPk(intval($id));
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	/**
	 * 地址统计
	 */
	public function actionStatistics()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'city_id, state';
		$locations = Location::model()->findAll($criteria);
		$data = array();
		foreach($locations as $location) {
			$data[$location->city_id]['count']++;
			if($location->state == STATE_ENABLED)
				$data[$location->city_id]['enable']++;
			else
				$data[$location->city_id]['disable']++;
		}
		$citys = City::getCityArray();
		
		$this->render('statistics', array(
			'data' => $data,
			'citys' => $citys,
		));
	}
}