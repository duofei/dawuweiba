<?php

class SettingController extends Controller
{
	public function actionList()
	{
		if(app()->request->isPostRequest && isset($_POST[Setting])) {
			foreach ($_POST['Setting'] as $k=>$v) {
				Setting::setValue($k, $v);
			}
		}
		$setting = Setting::model()->findAll();
		$array = array();
		foreach ($setting as $s) {
			$array[$s->parames] = $s->values;
		}
		$this->render('list', array(
			'setting' => $array
		));
	}
}