<?php
class CaijiController extends Controller
{
	public function actionPost()
	{
		$caiji = new ShopCaiji();
		$_POST['transport_amount'] = floatval($_POST['transport_amount']);
		$_POST['dispatching_amount'] = floatval($_POST['dispatching_amount']);
		$caiji->attributes = $_POST;
		if($caiji->save()) {
			echo 'success';
		} else {
			echo 'error';
		}
	}
	
	public function actionCity()
	{
		$city = City::model()->findAll();
		foreach ($city as $v) {
			echo '<div id="'.$v->id.'" name="'.$v->name.'"></div>';
		}
	}
}