<?php

class QuestionController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(ShopComment::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 留言列表
	 * Enter description here ...
	 */
	public function actionList()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->order = 'id desc';
	    
	    $pages = $this->_getPages($condition);
	    $shopComment = ShopComment::model()->findAll($condition);
	    
	    $this->pageTitle = '全部留言';
	    $this->render('list', array('shopComment'=>$shopComment, 'pages' => $pages));
	}

	/**
	 * 未回复
	 * Enter description here ...
	 */
	public function actionNoreply()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addCondition('t.reply_time=""');
	    $condition->order = 'id desc';
	    
	    $pages = $this->_getPages($condition);
	    $shopComment = ShopComment::model()->findAll($condition);
	    
	    $this->pageTitle = '未回复留言';
	    $this->render('noreply', array('noreply'=>$shopComment, 'pages' => $pages));
	}
	
	/**
	 * 回复用户留言
	 */
	public function actionReply($qid)
	{
	    $qid = (int)$qid;
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$commonreply = ShopCommonReply::model()->findAll($condition);
	    
	    $comment = ShopComment::model()->findByPk($qid, $condition);
		if (app()->request->isPostRequest && isset($_POST['ShopComment'])) {
			$comment->reply = $_POST['ShopComment']['reply'];
			$comment->reply_time = $_SERVER['REQUEST_TIME'];
			$comment->reply_ip = CdcBetaTools::getClientIp();
//			$comment->setScenario('reply');
			if (!$comment->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($comment));
				$data = array(
			    	'回复留言' => array(
			    		'id' => 'reply',
			    		'content' => $this->renderPartial('reply', array('commonreply' => $commonreply, 'comment'=>$comment), true)
			    	),
			    );
			    
				$this->render('/public/tab', array('tabs'=>$data));
			} else
				$this->redirect(url('shopcp/question/noreply'));
			
		} else {
		    $data = array(
		    	'回复留言' => array(
		    		'id' => 'reply',
		    		'content' => $this->renderPartial('reply', array('commonreply' => $commonreply, 'comment'=>$comment), true)
		    	),
		    );
		    
	    	$this->pageTitle = '回复留言';
			$this->render('/public/tab', array('tabs'=>$data));
		}
	}
	
	/**
	 * 常用回复
	 * Enter description here ...
	 */
	public function actionCommonReply()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$commonreply = ShopCommonReply::model()->findAll($condition);
	    
	    $this->pageTitle = '常用回复';
	    $this->render('commonreply', array('commonreply' => $commonreply));
	}

	/**
	 * 删除留言
	 * @param integer $qaid
	 */
	public function actionDelete($id)
	{
		$id = (int)$id;
		if (isset($id)) {
		    $commonReply_id = (int)$id;
			$condition = new CDbCriteria();
		    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$shopCommonReply = ShopCommonReply::model()->findByPk($commonReply_id, $condition);
			if(!$shopCommonReply->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shopCommonReply));
			}
		}
		$this->redirect(url('shopcp/question/commonreply'));
	}
	
	 /**
     * 添加常用回复
     * @param integer $goodsid 商品ID，编辑商品使用，默认为0，表示是添加商品
     */
	public function actionCreate()
	{
		if(app()->request->isPostRequest && isset($_POST)) {
			$ShopCommon = new ShopCommonReply();
			$ShopCommon->attributes = $_POST['ShopCommonReply'];
			$ShopCommon->shop_id = $_SESSION['shop']->id;
			if(!$ShopCommon->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($ShopCommon));
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
				$commonreply = ShopCommonReply::model()->findAll($condition);
			    
				$this->render('commonreply', array('commonreply' => $commonreply, 'ShopCommon'=>$ShopCommon));
			}else{
				$this->redirect(url('shopcp/question/commonreply'));
			}
		}else{
			$this->redirect(url('shopcp/question/commonreply'));
		}
	}
	
	public function filters()
	{
	    return array(
	    	'postOnly + create',
	    );
	}
}