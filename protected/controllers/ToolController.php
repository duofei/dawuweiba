<?php

class ToolController extends Controller
{
    /**
     * 团购导航
     */
	public function actionTuannav()
	{
		$this->render('tuannav');
	}

	/**
	 * 生活工具首页
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

}