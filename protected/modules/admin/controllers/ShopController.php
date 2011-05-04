<?php

class ShopController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Shop::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 待审商铺
	 */
    public function actionUnverify()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
    	$district = District::model()->findAll($condition);
    	$district = CHtml::listData($district, 'id', 'id');
    	
    	$condition = new CDbCriteria();
	   	$condition->addInCondition('district_id', $district);
	   	$condition->addColumnCondition(array('state' =>STATE_DISABLED));
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$shop = Shop::model()->findAll($condition);
	    $this->render('unverify', array('shop'=>$shop, 'pages'=>$pages));
    }

	/**
	 * 审核
	 */
	public function actionState($id = 0, $state=1)
	{
	    $shop_id = (int)$id;
	    $shop = Shop::model()->findByPk($shop_id);
	    if(key_exists($state, Shop::$states)) {
	    	$shop->state = $state;
	    }
		if(!$shop->save()) {
			user()->setFlash('errorSummary',CHtml::errorSummary($shop));
		} else {
			if($state == Shop::STATE_VERIFY) {
				AdminLog::saveManageLog('审核商铺(' . $shop->shop_name . ')通过');
				UserAction::addNewAction(UserAction::TYPE_SHOP_REGISTER, null, $shop->shop_name);
			}
		}
		$this->redirect(url('admin/shop/unverify'));
	}

	/**
	 * 删除待审核商铺
	 */
	public function actionDelete($id)
	{
	    $shop_id = (int)$id;
	    $shop = Shop::model()->findByPk($shop_id);
		if(!$shop->delete()) {
			user()->setFlash('errorSummary',CHtml::errorSummary($shop));
		} else {
			AdminLog::saveManageLog('删除待审核商铺(' . $shop->shop_name . ')');
		}
		$this->redirect(url('admin/shop/unverify'));
	}
    
	/**
	 * 最近加盟
	 */
    public function actionToday()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
    	$district = District::model()->findAll($condition);
    	$district = CHtml::listData($district, 'id', 'id');
    	
    	$date = date('Y-m-d');
		$LastWeek = strtotime($date) - (7 * 24 * 60 * 60);
    	$condition = new CDbCriteria();
	   	$condition->addInCondition('district_id', $district);
	   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
	   	$condition->addCondition('create_time>=' . $LastWeek);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$shop = Shop::model()->findAll($condition);
	    $this->render('today', array('shop'=>$shop, 'pages'=>$pages));
    }
    
	/**
	 * 全部店铺
	 */
    public function actionAll()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
    	$district = District::model()->findAll($condition);
    	$district = CHtml::listData($district, 'id', 'id');
    	
    	$condition = new CDbCriteria();
	   	$condition->addInCondition('district_id', $district);
	   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
	    $condition->order = 'create_time desc';
		$pages = $this->_getPages($condition);
    	$shop = Shop::model()->findAll($condition);
	    $this->render('all', array('shop'=>$shop, 'pages'=>$pages));
    }
    
    /**
     * 商家资料修改
     * @param integer $shopid 商家ID，如果为空则取cookie中的，如果不为空，则取该ID的商家资料
     */
	public function actionProfile($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST['Shop'])) {
		    $shop_id = (int)$_POST['id'];
		    $shop_info = Shop::model()->findByPk($shop_id);
			$shop_info->attributes = $_POST['Shop'];
			$shopTag = ShopCategory::getShopCategoryArray();
			$tagids = Tag::getTagId($shopTag);
			$shopTag = array_flip($tagids);
			if(!$shop_info->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop_info));
			    $this->render('profile',
				    array(
				    	'shop_info'=>$shop_info,
				    	'shopTag' => $shopTag,
						'district' => District::getDistrictArray($shop_info->district->city->id),
				    )
				);
			} else {
				$sql = 'delete from wm_ShopTag where shop_id=' . $shop_info->id;
				$command = app()->db->createCommand($sql);
				$command->execute();
				if ($_POST['tags']) {
					$sql = "insert into wm_ShopTag values ";
					$dot = '';
					foreach ($_POST['tags'] as $tagid) {
						$sql .=  $dot . "($shop_info->id, $tagid)";
						$dot = ',';
					}
					$command = app()->db->createCommand($sql);
					$command->execute();
				}
				AdminLog::saveManageLog('修改商铺(' . $shop_info->shop_name . ')信息');
				$this->redirect(url('admin/shop/all'));
			}
		} else {
		    $shop_id = (int)$id;
		    $shop_info = Shop::model()->findByPk($shop_id);
		    $shopTag = ShopCategory::getShopCategoryArray();
			$tagids = Tag::getTagId($shopTag);
			$shopTag = array_flip($tagids);
		    $this->render('profile',
			    array(
			    	'shop_info'=>$shop_info,
			    	'shopTag' => $shopTag,
					'district' => District::getDistrictArray($shop_info->district->city->id),
			    )
			);
		}
	}
	
	/**
	 * 商家认证
	 */
	public function actionApprove($type = '', $id = 0, $is = 0)
	{
	    $type = strip_tags(trim($type));
		if($type) {
		    $shop_id = (int)$id;
		    $is = (int)$is;
		    $shop_info = Shop::model()->findByPk($shop_id);
		    if ($type=='commercial') {
		    	if ($is==STATE_ENABLED) {
		    		$shop_info->is_commercial_approve = STATE_ENABLED;
		    	} else {
		    		$shop_info->commercial_instrument = '';
		    	}
		    }
			if ($type=='sanitary') {
		    	if ($is==STATE_ENABLED) {
		    		$shop_info->is_sanitary_approve = STATE_ENABLED;
		    	} else {
		    		$shop_info->sanitary_license = '';
		    	}
		    }
		    
			if(!$shop_info->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop_info));
//				$this->redirect(url('shopcp/shop/approve'), array('shop_info' => $shop_info));
			} else {
				AdminLog::saveManageLog('更改商铺认证(' . $shop_info->shop_name . ')信息');
			}
		}
		
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
    	$district = District::model()->findAll($condition);
    	$district = CHtml::listData($district, 'id', 'id');
    	
    	$condition = new CDbCriteria();
    	$condition->addCondition("`commercial_instrument` != '' and `is_commercial_approve` = " . STATE_DISABLED, 'OR');
    	$condition->addCondition("`sanitary_license` != '' and `is_sanitary_approve` = " . STATE_DISABLED, 'OR');
//    	mergeWith(CDbCriteria $criteria, boolean $useAnd=true)
	   	$condition->addInCondition('district_id', $district);
	   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$shop = Shop::model()->findAll($condition);
    	
		$this->render('approve', array('shop'=>$shop, 'pages'=>$pages));
	}
	
	public function actionSearch()
	{
		foreach ((array)$_GET['Shop'] as $key=>$val){
			$shop[$key] = strip_tags(trim($val));
		}
		$criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('super_shop'=>STATE_ENABLED, 'city_id'=>$_SESSION['manage_city_id']));
	    $users = User::model()->findAll($criteria);
	    $yewu = array();
	    foreach ((array)$users as $u) {
	    	$yewu[$u->id] = $u->username;
	    }
		if($shop) {
	    	$condition = new CDbCriteria();
	   		$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    	$district = District::model()->findAll($condition);
	    	$district = CHtml::listData($district, 'id', 'id');
	    	
			$condition = new CDbCriteria();
	   		$condition->addInCondition('district_id', $district);
			if ($shop['state'] != '') {
		    	$condition->addColumnCondition(array('state' => $shop['state']));
		    }
		    if ($shop['shop_name'] != '') {
		    	$condition->addSearchCondition('shop_name', $shop['shop_name']);
		    }
		    if ($shop['category_id'] != '') {
		    	$condition->addColumnCondition(array('category_id' => $shop['category_id']));
		    }
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
			if ($shop['yewu_id'] != '') {
		    	$condition->addColumnCondition(array('yewu_id' => $shop['yewu_id']));
		    }
		    $condition->order = 'id desc';
		    $pages = $this->_getPages($condition);
		    $shops = Shop::model()->findAll($condition);
		    $this->render('search', array('shops' => $shops, 'shop' => $shop, 'pages' => $pages, 'yewu'=>$yewu));
		} else {
		    $this->render('search', array('yewu'=>$yewu));
		}
	}
	
	public function actionSearchforbind()
	{
		$kw = trim($_GET['kw']);
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('shop_name', $kw);
		$shops = Shop::model()->with('user')->findAll($criteria);
		$this->render('searchforbind', array(
			'shops' => $shops
		));
	}
	
	/**
	 * 查看用户详情
	 */
	public function actionInfo($id)
	{
	    $shop_id = (int)$id;
	    $shop_info = Shop::model()->findByPk($shop_id);
		$this->render('info', array('shop_info'=>$shop_info));
	}
	
	/**
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
	public function actionCategoryDelete($id)
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

	/**
	 * 用户推荐的商铺信息处理
	 */
	public function actionShopsuggest()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		$criteria->order = 'id desc';
		
		$pages = new CPagination(ShopSuggest::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		
		$suggest = ShopSuggest::model()->findAll($criteria);
		
		$this->render('shopsuggest', array(
			'suggest' => $suggest,
			'pages' => $pages
		));
	}
	
	public function actionShopsuggestdel($id)
	{
		$id = intval($id);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$_SESSION['manage_city_id']));
		ShopSuggest::model()->deleteByPk($id, $criteria);
		$url = app()->request->urlReferrer;
		$this->redirect($url);
	}
	
	public function actionShopsuggestsave()
	{
		$id = intval($_POST['id']);
		$value = trim(strip_tags($_POST['v']));
		$model = ShopSuggest::model()->findByPk($id);
		if($model) {
			$model->remark = $value;
			if($model->save()) {
				echo STATE_ENABLED;
				exit;
			}
		}
	}
	
	public function actionStatistics()
	{
		
	}
	
	/**
     * 设置某一个商铺的SESSION
     */
    public function actionSetSession($id)
    {
    	$shopId = intval($id);
    	$shop = Shop::model()->findByPk($shopId);
    	if($shop) {
    		$session = app()->session;
        	$session['shop'] = $shop;
        	$this->redirect(url('shopcp/shop/profile'));
    	}
		echo '不存在此商铺!';
    }
    
    /**
     * 商铺评论
     */
    public function actionShopcomment()
    {
    	$criteria = new CDbCriteria();
    	
    	$pages = new CPagination(ShopComment::model()->count($criteria));
		$pages->pageSize = 13;
		$pages->applyLimit($criteria);
		
		$criteria->order = "t.create_time desc";
    	$shopcomment = ShopComment::model()->with('shop', 'user')->findAll($criteria);
    	$this->render('shopcomment', array(
    		'shopcomment' => $shopcomment,
    		'pages' => $pages
    	));
    }
    
    public function actionGoodsratelog()
    {
    	$criteria = new CDbCriteria();
    	
    	$pages = new CPagination(GoodsRateLog::model()->count($criteria));
    	$pages->pageSize = 20;
    	$pages->applyLimit($criteria);
    	
    	$criteria->order = "t.create_time desc";
    	$goodsrate = GoodsRateLog::model()->with('user', 'goods')->findAll($criteria);
    	
    	$this->render('goodsratelog', array(
    		'goodsrate' => $goodsrate,
    		'pages' => $pages
    	));
    }
    
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('unverify', 'profile', 'approve', 'delete', 'setSession'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array('unverify', 'profile', 'approve', 'delete', 'setSession'),
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