<?php

class ShopController extends Controller
{
	/*
	 * 待审商铺
	 */
    public function actionUnverify()
    {
    	$citylist = City::getCityArray();
		foreach ($citylist as $key=>$val){
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $key));
	    	$district = District::model()->findAll($condition);
	    	$district_array = CHtml::listData($district, 'id', 'id');
	    	
	    	$condition = new CDbCriteria();
		   	$condition->addInCondition('district_id', $district_array);
		   	$condition->addColumnCondition(array('state' =>STATE_DISABLED));
		   	$shopcount[$val]['count'] = Shop::model()->count($condition);
		}
	    $this->render('unverify', array('shopcount'=>$shopcount, 'citylist'=>$citylist));
    }

	/*
	 * 最近加盟
	 */
    public function actionToday()
    {
    	$date = date('Y-m-d');
		$LastWeek = strtotime($date) - (7 * 24 * 60 * 60);
    	$citylist = City::getCityArray();
		foreach ($citylist as $key=>$val){
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $key));
	    	$district = District::model()->findAll($condition);
	    	$district_array = CHtml::listData($district, 'id', 'id');
	    	
	    	$condition = new CDbCriteria();
		   	$condition->addInCondition('district_id', $district_array);
		   	$condition->addColumnCondition(array('state' =>STATE_ENABLED));
	   		$condition->addCondition('create_time>=' . $LastWeek);
		   	$shopcount[$val]['count'] = Shop::model()->count($condition);
		}
	    $this->render('today', array('shopcount'=>$shopcount, 'citylist'=>$citylist));
    }
    
	
	/**
	 * 商家认证
	 */
	public function actionApprove()
	{
		$citylist = City::getCityArray();
		foreach ($citylist as $key=>$val){
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $key));
	    	$district = District::model()->findAll($condition);
	    	$district_array = CHtml::listData($district, 'id', 'id');
	    	
	    	$condition = new CDbCriteria();
	    	$condition->addCondition("`commercial_instrument` != '' and `is_commercial_approve` = " . STATE_DISABLED);
		   	$condition->addInCondition('district_id', $district_array);
	   		$condition->addColumnCondition(array('state' => STATE_ENABLED));
		   	$shopcount[$val]['commercialcount'] = Shop::model()->count($condition);
		   	
	    	$condition = new CDbCriteria();
	    	$condition->addCondition("`sanitary_license` != '' and `is_sanitary_approve` = " . STATE_DISABLED);
		   	$condition->addInCondition('district_id', $district_array);
	   		$condition->addColumnCondition(array('state' => STATE_ENABLED));
		   	$shopcount[$val]['sanitarycount'] = Shop::model()->count($condition);
		}
	    $this->render('approve', array('shopcount'=>$shopcount, 'citylist'=>$citylist));
	}
	
	public function actionSearch()
	{
		foreach ((array)$_GET['Shop'] as $key=>$val){
			$shop[$key] = strip_tags(trim($val));
		}
		if($shop) {
			$citylist = City::getCityArray();
		    if ($shop['city_id'] != '') {
		    	$condition = new CDbCriteria();
	   			$condition->addColumnCondition(array('city_id' => $shop['city_id']));
		    	$district = District::model()->findAll($condition);
		    	$district_array = CHtml::listData($district, 'id', 'id');
	    	
				$condition = new CDbCriteria();
			    if ($shop['business_state'] != '') {
			    	$condition->addColumnCondition(array('business_state' => $shop['business_state']));
			    }
			    if ($shop['buy_type'] != '') {
			    	$condition->addColumnCondition(array('buy_type' => $shop['buy_type']));
			    }
			    if ($shop['is_commercial_approve'] != '') {
			    	$condition->addColumnCondition(array('is_commercial_approve' => $shop['is_commercial_approve']));
			    }
			    if ($shop['is_sanitary_approve'] != '') {
			    	$condition->addColumnCondition(array('is_sanitary_approve' => $shop['is_sanitary_approve']));
			    }
		   		$condition->addInCondition('district_id', $district_array);
			   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
			   	
			   	$conditionfood = new CDbCriteria();
			   	$conditionfood->mergeWith($condition);
				$conditionfood->addColumnCondition(array('category_id' => ShopCategory::CATEGORY_FOOD));
				$shopcount[$citylist[$shop['city_id']]]['foodcount'] = Shop::model()->count($conditionfood);
				
				$conditioncake = new CDbCriteria();
			   	$conditioncake->mergeWith($condition);
				$conditioncake->addColumnCondition(array('category_id' => ShopCategory::CATEGORY_CAKE));
				$shopcount[$citylist[$shop['city_id']]]['cakecount'] = Shop::model()->count($conditioncake);
		    } 
		    else {
				foreach ($citylist as $key=>$val){
					$condition = new CDbCriteria();
	   				$condition->addColumnCondition(array('city_id' => $key));
			    	$district = District::model()->findAll($condition);
			    	$district_array = CHtml::listData($district, 'id', 'id');
					
					$condition = new CDbCriteria();
				    if ($shop['business_state'] != '') {
				    	$condition->addColumnCondition(array('business_state' => $shop['business_state']));
				    }
				    if ($shop['buy_type'] != '') {
				    	$condition->addColumnCondition(array('buy_type' => $shop['buy_type']));
				    }
				    if ($shop['is_commercial_approve'] != '') {
				    	$condition->addColumnCondition(array('is_commercial_approve' => $shop['is_commercial_approve']));
				    }
				    if ($shop['is_sanitary_approve'] != '') {
				    	$condition->addColumnCondition(array('is_sanitary_approve' => $shop['is_sanitary_approve']));
				    }
			   		$condition->addInCondition('district_id', $district_array);
				   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
				   	
				   	$conditionfood = new CDbCriteria();
				   	$conditionfood->mergeWith($condition);
					$conditionfood->addColumnCondition(array('category_id' => ShopCategory::CATEGORY_FOOD));
					$shopcount[$citylist[$key]]['foodcount'] = Shop::model()->count($conditionfood);
					
					$conditioncake = new CDbCriteria();
				   	$conditioncake->mergeWith($condition);
					$conditioncake->addColumnCondition(array('category_id' => ShopCategory::CATEGORY_CAKE));
					$shopcount[$citylist[$key]]['cakecount'] = Shop::model()->count($conditioncake);
				}
		    }
		    
		    $this->render('statistics', array('shopcount'=>$shopcount, 'citylist'=>$citylist, 'shop'=>$shop));
		}else{
		    $this->redirect(url('super/shop/statistics'));
		}
	}
	
	/**
	 * 统计
	 * Enter description here ...
	 */
	public function actionStatistics()
	{
		$citylist = City::getCityArray();
		
		$shop = new Shop();
		foreach ($citylist as $key=>$val){
			$shopcount[$val]['foodcount']= $shop->getFoodShopCount($key);
			$shopcount[$val]['cakecount']= $shop->getCakeShopCount($key);
		}
	    $this->render('statistics', array('shopcount'=>$shopcount, 'citylist'=>$citylist));
	}
	
	/*
	 * 商铺分类管理
	 */
	public function actionCategory()
	{
	    $category = ShopCategory::model()->findAll();
		$this->render('category', array('category' => $category));
	}

	/**
	 * 添加和编辑分类
	 */
	public function actionCategoryCreate()
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$category_post = $_POST['Category'];
			$category = new ShopCategory();
			$category->name = $category_post['name'];
			$category->parent_id = 1;
			$category->state = 1;
			if (!$category->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($category));
	    		$category = ShopCategory::model()->findAll();
				$this->render('category', array('category_post' => $category_post, 'category' => $category));
				exit;
			}
		}
		$this->redirect(url('admin/shop/category'));
	}
	
	/**
	 * 添加和编辑分类
	 */
	public function actionCategoryEdit($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$category_post = $_POST['ShopCategory'];
			$category = ShopCategory::model()->findByPk($_POST['id']);
			$category->name = $category_post['name'];
			if (!$category->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($category));
				$this->render('edit', array('category' => $category, 'id'=>$_POST['id'], 'category_post'=>$category_post));
				exit;
			}
			$this->redirect(url('admin/shop/category'));
		}
		$id = (int)$id;
		if($id) {
		    $category = ShopCategory::model()->findByPk($id);
		    
			$this->render('edit', array('category' => $category));
		}
	}

	/**
	 * 删除分类
	 */
	public function actionCategoryDelete($id = 0)
	{
	    $category_id = (int)$id;
		if ($category_id) {
			$category = ShopCategory::model()->findByPk($category_id);
			$category->state = STATE_DISABLED;
			if (!$category->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($category));
			}
		}
		$this->redirect(url('admin/shop/category'));
	}

	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('unverify', 'profile', 'approve', 'delete'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array('unverify', 'profile', 'approve', 'delete'),
	            'users' => array('*'),
	        ),
	    );
	}
	
	public function filters()
	{
	    return array(
	        'accessControl',
	    );
	}
}