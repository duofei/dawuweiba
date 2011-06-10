<?php

class BuildingController extends Controller
{
	/**
	 * 待审核楼宇
	 */
	public function actionUnverify()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state' => STATE_DISABLED, 'type'=>Location::TYPE_OFFICE));
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id asc';
		$building = Location::model()->with('district', 'city')->findAll($criteria);
		$this->render('unverify', array(
			'building' => $building,
			'pages' => $pages
		));
	}
	
	/**
	 * 楼宇搜索
	 */
	public function actionSearch()
	{
		$type = intval($_GET['type']);
		$k = $_GET['k'];
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('type'=>Location::TYPE_OFFICE));
		if($type) {
			$criteria->addColumnCondition(array('type'=>$type));
		}
		if($k) {
			$criteria->addSearchCondition('t.name', $k);
		}
		
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.name asc';
		$building = Location::model()->with('district', 'district.city')->findAll($criteria);
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
	 * 删除楼宇
	 */
	public function actionDelete($id)
	{
		Location::model()->deleteByPk(intval($id));
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	/**
	 * 楼宇统计
	 */
	public function actionStatistics()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('type'=>Location::TYPE_OFFICE));
		$criteria->select = 'district_id, state';
		$buildings = Location::model()->findAll($criteria);
		$districtArray = array();
		foreach($buildings as $b) {
			$districtArray[$b->district_id]['count']++;
			if($b->state == STATE_ENABLED)
				$districtArray[$b->district_id]['enable']++;
			else
				$districtArray[$b->district_id]['disable']++;
		}
		$data = array();
		$citys = City::getCityArray();
		foreach ($citys as $cityId=>$cityName) {
			$district = District::getDistrictArray($cityId);
			foreach($district as $districtId=>$districtName) {
				$data[$cityId][$districtId] = $districtArray[$districtId];
			}
		}
		$this->render('statistics', array(
			'data' => $data,
			'citys' => $citys,
			'districts' => District::getDistrictArray()
		));
	}
}