<?php

class SettingController extends Controller
{
	public function actionList()
	{
		$cityId = $_SESSION['manage_city_id'];
		if(app()->request->isPostRequest && isset($_POST[Setting])) {
			foreach ($_POST['Setting'] as $k=>$v) {
				Setting::setValue($k, $v, $cityId);
			}
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$cityId));
		$setting = Setting::model()->findAll($criteria);
		$array = array();
		foreach ($setting as $s) {
			$array[$s->parames] = $s->values;
		}
		$this->render('list', array(
			'setting' => $array
		));
	}
}