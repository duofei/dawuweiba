<?php

class DeliveryController extends Controller
{
    /**
     * 送货人员列表
     */
	public function actionList($type = 0)
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->order = 'state desc';
	    $deliveryMan = DeliveryMan::model()->findAll($condition);
	    
	    $this->pageTitle = '配送员管理';
		$data = array(
	    	'送餐员列表' => array(
	    		'id' => 'list',
	    		'content' => $this->renderPartial('list', array('deliveryMan' => $deliveryMan), true)
	    	),
	    	
	    	'送餐员添加' => array(
	    		'id' => 'create',
	    		'content' => $this->renderPartial('create', array('deliveryMan' => $deliveryMan), true)
	    	),
	    );
	    $type = (int)$type;
		$this->render('/public/tab', array('tabs'=>$data, 'selected'=>$type));
	}

	/**
	 * 添加和编辑送货人员资料
	 * @param integer $manid 人员ID，编辑单个人员资料的时候用到，默认为0，表示添加新的送货人员
	 */
	public function actionCreate()
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$deliveryMan = new DeliveryMan();
			$deliveryMan->attributes = $_POST['Delivery'];
			$deliveryMan->shop_id = $_SESSION['shop']->id;
			if (!$deliveryMan->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($deliveryMan));
				$this->redirect(url('shopcp/delivery/list', array('type'=>'1')));
			}
		}
		
		$this->redirect(url('shopcp/delivery/list'));
	}
	
	/**
	 * 添加和编辑送货人员资料
	 * @param integer $manid 人员ID，编辑单个人员资料的时候用到，默认为0，表示添加新的送货人员
	 */
	public function actionEdit($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$deliveryMan = DeliveryMan::model()->findByPk($_POST['id'], $condition);
			$deliveryMan->attributes = $_POST['DeliveryMan'];
			if (!$deliveryMan->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($deliveryMan));
				$this->redirect(url('shopcp/delivery/edit', array('id'=>$_POST['id'])));
			}
			$this->redirect(url('shopcp/delivery/list'));
		}
		$id = (int)$id;
		if($id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$deliveryMan = DeliveryMan::model()->findByPk($id, $condition);
		    
	    	$this->pageTitle = '编辑配送员';
		    $data = array(
		    	'编辑配送员' => array(
		    		'id' => 'edit',
		    		'content' => $this->renderPartial('edit', array('deliveryMan' => $deliveryMan), true)
		    	),
		    );
			$this->render('/public/tab', array('tabs'=>$data));
		}
	}

	/**
	 * 删除一个人员信息
	 * @param integer $manid 人员ID号
	 */
	public function actionDelete($id = 0)
	{
		$delivery_id = (int)$id;
		if ($delivery_id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$deliveryMan = DeliveryMan::model()->findByPk($delivery_id, $condition);
			$deliveryMan->state = DeliveryMan::STATUS_DELETE;
			if (!$deliveryMan->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($deliveryMan));
			}
		}
		$this->redirect(url('shopcp/delivery/list'));
	}

	public function filters()
	{
	    return array(
	    	'postOnly + create',
	    );
	}
}