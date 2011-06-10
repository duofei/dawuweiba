<?php

class CaijiController extends Controller
{
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'id desc';
		$pages = new CPagination(ShopCaiji::model()->count($criteria));
		$pages->pageSize = '15';
		$pages->applyLimit($criteria);
		$shops = ShopCaiji::model()->findAll($criteria);
		$this->render('list', array(
			'shops' => $shops,
			'pages' => $pages
		));
	}
	
	public function actionDelete($id)
	{
		$id = intval($id);
		$shop = ShopCaiji::model()->findByPk($id);
		$url = CdcBetaTools::getReferrer();
		if($shop) {
			$shop->delete();
		}
		$this->redirect($url);
	}
}