<?php

class CityController extends Controller
{
    /**
     * 城市列表页面
     */
	public function actionList()
	{
		$this->render('list');
	}

}