<?php

class BuildingController extends Controller
{
	/**
	 * 用户提交楼宇
	 */
	public function actionCreate()
	{
		/*
	     * 设置面包屑导航
	     */
		$this->breadcrumbs = array(
			'我要提交楼宇' => url('building/create')
		);
		
		$this->pageTitle = '我要提交楼宇';
		$this->setPageKeyWords();
        $this->setPageDescription();
        
		$building= new Location('userpost');
		
		if (app()->request->isPostRequest && isset($_POST['Location'])) {
			$post = CdcBetaTools::filterPostData(array('name','address','map_x','map_y','district_id','validateCode'), $_POST['Location']);
			$building->attributes = $post;
			$building->city_id = $this->city['id'];
			$building->type = Location::TYPE_OFFICE;
			if ($building->save()) {
				$success = '您提交的楼宇信息我们已收到，非常感谢！';
			}
			$errorSummary = CHtml::errorSummary($building);
		}
		
		$this->pageTitle = '我要补充楼宇信息';
		$this->render('create', array(
			'building' => $building,
			'success' => $success,
			'errorSummary' => $errorSummary
		));
	}
}