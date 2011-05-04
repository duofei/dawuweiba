<?php

class CategoryController extends Controller
{
    /**
     * 创建和编辑分类
     * @param integer $cid 分类ID，单个分类编辑的时候用到
     */
	public function actionCreate()
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$category = new GoodsCategory();
			$category->attributes = $_POST['GoodsCategory'];
			$category->shop_id = $_SESSION['shop']->id;
			if(!$category->save()) {
				user()->setFlash('errorSummaryC',CHtml::errorSummary($category));
//				$this->redirect(url('shopcp/goods/list', array('type'=>'2')));
				
				$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('t.shop_id'=>$_SESSION['shop']->id));
			    $condition->order = 'orderid desc';
			    $goodscategory = GoodsCategory::model()->findAll($condition);
				
				$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('t.shop_id'=>$_SESSION['shop']->id));
			   	
			   	$sort = new CSort('Goods');
			   	$sort->attributes = array(
			    	'wm_price'=> array(
			    		'label' => '外卖价格', 
			    		'asc' => 'foodGoods.wm_price asc',
			    		'desc' => 'foodGoods.wm_price desc',
			    	), 
			    	'state'=> array(
			    		'label' => '状态', 
			    		'asc' => 't.state asc',
			    		'desc' => 't.state desc',
			    	), 'favorite_nums', 'rate_avg',
			    );
			   	$sort->applyOrder($condition);
			   	
			   	$goods = Goods::model()->with(array('shop', 'foodGoods', 'foodGoods.goodsCategory'))->findAll($condition);
			   	$goods_list = Goods::getSortGoods($goods);
			    
			    $this->pageTitle = '商品管理';
			    $data = array(
			    	'商品列表' => array(
			    		'id' => 'list',
			    		'content' => $this->renderPartial('/goods/list', array('goods_list' => $goods_list, 'sort'=>$sort), true)
			    	),
			    	'新增商品' => array(
			    		'id' => 'create',
			    		'content' => $this->renderPartial('/goods/create', array('goodscategory'=>$goodscategory), true)
			    	),
			    	'商品分类' => array(
			    		'id' => 'category',
			    		'content' => $this->renderPartial('/goods/category', array('goodscategory' => $goodscategory, 'category'=>$category), true)
			    	),
			    );
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'2'));
			}
			$this->redirect(url('shopcp/goods/list', array('type'=>'2')));
		}
	}

	/**
     * 创建和编辑分类
     * @param integer $cid 分类ID，单个分类编辑的时候用到
     */
	public function actionEdit($id = 0)
	{
		if (app()->request->isPostRequest && isset($_POST)) {
			$condition = new CDbCriteria();
			$condition->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
			$goodsCategory = GoodsCategory::model()->findByPk($_POST['id'], $condition);
			$goodsCategory->attributes = $_POST['GoodsCategory'];
			if (!$goodsCategory->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($goodsCategory));
				$this->redirect(url('shopcp/category/edit', array('id'=>$_POST['id'])));
			}
			$this->redirect(url('shopcp/goods/list', array('type'=>'2')));
		}
		$id = (int)$id;
		if($id) {
			$condition = new CDbCriteria();
			$condition->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
			$goodsCategory = GoodsCategory::model()->findByPk($id, $condition);

	    	$this->pageTitle = '编辑分类';
			$data = array(
		    	'编辑分类' => array(
		    		'id' => 'edit',
		    		'content' => $this->renderPartial('edit', array('goodsCategory'=>$goodsCategory), true)
		    	),
		    );
			$this->render('/public/tab', array('tabs'=>$data, 'type'=>'2'));
		}
	}
	
	/**
	 * 删除一个分类
	 * @param integer $cid 分类ID号
	 */
	public function actionDelete($id = 0)
	{
	    $category_id = (int)$id;
		if ($category_id) {
			$condition = new CDbCriteria();
			$condition->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
			$category = GoodsCategory::model()->findByPk($category_id, $condition);
			if(!$category->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($category));
			}
		}
		$this->redirect(url('shopcp/goods/list', array('type'=>'2')));
	}
	
	/**
	 * 分类排序
	 * @param integer $cid 分类ID号
	 */
	public function actionOrder()
	{
		if (app()->request->isPostRequest && isset($_POST)) {
		    foreach ($_POST['orderid'] as $key=>$val) {
		    	$category = GoodsCategory::model()->findByPk($key);
		    	$category->orderid = $val;
		    	if (!$category->save()) {
		    		user()->setFlash('errorSummary',CHtml::errorSummary($category));
		    	}
		    }
		}
		$this->redirect(url('shopcp/goods/list', array('type'=>'2')));
	}

	public function filters()
	{
	    return array(
	    	'postOnly + create',
	    );
	}
}