<?php

class DituController extends Controller
{
	/**
	 * 地图上标注我的位置
	 */
    public function actionLocation()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id']));
		$district = District::model()->findAll($criteria);
		$this->layout = 'black';
		$this->render(param('map') . '_location', array(
			'district' => $district
		));
	}
	
	/**
	 * 获取坐标
	 */
	public function actionGetLatLon()
	{
		$get['map_x'] = $_GET['map_x'];
		$get['map_y'] = $_GET['map_y'];
		$get['callback'] = trim($_GET['callback']);
		$get['city_id'] = intval($_GET['city_id']) ? intval($_GET['city_id']) : $this->city['id'];
		$city = City::model()->findByPk($get['city_id']);

		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id']));
		$district = District::model()->findAll($criteria);
		
		$this->layout = 'black';
		$this->render(param('map') . '_getlatlon', array(
			'get' => $get,
			'city' => $city,
			'district' => $district
		));
	}
	
	/**
	 * 获取楼宇
	 */
	public function actionGetBuilding()
	{
		$minx = floatval($_GET['minx']);
		$miny = floatval($_GET['miny']);
		$maxx = floatval($_GET['maxx']);
		$maxy = floatval($_GET['maxy']);
		$criteria = new CDbCriteria();
		$criteria->addBetweenCondition('map_x', $minx, $maxx);
		$criteria->addBetweenCondition('map_y', $miny, $maxy);
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED, 'type'=>Location::TYPE_OFFICE));
		$building = Location::model()->findAll($criteria);
		$array = array();
		foreach ($building as $b) {
			$array[] = array(
				'id' => $b->id,
				'name' => $b->name,
				'map_x' => $b->map_x,
				'map_y' => $b->map_y
			);
		}
		echo CJSON::encode($array);
	}

	/**
	 * 地图范围
	 */
	public function actionRegion()
	{
		
		$center['map_x'] = $_GET['map_x'];
		$center['map_y'] = $_GET['map_y'];
		$shopLocation = $center;
		if(!$center['map_x'] || !$center['map_y']) {
			$center['map_x'] = $this->city['map_x'];
			$center['map_y'] = $this->city['map_y'];
		}
		
		$regions = array(
			array(
				$center['map_x']+'0.01',
				$center['map_y']-'0.007',
			),
			array(
				$center['map_x']-'0.01',
				$center['map_y']-'0.007',
			),
			array(
				$center['map_x']-'0.01',
				$center['map_y']+'0.007',
			),
			array(
				$center['map_x']+'0.01',
				$center['map_y']+'0.007',
			)
		);
		
		$region = trim($_GET['region']);
		if($region) {
			$maxMin = array(
    			'max' => array('x'=>0, 'y'=>0),
    			'min' => array('x'=>1000, 'y'=>1000),
    		);
			$region_array = array();
			$ex = explode('|', $region);
			foreach ($ex as $e) {
				if($e) {
					$temp = explode(',',$e);
					$region_array[] = $temp;
					if($maxMin['max']['x'] < $temp[0]) {
	    				$maxMin['max']['x'] = $temp[0];
	    			}
	    			if($maxMin['max']['y'] < $temp[1]) {
	    				$maxMin['max']['y'] = $temp[1];
	    			}
	    			if($maxMin['min']['x'] > $temp[0]) {
	    				$maxMin['min']['x'] = $temp[0];
	    			}
	    			if($maxMin['min']['y'] > $temp[1]) {
	    				$maxMin['min']['y'] = $temp[1];
	    			}
				}
			}
			$regions = $region_array;
			$center['map_x'] = $maxMin['min']['x'] + ($maxMin['max']['x']-$maxMin['min']['x'])/2;
		    $center['map_y'] = $maxMin['min']['y'] + ($maxMin['max']['y']-$maxMin['min']['y'])/2;
		}
			
		$callback = trim($_GET['callback']) ? trim($_GET['callback']) : 'callback';
		
		$this->layout = 'black';
		$city_id = $this->city['id'];
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$city_id));
		$mapregion = MapRegion::model()->findAll($criteria);

		//$regions = CdcBetaTools::getOctagonCoordinate(1000, array('x'=>'116.987313', 'y'=>'36.672182'));
		
		$this->render(param('map') . '_region', array(
			'regions' => json_encode($regions),
			'callback' => $callback,
			'mapregion' => $mapregion,
		    'center' => $center,
		    'shopLocation' => $shopLocation,
			'maxMin' => json_encode($maxMin)
		));
	}

	/**
	 * 返回八边形的坐标
	 */
	public function actionOctagon()
	{
		$map_x = $_GET['x'];
		$map_y = $_GET['y'];
		$region = $_GET['region'];
		$croods = CdcBetaTools::getOctagonCoordinate($region, array('x'=>$map_x, 'y'=>$map_y));
		echo json_encode($croods);
	}
	
	public function actionSearch()
	{
		$other = $_GET['other'];
		$miaosha = false;
		if($other == 'miaosha') {
			$miaosha = true;
		}
		$cid = intval($_GET['cid']);
		if(!array_key_exists($cid, ShopCategory::$categorys)) {
			$cid = ShopCategory::CATEGORY_FOOD;
		}
		
		$city_id = $this->city['id'];
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$city_id));
		$district = District::model()->findAll($criteria);
		
		$kw = $_GET['kw'];
		$kw = strip_tags(str_replace(array(' ', '.', '。'),'',$kw));
		if($kw) {
		    // 记录关键字
		    $searchlog = new SearchLog();
		    $searchlog->keywords = $kw;
		    $searchlog->city_id = $city_id;
		    $searchlog->referer = app()->request->urlReferrer;
		    $searchlog->save();
		    
		    $criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('city_id'=>$city_id, 'state'=>STATE_ENABLED, 'name'=>$kw));
			$location = Location::model()->find($criteria);
			if($location) {
				$data[] = array(
	    			'map' => false,
	    			'name' => $location->name,
	    			'foodShopListUrl' => $location->foodShopListUrl,
	    			'id' => $location->id,
	    			'food_nums' => $location->food_nums,
	    			'cake_nums' => $location->cake_nums,
	    			'cakeShopListUrl' => $location->cakeShopListUrl,
	    			'address' => $location->address,
					'map_x' => $location->map_x,
					'map_y' => $location->map_y
			    );
			} else {
			    // 查询地址
				$criteria = new CDbCriteria();
				$criteria->addColumnCondition(array('city_id'=>$city_id, 'state'=>STATE_ENABLED));
				$criteria->addSearchCondition('name', $kw);
				$criteria->limit = 26;
				$criteria->order = 'use_nums desc';
				$data = Location::model()->findAll($criteria);

			    $newdata = array();
			    $namearray = array();
			    if($data) {
			    	foreach ($data as $v) {
			    		$namearray[] = $v->name;
			    		$newdata[] = array(
			    			'map' => false,
			    			'name' => $v->name,
			    			'foodShopListUrl' => $v->foodShopListUrl,
			    			'id' => $v->id,
			    			'food_nums' => $v->food_nums,
			    			'cake_nums' => $v->cake_nums,
			    			'cakeShopListUrl' => $v->cakeShopListUrl,
			    			'address' => $v->address,
			    			'map_x' => $v->map_x,
							'map_y' => $v->map_y
			    		);
			    	}
			    }
			    
			    if(count($newdata) < 26) {
				    /* 通过google map 取地址数据 */
				    $cityname = $this->city['name'];
				    $g_kw = $kw;
				    if(stripos($kw, $cityname) === false) {
				    	$g_kw = $cityname . $kw;
				    }
				    $google_geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' .urlencode($g_kw). '&sensor=false&region=cn&language=zh-CN');
				    $googledata = CJSON::decode($google_geocode);
					if($googledata['status'] == 'OK') {
						$result = $googledata['results'];
						foreach ($result as $r) {
							if($r['address_components'][0]['types'][0] == 'street_number') {
								$name = $r['address_components'][1]['long_name'] . $r['address_components'][0]['long_name'];
							} else {
								$name = $r['address_components'][0]['long_name'];
							}
							$name = str_replace(array('（', '）'), array('(', ')'), trim($name));
							if(!in_array($name, $namearray)) {
								$newdata[] = array(
									'map' => true,
									'name' => $name,
					    			'map_x' => $r['geometry']['location']['lng'],
					    			'map_y' => $r['geometry']['location']['lat'],
					    			'address' => $r['formatted_address']
								);
							}
						}
					}
					if(count($newdata) == 1) {
						if($newdata[0]['name'] == '济南' && $newdata[0]['map']) {
							$newdata = null;
						}
					}
			    }
				$data = $newdata;
			}
			
			if(!$data) {
				$error = '对不起，您搜索的地址不存在，请在地图上标注您的位置！';
			}
		}
		
		$this->layout = 'black';
		$this->render('search', array(
			'district' => $district,
			'kw' => $kw,
			'data' => $data,
			'error' => $error,
			'cid' => $cid,
			'miaosha' => $miaosha
		));
	}
}