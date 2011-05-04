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
	        '系统消息'
	    );
		$condition = new CDbCriteria();
		$condition->order = 'id desc';
		$message = Message::model()->findAll($condition);
		$this->pageTitle = '系统消息';
		$this->render('list', array('message'=>$message));
	}

	/**
	 * 显示某一条消息
	 * @param integer $msgid 短消息ID
	 */
	public function actionShow($msgid)
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '系统消息'
	    );
	    
	    $msgid = (int)$msgid;
		$message = Message::model()->findByPk($msgid);
		
		$this->render('show', array('message'=>$message));
	}
}