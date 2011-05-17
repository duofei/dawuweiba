<?php
//set_time_limit(3600);
class BevinController extends Controller
{

	public function actionTest()
	{
		echo time()+300;
		exit;
		$a = MiaoshaResult::getTodaySuccessUserTelphone();
		print_r($a);
		
		$a = MiaoshaResult::getSuccessUserTelphone();
		print_r($a);
		exit;
		echo time();
		echo mktime(0,0,0,05,13,2011);
		echo '<br>';
		echo mktime(0,0,0,05,14,2011);
		echo '<br>';
		echo mktime(0,0,0,05,15,2011);
		echo '<br>';
		echo mktime(0,0,0,05,16,2011);
	}
}