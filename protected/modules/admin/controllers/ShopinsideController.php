<?php

class ShopinsideController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(ShopInside::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	public function actionCreate($id=0)
	{
		$id = (int)$id;
		if($id) {
			$shopinside = ShopInside::model()->findByPk($id);
		} else {
			$shopinside = new ShopInside();
		}
		
		if(app()->request->isPostRequest && isset($_POST['ShopInside'])) {
			$shopinside->attributes = $_POST['ShopInside'];
			if(substr($shopinside->map_region, -1) == Shop::SEPARATOR_REGION_POINT) {
				$shopinside->map_region = substr($shopinside->map_region, 0, -1);
			}
			if($shopinside->save()) {
				if(!$id) {
					$this->redirect(url('admin/shopinside/list'));
				}
			}
		}
	    
		if(!$shopinside->category_id) {
			$shopinside->category_id = ShopCategory::CATEGORY_FOOD;
		}
		$this->render('create', array(
			'shopinside'=>$shopinside,
			'district' => District::getDistrictArray($_SESSION['manage_city_id']),
		));
	}
	
	public function actionPost($id)
	{
		$id = intval($id);
		$shopinside = ShopInside::model()->findByPk($id);
		if($shopinside->user_id != user()->id) {
			$this->redirect(url('admin/shopinside/list'));
		} else {
			$user = new User();
			$shop = new Shop('adminPost');
			if(app()->request->isPostRequest && isset($_POST['User'])) {
				$user->attributes = $_POST['User'];
				$user->clear_password = $user->password;
				if($user->save()) {
					$shop->attributes = $shopinside->attributes;
					$shop->user_id = $user->id;
					if($shop->save()) {
						$shopinside->state = STATE_ENABLED;
						$shopinside->save();
						$this->redirect(url('admin/shopinside/list'));
					}
				}
			}
			if(empty($user->username)) {
				$user->username = $shopinside->shop_name;
			}
			$user->password = $user->clear_password;
			$this->render('post', array(
				'shopinside' => $shopinside,
				'user' => $user,
				'shop' => $shop
			));
		}
	}
	
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 't.state asc, t.create_time desc';
		$criteria->addColumnCondition(array('t.user_id'=>user()->id));
		$pages = $this->_getPages($criteria);
		$shopinside = ShopInside::model()->with('user')->findAll($criteria);
		
		$model = new ShopInside();
		$this->render('list', array(
			'pages' => $pages,
			'shopinside' => $shopinside,
			'model' => $model
		));
	}
	
	public function actionDelete($id)
	{
	    $id = (int)$id;
	    $shop = ShopInside::model()->findByPk($id);
	    if($shop->user_id == user()->id) {
			if(!$shop->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop));
			} else {
				AdminLog::saveManageLog('删除待审核商铺(' . $shop->shop_name . ')');
			}
	    }
		$this->redirect(url('admin/shopinside/list'));
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('create'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array('create'),
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