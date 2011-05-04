<?php

class GoodsController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Goods::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
    /**
     * 添加或编辑商品
     * @param integer $goodsid 商品ID，编辑商品使用，默认为0，表示是添加商品
     */
	public function actionCreate()
	{
		if(app()->request->isPostRequest && isset($_POST['Goods'])) {
			$error = '';
			$goods = new Goods();
			$file = CUploadedFile::getInstanceByName('Goods[pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(180, 135);
					if ($image->save()) {
						$fileUrl = $filePath['relative'] . $filename;
						$goods->pic = $fileUrl;
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			$goods->attributes = $_POST['Goods'];
			$goods->shop_id = $_SESSION['shop']->id;
			
			if(!$goods->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($goods));
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				
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
			   	
			   	$goods_list = Goods::model()->with(array('shop', 'foodGoods', 'foodGoods.goodsCategory'))->findAll($condition);
			   	$goods_list = Goods::getSortGoods($goods_list);
			    
			    $this->pageTitle = '商品管理';
			    $data = array(
			    	'商品列表' => array(
			    		'id' => 'list',
			    		'content' => $this->renderPartial('list', array('goods_list' => $goods_list, 'sort'=>$sort), true)
			    	),
			    	'新增商品' => array(
			    		'id' => 'create',
			    		'content' => $this->renderPartial('create', array('goodscategory'=>$goodscategory, 'goods'=>$goods, 'foodgoods'=>$foodgoods), true)
			    	),
			    	'商品分类' => array(
			    		'id' => 'category',
			    		'content' => $this->renderPartial('category', array('goodscategory' => $goodscategory), true)
			    	),
			    );
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'1'));
				exit;
			}
			
			$foodgoods = new FoodGoods();
			if (empty($_POST['FoodGoods']['group_price'])) $_POST['FoodGoods']['group_price'] =$_POST['FoodGoods']['wm_price'];
			$foodgoods->attributes = $_POST['FoodGoods'];
			$foodgoods->goods_id = $goods->id;
			if(!$foodgoods->save()) {
				user()->setFlash('errorSummaryF',CHtml::errorSummary($foodgoods));
				if (!$goods->delete()) {
					user()->setFlash('errorSummaryD',CHtml::errorSummary($goods));
				}
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				
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
			   	
			   	$goods_list = Goods::model()->with(array('shop', 'foodGoods', 'foodGoods.goodsCategory'))->findAll($condition);
			   	$goods_list = Goods::getSortGoods($goods_list);
			    
			    $this->pageTitle = '商品管理';
			    $data = array(
			    	'商品列表' => array(
			    		'id' => 'list',
			    		'content' => $this->renderPartial('list', array('goods_list' => $goods_list, 'sort'=>$sort), true)
			    	),
			    	'新增商品' => array(
			    		'id' => 'create',
			    		'content' => $this->renderPartial('create', array('goodscategory'=>$goodscategory, 'goods'=>$goods, 'foodgoods'=>$foodgoods), true)
			    	),
			    	'商品分类' => array(
			    		'id' => 'category',
			    		'content' => $this->renderPartial('category', array('goodscategory' => $goodscategory), true)
					),
			    );
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'1'));
			}
		}
		$this->redirect(url('shopcp/goods/list'));
	}
	
	/**
	 * 批量添加
	 */
	public function actionAddlist()
	{
		if(app()->request->isPostRequest && isset($_POST['category_id'])) {
			$category_id = intval($_POST['category_id']);
			$name = $_POST['name'];
			$wm_price = $_POST['wm_price'];
			$desc = $_POST['desc'];
			foreach ($name as $key=>$n) {
				if($n) {
					$goods = new Goods();
					$goods->name = strip_tags(trim($n));
					$goods->shop_id = $_SESSION['shop']->id;
					if($goods->save()) {
						$foodgoods = new FoodGoods();
						$foodgoods->goods_id = $goods->id;
						$foodgoods->wm_price = floatval($wm_price[$key]);
						$foodgoods->desc = strip_tags(trim($desc[$key]));
						$foodgoods->category_id = $category_id;
						$foodgoods->save();
					}
				}
			}
			$this->redirect(url('shopcp/goods/list'));
		}
	}
	
    /**
     * 添加或编辑蛋糕商品
     * @param integer $goodsid 商品ID，编辑商品使用，默认为0，表示是添加商品
     */
	public function actionCreateCake()
	{
		if(app()->request->isPostRequest && isset($_POST['Goods'])) {
			$error = '';
			$goods = new Goods();
			$goods->attributes = $_POST['Goods'];
			$goods->shop_id = $_SESSION['shop']->id;
			
			$cakegoods = new CakeGoods();
			$cakegoods->attributes = $_POST['CakeGoods'];
			
			$file = CUploadedFile::getInstanceByName('Goods[pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(135, 135);
					if ($image->save()) {
						$fileUrl = $filePath['relative'] . $filename;
						$goods->pic = $fileUrl;
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			
			$file = CUploadedFile::getInstanceByName('CakeGoods[big_pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
		        	$fileUrl = $filePath['relative'] . $filename;
			        $cakegoods->big_pic = $fileUrl;
			        
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(135, 135);
					if ($image->save($filePath['absolute'] . 'small' . $filename)) {
						$fileUrl = $filePath['relative'] . 'small' . $filename;
						$goods->pic = $fileUrl;
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			$file = CUploadedFile::getInstanceByName('CakeGoods[small_pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
		        	$fileUrl = $filePath['relative'] . $filename;
			        $cakegoods->small_pic = $fileUrl;
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			
			if(!$goods->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($goods));
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				
				$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('t.shop_id'=>$_SESSION['shop']->id));
			   	$sort = new CSort('Goods');
			   	$sort->attributes = array(
			    	'state'=> array(
			    		'label' => '状态',
			    		'asc' => 't.state asc',
			    		'desc' => 't.state desc',
			    	), 'favorite_nums', 'rate_avg',
			    );
			   	$sort->applyOrder($condition);
			   	$goods_list = Goods::model()->findAll($condition);
			    $this->pageTitle = '商品管理';
			    $data = array(
			    	'商品列表' => array(
			    		'id' => 'listcake',
			    		'content' => $this->renderPartial('listcake', array('goods_list' => $goods_list, 'sort'=>$sort), true)
			    	),
			    	'新增商品' => array(
			    		'id' => 'createcake',
			    		'content' => $this->renderPartial('createcake', array('goods'=>$goods, 'cakegoods'=>$cakegoods, 'purpose_id'=>$_POST['CakePurpose']['purpose_id'], 'variety_id'=>$_POST['CakeVariety']['variety_id'], 'CakePrice'=>$_POST['CakePrice']), true)
			    	),
			    );
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'1'));exit;
			}
			
			$cakegoods->goods_id = $goods->id;
			if(!$cakegoods->save()) {
				user()->setFlash('errorSummaryF',CHtml::errorSummary($cakegoods));
				if (!$goods->delete()) {
					user()->setFlash('errorSummaryD',CHtml::errorSummary($goods));
				}
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				
				$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('t.shop_id'=>$_SESSION['shop']->id));
			   	$sort = new CSort('Goods');
			   	$sort->attributes = array(
			    	'state'=> array(
			    		'label' => '状态',
			    		'asc' => 't.state asc',
			    		'desc' => 't.state desc',
			    	), 'favorite_nums', 'rate_avg',
			    );
			   	$sort->applyOrder($condition);
			   	$goods_list = Goods::model()->findAll($condition);
			    $this->pageTitle = '商品管理';
			    $data = array(
			    	'商品列表' => array(
			    		'id' => 'listcake',
			    		'content' => $this->renderPartial('listcake', array('goods_list' => $goods_list, 'sort'=>$sort), true)
			    	),
			    	'新增商品' => array(
			    		'id' => 'createcake',
			    		'content' => $this->renderPartial('createcake', array('goods'=>$goods, 'cakegoods'=>$cakegoods), true)
			    	),
			    );
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'1'));exit;
				
			}
			
			if ($_POST['CakePrice']['size']) {
				foreach ($_POST['CakePrice']['size'] as $key=>$val) {
					$cakeprice = new CakePrice();
					$cakeprice->size = $val;
					$cakeprice->goods_id = $goods->id;
					$cakeprice->market_price = $_POST['CakePrice']['market_price'.$val];
					$cakeprice->wm_price = $_POST['CakePrice']['wm_price'.$val];
					$cakeprice->desc = $_POST['CakePrice']['desc'.$val];
					if (!$cakeprice->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakeprice));
	//					$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
					}
				}
			}
			if ($_POST['CakePurpose']['purpose_id']) {
				foreach ($_POST['CakePurpose']['purpose_id'] as $key=>$val) {
					$cakePurpose = new CakePurpose();
					$cakePurpose->purpose_id = $val;
					$cakePurpose->goods_id = $goods->id;
					if (!$cakePurpose->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakePurpose));
					}
				}
			}
			if ($_POST['CakePurpose']['purpose_id']) {
				foreach ($_POST['CakeVariety']['variety_id'] as $key=>$val) {
					$cakeVariety = new CakeVariety();
					$cakeVariety->variety_id = $val;
					$cakeVariety->goods_id = $goods->id;
					if (!$cakeVariety->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakeVariety));
					}
				}
			}
		}
		$this->redirect(url('shopcp/goods/list'));
	}
	
	/**
     * 添加或编辑商品
     * @param integer $goodsid 商品ID，编辑商品使用，默认为0，表示是添加商品
     */
	public function actionEdit($id = 0)
	{
		if (app()->request->isPostRequest && isset($_POST['Goods'])) {
			$error = '';
		    $goodsid = (int)$_POST['id'];
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('t.shop_id' => $_SESSION['shop']->id));
			$goods = Goods::model()->with('foodGoods', 'foodGoods.goodsCategory')->findByPk($goodsid, $condition);
			if (null === $goods) throw new CException('该商品资料不存在', 0);
			if ($goods->pic) {
				$oldfile = param('staticBasePath') . $goods->pic;
			}
			
			$goods->attributes = $_POST['Goods'];
			$goods->shop_id = $_SESSION['shop']->id;
			$goods->pic = $_POST['Goods']['picOriginal'];
					
			$file = CUploadedFile::getInstanceByName('Goods[pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;

		        if ($file->saveAs($fileSavePath)) {
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(180, 135);
					if ($image->save()) {
						$fileUrl = $filePath['relative'] . $filename;
						$goods->pic = $fileUrl;
						
						if(file_exists($oldfile)) {
							unlink($oldfile);
						}
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			if(!$goods->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($goods));
				$this->redirect(url('shopcp/goods/edit', array('id'=>$_POST['id'])));
			}
			$foodgoods = FoodGoods::model()->findByPk($goods->id);
			if (empty($_POST['FoodGoods']['group_price'])) $_POST['FoodGoods']['group_price'] =$_POST['FoodGoods']['wm_price'];
			$foodgoods->attributes = $_POST['FoodGoods'];
			if (!$foodgoods->save()) {
				user()->setFlash('errorSummaryF',CHtml::errorSummary($foodgoods));
				$this->redirect(url('shopcp/goods/edit', array('id'=>$_POST['id'])));
			} else {
				if ($goods->foodGoods->goodsCategory->id != $_POST['FoodGoods']['category_id']) {
					$foodgoods->goodsCategory->goods_nums++;
				    $foodgoods->goodsCategory->update();
					if ($goods->foodGoods->goodsCategory->id){
					    $goods->foodGoods->goodsCategory->goods_nums--;
					    $goods->foodGoods->goodsCategory->update();
					}
				}
			}
			$this->redirect(url('shopcp/goods/list'));
		}
	    
    	$id = (int)$id;
	    if($id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		    $condition->order = 'orderid desc';
		    $goodscategory = GoodsCategory::model()->findAll($condition);
		    
		    $goods_info = Goods::model()->findByPk($id);
		    if (null === $goods_info) throw new CException('该商品资料不存在', 0);
	    	$this->pageTitle = '编辑商品';
		    $data = array(
		    	'编辑商品' => array(
		    		'id' => 'edit',
		    		'content' => $this->renderPartial('edit', array('goodscategory'=>$goodscategory, 'goods_info'=>$goods_info), true)
		    	),
		    );
			$this->render('/public/tab', array('tabs'=>$data));
	    }
	}

	/**
     * 添加或编辑商品
     * @param integer $goodsid 商品ID，编辑商品使用，默认为0，表示是添加商品
     */
	public function actionEditCake($id = 0)
	{
		if (app()->request->isPostRequest && isset($_POST['Goods'])) {
			$error = '';
		    $goodsid = (int)$_POST['id'];
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$goods = Goods::model()->findByPk($goodsid, $condition);
			if ($goods->pic) {
				$oldfile = param('staticBasePath') . $goods->pic;
			}
			if (null === $goods) throw new CException('该商品资料不存在', 0);
			
			$goods->attributes = $_POST['Goods'];
			$goods->shop_id = $_SESSION['shop']->id;
			$goods->pic = $_POST['Goods']['picOriginal'];

			$cakegoods = CakeGoods::model()->findByPk($goods->id);
			if ($cakegoods->big_pic) {
				$oldfile_big = param('staticBasePath') . $cakegoods->big_pic;
			}if ($cakegoods->small_pic) {
				$oldfile_small = param('staticBasePath') . $cakegoods->small_pic;
			}
			$cakegoods->attributes = $_POST['CakeGoods'];
			$cakegoods->big_pic = $_POST['CakeGoods']['big_picOriginal'];
			$cakegoods->small_pic = $_POST['CakeGoods']['small_picOriginal'];
			
			$file = CUploadedFile::getInstanceByName('Goods[pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;

		        if ($file->saveAs($fileSavePath)) {
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(135, 135);
					if ($image->save()) {
						$fileUrl = $filePath['relative'] . $filename;
						$goods->pic = $fileUrl;
					
						if(file_exists($oldfile)) {
							unlink($oldfile);
						}
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			
			$file = CUploadedFile::getInstanceByName('CakeGoods[big_pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
		        	$fileUrl = $filePath['relative'] . $filename;
			        $cakegoods->big_pic = $fileUrl;
		        
			        $image = Yii::app()->image->load($fileSavePath);
					$image->resize(135, 135);
					if ($image->save($filePath['absolute'] . 'small' . $filename)) {
						$fileUrl = $filePath['relative'] . 'small' . $filename;
						$goods->pic = $fileUrl;
					
						if(file_exists($oldfile_big)) {
							unlink($oldfile_big);
						}
					
						if(file_exists($oldfile)) {
							unlink($oldfile);
						}
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			$file = CUploadedFile::getInstanceByName('CakeGoods[small_pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('pic');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
		        	$fileUrl = $filePath['relative'] . $filename;
			        $cakegoods->small_pic = $fileUrl;
		        
					if(file_exists($oldfile_small)) {
						unlink($oldfile_small);
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			if(!$goods->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($goods));
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				$this->redirect(url('shopcp/goods/editcake', array('id'=>$_POST['id'])));
			}
		    
			if(!$cakegoods->save()) {
				user()->setFlash('errorSummaryF',CHtml::errorSummary($cakegoods));
				if (!$goods->delete()) {
					user()->setFlash('errorSummaryD',CHtml::errorSummary($goods));
				}
//				$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
				$this->redirect(url('shopcp/goods/editcake', array('id'=>$_POST['id'])));
			}
			
			$condition = new CDbCriteria();
			$condition->addCondition('goods_id=' . $goods->id);
			if(!CakePrice::model()->deleteAll($condition)) {
				echo '删除失败!';
			}
			if(!CakePurpose::model()->deleteAll($condition)) {
				echo '删除失败!';
			}
			if(!CakeVariety::model()->deleteAll($condition)) {
				echo '删除失败!';
			}
			
			if ($_POST['CakePrice']['size']) {
				foreach ($_POST['CakePrice']['size'] as $key=>$val) {
					$cakeprice = new CakePrice();
					$cakeprice->size = $val;
					$cakeprice->goods_id = $goods->id;
					$cakeprice->market_price = $_POST['CakePrice']['market_price'.$val];
					$cakeprice->wm_price = $_POST['CakePrice']['wm_price'.$val];
					$cakeprice->desc = $_POST['CakePrice']['desc'.$val];
					if (!$cakeprice->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakeprice));
//					$this->redirect(url('shopcp/goods/list', array('type'=>'1')));
					}
				}
			}
			if ($_POST['CakePurpose']['purpose_id']) {
				foreach ($_POST['CakePurpose']['purpose_id'] as $key=>$val) {
					$cakePurpose = new CakePurpose();
					$cakePurpose->purpose_id = $val;
					$cakePurpose->goods_id = $goods->id;
					if (!$cakePurpose->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakePurpose));
					}
				}
			}
			if ($_POST['CakePurpose']['purpose_id']) {
				foreach ($_POST['CakeVariety']['variety_id'] as $key=>$val) {
					$cakeVariety = new CakeVariety();
					$cakeVariety->variety_id = $val;
					$cakeVariety->goods_id = $goods->id;
					if (!$cakeVariety->save()) {
						user()->setFlash('errorSummaryD',CHtml::errorSummary($cakeVariety));
					}
				}
			}
			$this->redirect(url('shopcp/goods/list'));
		}
	    
    	$id = (int)$id;
	    if($id) {
		    $goods_info = Goods::model()->findByPk($id);
		    
	    	$this->pageTitle = '编辑商品';
		    $data = array(
		    	'编辑商品' => array(
		    		'id' => 'editcake',
		    		'content' => $this->renderPartial('editcake', array('goods_info'=>$goods_info), true)
		    	),
		    );
			$this->render('/public/tab', array('tabs'=>$data));
	    }
	}
	
	/**
	 * 商品列表
	 * @param integer $cid 商品自定义分类ID，默认为0，读取全部商品
	 */
	public function actionList($type = 0)
	{
		$type = (int)$type;
		if ($_SESSION['shop']->category_id == ShopCategory::CATEGORY_FOOD) {
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
		    		'content' => $this->renderPartial('list', array('goods_list' => $goods_list, 'sort'=>$sort), true)
		    	),
		    	'新增商品' => array(
		    		'id' => 'create',
		    		'content' => $this->renderPartial('create', array('goodscategory'=>$goodscategory), true)
		    	),
		    	'商品分类' => array(
		    		'id' => 'category',
		    		'content' => $this->renderPartial('category', array('goodscategory' => $goodscategory), true)
				),
				'批量添加' => array(
			    	'id' => 'addlist',
			    	'content' => $this->renderPartial('addlist', array('goodscategory'=>$goodscategory), true)
			    ),
			    '商品导入' => array(
		    		'id' => 'import',
		    		'content' => $this->renderPartial('import', array(), true)
			    )
			);
			$this->render('/public/tab', array('tabs'=>$data, 'selected'=>$type));
		}else{
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('t.shop_id'=>$_SESSION['shop']->id));
		   	$sort = new CSort('Goods');
		   	$sort->attributes = array(
		    	'state'=> array(
		    		'label' => '状态',
		    		'asc' => 't.state asc',
		    		'desc' => 't.state desc',
		    	), 'favorite_nums', 'rate_avg',
		    );
		   	$sort->applyOrder($condition);
		   	$goods_list = Goods::model()->findAll($condition);
//		   	$goods_list = Goods::getSortGoods($goods_list);
		   	
		    $this->pageTitle = '商品管理';
		    $data = array(
		    	'商品列表' => array(
		    		'id' => 'listcake',
		    		'content' => $this->renderPartial('listcake', array('goods_list' => $goods_list, 'sort'=>$sort), true)
		    	),
		    	'新增商品' => array(
		    		'id' => 'createcake',
		    		'content' => $this->renderPartial('createcake', array(), true)
		    	),
		    );
			$this->render('/public/tab', array('tabs'=>$data, 'selected'=>$type));
		}
	}

	/**
	 * 删除一个商品
	 * @param integer $goodsid 商品ID
	 */
	public function actionDelete($id = 0)
	{
		$goods_id = (int)$id;
		$criteria = new CDbCriteria();
	   	$criteria->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
	   	$goods = Goods::model()->findByPk($goods_id, $criteria);
		if($goods) {
			$goods->goodsModel->delete();
		}
		$this->redirect(url('shopcp/goods/list'));
	}

	/**
	 *
	 * Enter description here ...
	 */
	public function actionDeletemore()
	{
		$goodsid = $_POST['goodsid'];
		foreach ((array)$goodsid as $gid) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
			$goods = Goods::model()->findByPk($gid, $criteria);
			if($goods) {
				$goods->goodsModel->delete();
			}
		}
		$this->redirect(url('shopcp/goods/list'));
	}
	
	/**
	 * 设置商品状态，上架/下架/售完
	 * @param integer $goodsid 商品ID
	 */
	public function actionState($id = 0, $state = 0)
	{
	    $goods_id = (int)$id;
	    $state = (int)$state;
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('shop_id'=>$_SESSION['shop']->id));
	    $goods = Goods::model()->findByPk($goods_id, $condition);
	    $goods->state = $state;
		if(!$goods->save()) {
			user()->setFlash('errorSummary',CHtml::errorSummary($goods));
		}
		$this->redirect(url('shopcp/goods/list'));
	}

	/**
	 * 参与团购的商品列表
	 * @param integer $cid 商品自定义分类ID，默认为0，读取全部商品
	 */
	public function actionTuan($cid = 0)
	{
	    $cid = (int)$cid;
		$this->render('tuan');
	}
	
	/**
	 * 每日菜单列表
	 * @param integer $cid 商品自定义分类ID，默认为0，读取全部商品
	 */
	public function actionDayList($week = 0)
	{
		if ($_SESSION['shop']->is_dailymenu == 1) {
		    $condition = new CDbCriteria();
		    $condition->addColumnCondition(array('t.shop_id' => $_SESSION['shop']->id));
		    $day_list = DayList::model()->findAll($condition);
		    
		    $condition->order = 't.create_time desc';
		    $goods = Goods::model()->with(array('shop', 'foodGoods', 'foodGoods.goodsCategory'))->findAll($condition);
	   		$goods_list = Goods::getSortGoods($goods);
		    
		    foreach ($day_list as $key=>$val) {
		    	for ($i=1; $i<=7; $i++) {
			    	if($val->week==$i) {
			    		$list[$i][]=$val->goods_id;
			    	}
		    	}
		    }
		    
		    $this->pageTitle = '每日菜单';
		    $data = array(
		    	'周一菜单' => array(
		    		'id' => 'Monday',
		    		'content' => $this->renderPartial('Monday', array('goods_list' => $goods_list, 'day_list' => $list[1]), true)
		    	),
		    	'周二菜单' => array(
		    		'id' => 'Tuesday',
		    		'content' => $this->renderPartial('Tuesday', array('goods_list' => $goods_list, 'day_list' => $list[2]), true)
		    	),
		    	'周三菜单' => array(
		    		'id' => 'Wednesday',
		    		'content' => $this->renderPartial('Wednesday', array('goods_list' => $goods_list, 'day_list' => $list[3]), true)
		    	),
		    	'周四菜单' => array(
		    		'id' => 'Thursday',
		    		'content' => $this->renderPartial('Thursday', array('goods_list' => $goods_list, 'day_list' => $list[4]), true)
		    	),
		    	'周五菜单' => array(
		    		'id' => 'Friday',
		    		'content' => $this->renderPartial('Friday', array('goods_list' => $goods_list, 'day_list' => $list[5]), true)
		    	),
		    	'周六菜单' => array(
		    		'id' => 'Saturday',
		    		'content' => $this->renderPartial('Saturday', array('goods_list' => $goods_list, 'day_list' => $list[6]), true)
		    	),
		    	'周日菜单' => array(
		    		'id' => 'Sunday',
		    		'content' => $this->renderPartial('Sunday', array('goods_list' => $goods_list, 'day_list' => $list[7]), true)
		    	),
		    );
		    
		    $week = (int)($week-1);
			$this->render('/public/tab', array('tabs'=>$data, 'selected'=>$week));
		} else {
			$this->pageTitle = '每日菜单';
			$data = array(
		    	'每日菜单' => array(
		    		'id' => 'Monday',
		    		'content' => $this->renderPartial('Monday', array('is_dailymenu' => sprintf('您没有开启每日菜单功能，请进入%s开启每日菜单功能', l('店铺设置', url('shopcp/shop/profile'), array('class'=>'fb cred')))), true)
		    	),
		    );
		    $this->render('/public/tab', array('tabs'=>$data));
		}
	}
	/**
	 * 每日菜单修改添加
	 * Enter description here ...
	 */
	public function actionDayCreate()
	{
		if (app()->request->isPostRequest && isset($_POST)) {
			$condition = new CDbCriteria();
			$condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$day_list = DayList::model()->findAll($condition);
		    foreach ($day_list as $key=>$val) {
		    	for ($i=1; $i<=7; $i++) {
			    	if ($val->week==$i) {
			    		$list[$i][]=$val->goods_id;
			    	}
		    	}
		    }
		    
	    	$week = $_POST['week'];
	    	$daylist=array();
	    	foreach ($_POST['i'] as $k=>$v) {
	    		foreach ($_POST as $key=>$val){
	    			if ($key=='DayList'.$week.$v){
			    		$daylist = array_merge($daylist,$_POST['DayList'.$week.$v]);
	    			}
	    		}
	    	}
	    	if (isset($list[$week]) && isset($daylist)) {
		    	$delresult = array_diff($list[$week], $daylist);
		    	$addresult = array_diff($daylist, $list[$week]);
	    	}elseif (!isset($list[$week])) {
	    		$addresult = $daylist;
	    	}elseif (!isset($daylist)) {
	    		$delresult = $list[$week];
	    	}
	    	if ($delresult){
			    $comma_separated = implode(",", $delresult);
			    $goodscondition = new CDbCriteria();
			    $goodscondition->addCondition('goods_id in(' . $comma_separated . ') and shop_id=' . $_SESSION['shop']->id . ' and week=' . $week);
			    DayList::model()->deleteAll($goodscondition);
	    	}
	    	if ($addresult){
			    foreach ($addresult as $key => $val) {
				    $day = new DayList();
				    $day->shop_id = $_SESSION['shop']->id;
				    $day->goods_id = $val;
				    $day->week = $week;
				    if(!$day->save()) {
						user()->setFlash('errorSummary',CHtml::errorSummary($day));
					}
			    }
	    	}
	    }
	    
	    $this->redirect(url('shopcp/goods/daylist', array('week'=>$week)));
	}

	/**
	 * 商品批量导入
	 */
	public function actionImport()
	{
		$file = CUploadedFile::getInstanceByName('postimport');
		if ($file->hasError || !$file) {
			$error = '上传错误';
		} else {
			$list = file($file->tempName);
			if(!$list) {
				exit;
			}
			$shop_id = $_SESSION['shop']->id;
			$category_id = 0;
			foreach ($list as $value) {
				if($value) {
					$value = iconv("GBK", "UTF-8", $value);
					$value = str_replace('，', ',', $value);
					$temp = explode(',', $value);
					if(floatval($temp[1])) {
						$name = trim($temp[0]);
						$wm_price = floatval($temp[1]);
						$desc = trim($temp[2]);
						$goods = new Goods();
						$goods->name = $name;
						$goods->shop_id = $shop_id;
						$goods->save();
						$goods_id = $goods->id;
						$foodgoods = new FoodGoods();
						$foodgoods->goods_id = $goods_id;
						$foodgoods->category_id = $category_id;
						$foodgoods->wm_price = $wm_price;
						$foodgoods->desc = $desc;
						$foodgoods->save();
					} else {
						$name = trim($temp[0]);
						$criteria = new Cdbcriteria();
						$criteria->addColumnCondition(array('name'=>$name, 'shop_id'=>$shop_id));
						$goodscategory = GoodsCategory::model()->find($criteria);
						if($goodscategory->id) {
							$category_id = $goodscategory->id;
						} else {
							$goodscategory = new GoodsCategory();
							$goodscategory->shop_id = $shop_id;
							$goodscategory->name = $name;
							$goodscategory->save();
							$category_id = $goodscategory->id;
						}
					}
				}
			}
		}
		$this->redirect(url('shopcp/goods/list'));
	}
	
	public function filters()
	{
	    return array(
	    	'postOnly + daycreate',
	    );
	}
}