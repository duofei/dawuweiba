<?php

class PromotionController extends Controller
{
	/**
	 * 优惠信息列表
	 */
	public function actionList($type = 0)
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->order = 'id desc';
	    
	    $promotion = Promotion::model()->findAll($condition);
	    
	    $this->pageTitle = '优惠信息管理';
		$data = array(
	    	'优惠信息列表' => array(
	    		'id' => 'list',
	    		'content' => $this->renderPartial('list', array('promotion' => $promotion), true)
	    	),
	    	
	    	'优惠信息添加' => array(
	    		'id' => 'create',
	    		'content' => $this->renderPartial('create', array(), true)
	    	),
	    );
	    $type = (int)$type;
	    
		$this->render('/public/tab', array('tabs'=>$data, 'selected'=>$type));
	}
	
    /**
     * 添加优惠信息
     * @param integer $pid 优惠信息ID，默认值为0，表示是添加，非0为编辑
     */
	public function actionCreate()
	{
	    if(app()->request->isPostRequest && isset($_POST)) {
			$promotionsave = new Promotion();
			$promotionsave->shop_id = $_SESSION['shop']->id;
			$promotionsave->end_time = strtotime($_POST['end_time']);
			$promotionsave->content = $_POST['content'];
			if (!$promotionsave->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($promotionsave));
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
				$condition->order = 'id desc';
			    
			    $promotion = Promotion::model()->findAll($condition);
			    
				$data = array(
			    	'优惠信息列表' => array(
			    		'id' => 'list',
			    		'content' => $this->renderPartial('list', array('promotion' => $promotion), true)
			    	),
			    	
			    	'优惠信息添加' => array(
			    		'id' => 'create',
			    		'content' => $this->renderPartial('create', array('promotionsave'=>$promotionsave), true)
			    	),
			    );
			    
				$this->render('/public/tab', array('tabs'=>$data, 'selected'=>'1'));
			}
		}
		
		$this->redirect(url('shopcp/promotion/list'));
	}
	
	/**
     * 添加优惠信息
     * @param integer $pid 优惠信息ID，默认值为0，表示是添加，非0为编辑
     */
	public function actionEdit($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$promotion = Promotion::model()->findByPk($_POST['id'], $condition);
			$promotion->attributes = $_POST['Promotion'];
			$promotion->end_time = strtotime($_POST['Promotion']['end_time']);
			if (!$promotion->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($promotion));
				$promotion->end_time = $_POST['Promotion']['end_time'];
				
	    		$this->pageTitle = '优惠信息修改';
				$data = array(
			    	'优惠信息修改' => array(
			    		'id' => 'edit',
			    		'content' => $this->renderPartial('edit', array('promotion' => $promotion), true)
			    	),
			    );
			    
				$this->render('/public/tab', array('tabs'=>$data));
			}else{
				$this->redirect('list');
			}
		}
		
    	$id = (int)$id;
	    if($id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$promotion = Promotion::model()->findByPk($id, $condition);
			$promotion->end_time = $promotion->endDateText;
			$data = array(
		    	'优惠信息修改' => array(
		    		'id' => 'edit',
		    		'content' => $this->renderPartial('edit', array('promotion' => $promotion), true)
		    	),
		    );
		    
			$this->render('/public/tab', array('tabs'=>$data));
		}
	}
	

	/**
	 * 删除优惠信息
	 * @param integer $pid 优惠信息ID
	 */
	public function actionDelete($id = 0)
	{
    	$id = (int)$id;
	    if ($id) {
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$promotion = Promotion::model()->findByPk($id, $condition);
			if (!$promotion->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($promotion));
			}
		}
		$this->redirect(url('shopcp/promotion/list'));
	}

}