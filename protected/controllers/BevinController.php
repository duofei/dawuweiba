<?php
//set_time_limit(3600);
class BevinController extends Controller
{

	public function actionTest()
	{
		echo mktime(0,0,0,05,09,2011);
		echo '<br>';
		echo mktime(0,0,0,05,10,2011);
		echo '<br>';
		echo mktime(0,0,0,05,11,2011);
		echo '<br>';
		echo mktime(0,0,0,05,12,2011);
	}
}