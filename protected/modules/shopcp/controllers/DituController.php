<?php

class DituController extends Controller
{

    public function actionSetShopLocation()
	{
		if(isset($_GET)) {
			$shop = Shop::model()->findByPk($_SESSION['shop']->id);
			$shop->map_x = $_GET['map_x'];
			$shop->map_y = $_GET['map_y'];
			if (!$shop->update()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop));
			}
		}
		//$this->redirect(url('shopcp/shop/profile'));
	}
	
	public function actionRegion()
	{
		$shop = Shop::model()->findByPk($_SESSION['shop']->id);
		if ($shop->map_region) {
			$map_region = explode("|", $shop->map_region);
			foreach ($map_region as $key=>$val) {
				$regions[$key] = explode(",", $val);
			}
		} else if ($shop->map_x){
			$regions = array(
				'0'=>array(
					$shop->map_x+'0.004',
					$shop->map_y-'0.0017',
				),
				'1'=>array(
					$shop->map_x-'0.004',
					$shop->map_y-'0.0017',
				),
				'2'=>array(
					$shop->map_x,
					$shop->map_y+'0.003',
				)
			);
		} else {
			$regions = '';
		}
		$this->layout = 'black';
		$city_id = $this->city['id'];
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$city_id));
		$mapregion = MapRegion::model()->findAll($criteria);
		
		$this->render('region', array('regions'=>$regions, 'mapregion'=>$mapregion));
	}

    public function actionRegionset()
	{
		if(isset($_GET)) {
			$shop = Shop::model()->findByPk($_SESSION['shop']->id);
			$map_region = substr($_GET['map_region'], 0, -1);
			$shop->map_region = $map_region;
			if (!$shop->update()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop));
			}
		}
		$this->redirect(url('shopcp/shop/profile'));
	}
}