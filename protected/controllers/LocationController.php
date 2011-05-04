<?php

class LocationController extends Controller
{
	/**
	 * 当前商家不支持你的送餐地址
	 */
	public function actionConflict()
	{
		$atid = Location::getLastVisit();
		if(is_array($atid)) {
			$locationName = l('位置：' . $atid[0] . ',' . $atid[1], url('shop/list', array('lat'=>$atid[0], 'lon'=>$atid[1])));
		} else {
	    	$location = Location::model()->findByPk($atid);
	    	$locationName = l($location->name,url('shop/list', array('atid'=>$atid)));
		}
		
		$location = Location::getSearchHistoryData();
		$nums = '10'; // 显示地址记录条数
		$i = 0;
		foreach ((array)$location as $row) {
			if($atid == $row->id) {
				$html .=  '<p class="pa-l30px ma-l20px lh20px">' . $row->name . '</p>';
			} else {
				$html .=  '<p class="pa-l30px ma-l20px lh20px">' . l($row->name, $row->shopListUrl) . '</p>';
			}
			$i++;
			if($i >= $nums) {
				break;
			}
		}
		
		$this->renderPartial('conflict', array('locationName'=>$locationName, 'html'=>$html));
	}

	/**
	 * 用户提交地址
	 */
	public function actionCreate()
	{
		/*
	     * 设置面包屑导航
	     */
		$this->breadcrumbs = array(
			'我要提交地址' => url('location/create')
		);
		
		$this->pageTitle = '我要提交地址';
		$this->setPageKeyWords();
        $this->setPageDescription();
        
		$location = new Location('userpost');
		
		if (app()->request->isPostRequest && isset($_POST['Location'])) {
			$post = CdcBetaTools::filterPostData(array('name','map_x','map_y','address','validateCode'), $_POST['Location']);
			$post['source'] = Location::SOURCE_USERPOST;
			$location->attributes = $post;
			$location->city_id = $this->city['id'];
			if ($location->save()) {
				$success = '您提交的地址信息我们已收到，非常感谢！';
			}
			$errorSummary = CHtml::errorSummary($location);
		}
		
		$this->render('create', array(
			'location' => $location,
			'success' => $success,
			'errorSummary' => $errorSummary
		));
	}
}