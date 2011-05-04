<?php

class FavoriteController extends Controller
{
   
    /**
     * 商品收藏
     */
	public function actionIndex()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的收藏' => url('my/favorite/index'),
	        '商品收藏'
	    );
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.user_id' => user()->id));
		$criteria->order = 't.id desc';
		$pages = new CPagination(UserGoodsFavorite::model()->count($criteria));
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$goodsfavorite = UserGoodsFavorite::model()->with('goods','goods.shop')->findAll($criteria);
		$this->pageTitle = '商品收藏';
		$this->render('goodslist', array('goodsfavorite' => $goodsfavorite, 'pages' => $pages));
	}
	
	/**
     * 商铺收藏
     */
	public function actionShoplist()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的收藏' => url('my/favorite/index'),
	        '商铺收藏'
	    );
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.user_id' => user()->id));
		$criteria->order = 't.id desc';
		$pages = new CPagination(UserShopFavorite::model()->count($criteria));
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$shopfavorite = UserShopFavorite::model()->findAll($criteria);
		$this->pageTitle = '商铺收藏';
		$this->render('shoplist', array('shopfavorite' => $shopfavorite, 'pages' => $pages));
	}

	/**
	 * 删除一条收藏记录
	 * @param integer $fid 要删除的收藏记录的ID号
	 * @param string $type 收藏分类，shop/goods
	 */
	public function actionDelete($fid, $type)
	{
		$fid = (int)$fid;
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('user_id' => user()->id));
		if($type=='goods') {
			$goodsfavorite = UserGoodsFavorite::model()->findByPk($fid, $condition);
			if ($goodsfavorite->delete()) {
				$goods = Goods::model()->findByPk($goodsfavorite->goods_id);
				$goods->favorite_nums = $goods->favorite_nums-1;
				if (!$goods->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($goods));
				}
			}else {
				user()->setFlash('errorSummary',CHtml::errorSummary($goods));
			}
		} elseif($type=='shop') {
			$shopfavorite = UserShopFavorite::model()->findByPk($fid, $condition);
			if ($shopfavorite->delete()) {
				$shop = Shop::model()->findByPk($shopfavorite->shop_id);
				$shop->favorite_nums = $shop->favorite_nums-1;
				if (!$shop->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($shop));
				}
			}else {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop));
			}
			$this->redirect('/my/favorite/shoplist');
		}
		$this->redirect('/my/favorite');
	}

}