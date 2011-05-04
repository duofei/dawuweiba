<?php

class AddressController extends Controller
{
	/**
	 * 我的收货地址列表
	 */
	public function actionList($id=0)
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的地址' => url('my/address/list'),
	        '地址列表'
	    );
		// 截入pager.css
		$path = Yii::getPathOfAlias('system.web.widgets.pagers');
		$url = app()->assetManager->publish($path) . '/';
		cs()->registerCssFile($url . 'pager.css', 'screen');
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('t.user_id' => user()->id));
		$condition->order = 't.id desc';
		$caption = "修改地址";
		if(!$id || !$model = UserAddress::model()->findByPk($id, $condition)) {
			$model = new UserAddress();
			$caption = "增加地址";
		}
		if(app()->request->isPostRequest && isset($_POST['UserAddress'])) {
			$post = CdcBetaTools::filterPostData(array('city_id', 'district_id', 'building_id', 'map_x', 'map_y', 'address', 'consignee', 'telphone', 'mobile', 'id'), $_POST['UserAddress']);
			$model->attributes = $post;
			if(!$model->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($model));
			}
		}
		// 所有地址信息
		$address = UserAddress::model()->with('city','district')->findAll($condition);
		// 城市和行政区域下拉列表
		$city = City::model()->findAll();
		$cityarray = array();
		$districtarray = array();
		if($model->city_id) {
			$city_id = $model->city_id;
		} else {
			$city_id = $this->city['id'];
		}
		foreach ($city as $row) {
			$cityarray[$row->id] = $row->name;
			if($row->id == $city_id) {
				foreach($row->districts as $r) {
					$districtarray[$r->id] = $r->name;
				}
			}
		}
		$this->pageTitle = '地址列表';
		$this->render('list', array('address' => $address, 'cityarray'=>$cityarray, 'districtarray'=>$districtarray, 'model'=>$model, 'caption'=>$caption));
	}

	/**
	 * 删除一条地址
	 */
	public function actionDelete()
	{
		$id = intval($_POST['id']);
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('user_id' => user()->id));
		if(!UserAddress::model()->deleteByPk($id, $condition)) {
			echo '删除失败!';
		}
		exit;
	}
	
	/**
	 * 设置默认地址
	 */
	public function actionSetDefault() {
		$id = intval($_GET['id']);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id' => user()->id));
		UserAddress::model()->updateAll(array('is_default'=>0), $criteria);
		$criteria->addCondition('id = ' . $id);
		UserAddress::model()->updateAll(array('is_default'=>1), $criteria);
		$this->redirect('/my/address/list');
	}
	
	public function filters()
	{
	    return array(
	        'ajaxOnly + delete',
	    	'postOnly + delete',
	    );
	}
}