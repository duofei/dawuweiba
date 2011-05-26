<?php

class MessageController extends Controller
{
    /**
     * 系统消息列表
     */
	public function actionList()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '短消息'
	    );
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('touid'=>user()->id));
		$pages = new CPagination(Message::model()->count($condition));
		$pages->pageSize = 15;
		$pages->applyLimit($condition);
		$condition->order = 'id desc';
		$message = Message::model()->findAll($condition);
		$this->pageTitle = '短消息';
		$this->render('list', array(
			'message' => $message,
			'pages' => $pages
		));
	}

	/**
	 * 显示某一条消息
	 * @param integer $msgid 短消息ID
	 */
	public function actionShow($msgid)
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '短消息'
	    );
	    
	    $msgid = (int)$msgid;
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('touid'=>user()->id));
		$message = Message::model()->findByPk($msgid, $criteria);
		
		if($message) {
			$message->is_read = STATE_ENABLED;
			$message->save();
		}
		$this->render('show', array('message'=>$message));
	}
}