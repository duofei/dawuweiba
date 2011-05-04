<?php

class LocationController extends Controller
{
	/**
	 * 待审核地址
	 */
	public function actionUnverify()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state' => STATE_DISABLED, 'city_id'=>$_SESSION['manage_city_id']));
		$pages = $this->_getPages($criteria);
		$criteria->order = 'id desc';
		$location = Location::model()->findAll($criteria);
		$this->render('unverify', array(
			'location' => $location,
			'pages' => $pages
		));
	}
	
	/**
	 * 地址搜索
	 */
	public function actionSearch($k = '', $address='', $category='', $region='')
	{
		$k = strip_tags(trim($k));
		$address = strip_tags(trim($address));
		$category = strip_tags(trim($category));
		$region = strip_tags(trim($region));
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));

		if($k) $criteria->addSearchCondition('t.name', $k);
		if($address) $criteria->addSearchCondition('t.address', $address);
		if($category) $criteria->addSearchCondition('t.category', $category);
		if($region) {
			$region_array = array();
			$xArray = array();
			$yArray = array();
			$ex = explode('|', $region);
			foreach ($ex as $e) {
				if($e) {
					$lonlat = explode(',',$e);
					$region_array[] = $lonlat;
					$xArray[] = $lonlat[0];
					$yArray[] = $lonlat[1];
				}
			}
			$maxx = max($xArray);
			$minx = min($xArray);
			$maxy = max($yArray);
			$miny = min($yArray);
			$criteria->addBetweenCondition('map_x', $minx, $maxx);
			$criteria->addBetweenCondition('map_y', $miny, $maxy);
		}
		
		if(!$region)
			$pages = $this->_getPages($criteria);
		
		$criteria->order = 't.name asc';
		$location = Location::model()->findAll($criteria);
		
		if($region_array) {
			$locaArray = array();
			foreach ($location as $v) {
				if(CdcBetaTools::pointInPolygon($region_array, $v->map_x, $v->map_y)) {
					$locaArray[] = $v;
				}
			}
			$location = $locaArray;
		}
		
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
	 * 添加修改地址
	 */
	public function actionEdit($id=0)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		if($id) {
			$location = Location::model()->findByPk(intval($id), $criteria);
			$op = '更改';
		} else {
			$location = new Location();
			$op = '添加';
			$_POST['url'] = '';
		}
		if(app()->request->isPostRequest && isset($_POST['Location'])) {
			$location->attributes = $_POST['Location'];
			$location->city_id = $_SESSION['manage_city_id'];
			if($location->save()) {
				AdminLog::saveManageLog($op . '地址(' . $location->name . ')信息');
			}
			if(isset($_POST['url']) && $_POST['url']) {
				$this->redirect($_POST['url']);
			}
		}
		if($_GET['verify'] == 1) {
			$location->state = STATE_ENABLED;
		}
		$this->render('edit', array(
			'location'=>$location,
			'url' => app()->request->urlReferrer
		));
	}
	
	/**
	 * 删除地址
	 */
	public function actionDelete($id=0)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$id = intval($id);
		if($id) {
			$location = Location::model()->findByPk($id, $criteria);
			$location->delete();
			AdminLog::saveManageLog('删除了楼宇(' . $location->name . ')记录');
		} else {
			$ids = $_POST['postid'];
			$criteria->addInCondition('id', $ids);
			Location::model()->deleteAll($criteria);
			AdminLog::saveManageLog('批量删除了楼宇ID(' . implode(',', $ids) . ')记录');
		}
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	/**
	 * 地址统计
	 */
	public function actionStatistics()
	{
		$citys = City::getCityArray();
		$locations = array();
		foreach ($citys as $city_id=>$city) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('city_id'=>$city_id, 'state'=>STATE_DISABLED));
			$locations[$city_id]['disabled'] = Location::model()->count($criteria);
			
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('city_id'=>$city_id, 'state'=>STATE_ENABLED));
			$locations[$city_id]['enabled'] = Location::model()->count($criteria);
		}
		$this->render('statistics', array(
			'locations' => $locations,
			'citys' => $citys
		));
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