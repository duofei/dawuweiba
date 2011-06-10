<?php

class GoodsController extends Controller
{
	public function actionChange()
	{
		if(app()->request->isPostRequest && $_POST) {
			$submit_nosell = $_POST['submit_nosell'];
			$submit_price = $_POST['submit_price'];
			$goodsids = $_POST['goodsid'];
			$wmprice = $_POST['wmprice'];
			if($submit_price) {
				foreach ((array)$wmprice as $k=>$v) {
					$g = Goods::model()->with('foodGoods', 'shop')->findByPk($k);
					if($g->shop->district->city_id == $_SESSION['manage_city_id']) {
						$g->foodGoods->wm_price = floatval($v);
						$g->foodGoods->save();
					}
				}
			} elseif($submit_nosell) {
				foreach ((array)$goodsids as $k=>$v) {
					$g = Goods::model()->with('shop')->findByPk($k);
					if($g->shop->district->city_id == $_SESSION['manage_city_id']) {
						$g->state = Goods::STATE_NOSELL;
						$g->save();
					}
				}
			}
		}
		$referer = CdcBetaTools::getReferrer();
		$this->redirect($referer);
	}
}