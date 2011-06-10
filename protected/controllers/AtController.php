<?php

class AtController extends Controller
{
    public function actionSetLocation($atid, $referer)
    {
        $atid = (int)$atid;
        $referer = strip_tags(trim($referer));
        if (!$atid)
            $this->redirect(app()->homeUrl);
        /*
	     * 设置搜索记录
	     */
	    Location::addSearchHistory($atid);
	    if ($referer)
	        $this->redirect($referer);
	    else
	        $this->redirect(aurl('shop/list', array('atid'=>$atid)));
    }
    
    /**
     * 按照区域显示的所有位置列表
     * @param integer $dtid 行政区域ID号，默认为0，表示显示整个城市的位置
     */
	public function actionList($dtid = 0)
	{
	    $this->render('list');
	}

	/**
	 * 用户推荐位置
	 */
	public function actionCreate()
	{
		$this->render('create');
	}

	/**
	 * 位置列表，用户搜索后位置列表
	 * @param string $kw 搜索关键字
	 * @param string $map 是否用地图方式查看
	 */
	public function actionSearch($kw = null, $map = null)
	{
	    $kw = strip_tags(str_replace(array(' ', '.', '。'),'',$kw));
	    // 记录关键字
	    $searchlog = new SearchLog();
	    $searchlog->keywords = $kw;
	    $searchlog->city_id = $this->city['id'];
	    $searchlog->referer = app()->request->urlReferrer;
	    $searchlog->save();
	    
	    $criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id'], 'state'=>STATE_ENABLED, 'name'=>$kw));
		$location = Location::model()->find($criteria);
		if($location) {
			$location->afterSearch();
			$this->redirect($location->foodShopListUrl);
			exit;
		}
		
	    // 查询地址
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id'], 'state'=>STATE_ENABLED));
		$criteria->addSearchCondition('name', $kw);
		$criteria->limit = 30;
		$criteria->order = 'use_nums desc';
		$data = Location::model()->findAll($criteria);
	    $this->layout = 'search';
	    $this->taskTitle = '您要搜索的地址：' . $kw;
	    
	    $this->pageTitle = $this->city['name'] . $kw . '相关地址和写字楼';
	    $this->setPageKeyWords($kw);
	    $this->setPageDescription($kw . '相关地址');
	    
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
	    		);
	    	}
	    }
	    
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
		$viewFile = $newdata ? 'search' : 'search_no_result';
	    $this->render($viewFile, array(
		    'newdata' => $newdata,
	        'kw' => $kw,
		));
	}
	
	public function actionSearchNoResult($kw=null)
	{
		$this->layout = 'search';
	    $this->taskTitle = '您要搜索的地址：' . $kw;
	    
	    $this->pageTitle = $this->city['name'] . $kw . '相关地址和写字楼';
	    $this->setPageKeyWords($kw);
	    $this->setPageDescription($kw . '相关地址');
	    
		$kw = strip_tags(str_replace(array(' ', '.', '。'),'',$kw));
		$this->render('search_no_result', array(
			'data' => array(),
	        'kw' => $kw,
		));
	}
	
	/**
	 * 增加地址使用次数
	 */
	public function actionAddusenums($id)
	{
		Location::addUseNums($id);
	}
	
	/**
	 * 用户曾经打开过的地址列表
	 */
	public function actionSwitch()
	{
		$this->layout = 'search';
		$this->pageTitle = $this->taskTitle = '您的历史搜索地点';
		$location = Location::getSearchHistoryData();
		$this->render('switch', array('location'=>$location));
		$this->setPageKeyWords();
	    $this->setPageDescription();
	}
	
	/**
	 * ajax请求行政区域列表
	 */
	public function actionDistrict()
	{
		$city_id = intval($_GET['city_id']);
		if(!$city_id) {
			$city_id = $this->city['id'];
		}
		$criteria = new CDbCriteria();
		$criteria->addCondition('city_id = ' . $city_id);
		$data = District::model()->findAll($criteria);
		$array = array();
		foreach($data as $district) {
			$array[] = array(
				'name' => $district->name,
				'id' => $district->id
			);
		}
		echo CJSON::encode($array);
		exit;
	}
	
	/**
	 * ajax请求Building处理
	 */
	public function actionBuilding()
	{
		$district_id = intval($_GET['district_id']);
		$letter = strtoupper($_GET['letter']);
		$key = $_GET['key'];
		$city_id = intval($_GET['city_id']);
		if(!$city_id) {
			$city_id = $this->city['id'];
		}
		// 生成查询条件
		$criteria = new CDbCriteria();
		if(!$district_id) {
			$c = new CDbCriteria();
			$c->addCondition('city_id = ' . $city_id);
			$data = District::model()->findAll($c);
			$array = array();
			foreach($data as $value) {
				$array[] = $value->id;
			}
			$criteria->addInCondition('district_id', $array);
		} else {
			$criteria->addCondition('district_id =' . $district_id);
		}
		if($letter) {
			$criteria->addCondition('letter = "' . $letter . '"');
		}
		if($key) {
			$criteria->addSearchCondition('name', $key);
		}
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED, 'type'=>Location::TYPE_OFFICE));
		$criteria->limit = 21;
		
		// 分页处理
		$pages = new CPagination(Location::model()->count($criteria));
		$pages->pageSize = 21;
		$pages->applyLimit($criteria);
			
		$criteria->order = 'use_nums desc';
		
		$data = Location::model()->findAll($criteria);
		echo $this->renderPartial('building', array('data' => $data, 'pages'=>$pages), false);
	}
	
	public function actionSuggest($kw)
	{
		$kw = strip_tags(trim($kw));
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id'], 'state'=>STATE_ENABLED));

		if(preg_match("/[a-z]+/i", $kw)) {
			$criteria->addCondition('pinyin like "' . strtolower($kw) . '%"');
		} else {
			$criteria->addSearchCondition('name', $kw);
		}
		$criteria->select = 'name';
		$criteria->limit = 20;
		$criteria->order = 'use_nums desc';
		$result = Location::model()->findAll($criteria);
		$array = array();
		foreach($result as $value) {
			$array[] = $value->name;
		}
		echo 'window.get52WmKeyWords(' . CJSON::encode($array) . ');';
		exit;
	}
	
	/**
	 * 通过地图的显示的地址进入商铺列表
	 */
	public function actionPostSearchLocation()
	{
		$address = strip_tags(trim($_POST['address']));
		$name = strip_tags(trim($_POST['name']));
		$map_x = floatval($_POST['map_x']);
		$map_y = floatval($_POST['map_y']);
		$city_id = $this->city['id'];
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$city_id, 'name'=>$name));
		$location = Location::model()->find($criteria);
		if($location->id) {
			echo aurl('shop/list', array('atid'=>$location->id));
		} else {
			$location = new Location();
			$location->city_id = $city_id;
			$location->map_x = $map_x;
			$location->map_y = $map_y;
			$location->name = $name;
			$location->address = $address;
			$location->source = Location::SOURCE_SEARCH;
			$location->save();
			echo aurl('shop/list', array('atid'=>$location->id));
		}
	}
	
	public function filters()
	{
	    return array(
	        'ajaxOnly + district, building, addusenums, postSearchLocation'
	    );
	}
}