<?php

class DituController extends Controller
{
    public function actionLocation()
	{
		$get = $_GET;
		$city = City::model()->findByPk($_SESSION['manage_city_id']);

		$this->renderPartial('location', array(
			'get' => $get,
			'city' => $city
		));
	}
}