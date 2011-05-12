<?php
class Miaosha2Controller extends Controller
{
	public function init()
	{
		$this->layout = 'miaosha2';
	}
	
	public function actionIndex()
	{
		$miaoshas = Miaosha::model()->findAll();
		$this->pageTitle = "秒杀活动";
		$this->render('index');
	}
}