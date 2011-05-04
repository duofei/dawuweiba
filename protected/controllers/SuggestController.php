<?php

class SuggestController extends Controller
{
	public function actionShop()
	{
		/*
	     * 设置面包屑导航
	     */
		$this->breadcrumbs = array(
			'我要推荐商铺' => url('suggest/shop')
		);
		
		$this->pageTitle = '我要推荐商铺';
		$this->setPageKeyWords();
        $this->setPageDescription();
        
		$shopsuggest = new ShopSuggest();
		
		if (app()->request->isPostRequest && isset($_POST['ShopSuggest'])) {
			$post = CdcBetaTools::filterPostData(array('email', 'telphone', 'shop_address', 'shop_name', 'comment', 'validateCode'), $_POST['ShopSuggest']);
			$shopsuggest->attributes = $post;
			$shopsuggest->city_id = $this->city['id'];
			if($shopsuggest->save())
			{
				$success = true;
			}
		}
		
		$this->render('shopsuggest', array(
			'shopsuggest' => $shopsuggest,
			'success' => $success
		));
	}
}