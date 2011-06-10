<?php

class FeedbackController extends Controller
{
    /**
     * 留言列表页面
     */
	public function actionindex()
	{
	    $criteria = new CDbCriteria();
	    $criteria->order = 't.id desc';
	    $criteria->limit = 20;
	    $criteria->addColumnCondition(array('post_id'=>0));
	    $pages = new CPagination(Feedback::model()->count($criteria));
	    $pages->pageSize = 20;
		$pages->applyLimit($criteria);
		$feedback = Feedback::model()->with('user', 'reply')->findAll($criteria);
		 
		/*
	     * 设置面包屑导航
	     */
	    $this->breadcrumbs = array(
    		'反馈留言' => url('feedback'),
	    );
	    
	    $model = new Feedback();
	    
	    $this->pageTitle = '反馈留言';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    $this->render('index', array(
		    'pages' => $pages,
		    'feedback' => $feedback,
		    'model' => $model,
		));
	}

	/**
	 * 回复留言
	 */
	public function actionReply($id=0)
	{
		if(user()->isGuest) {
			$this->redirect(url('feedback'));
		}
		
		$feedback = Feedback::model()->with('user', 'reply')->findByPk(intval($id));
		
		/*
	     * 设置面包屑导航
	     */
	    $this->breadcrumbs = array(
    		'反馈留言' => url('feedback'),
	    	'发表回复' => url('feedback/reply', array('id'=>$id))
	    );
	    
		$model = new Feedback();
		$model->post_id = $id;
		
		$this->pageTitle = '回复留言';
		$this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('reply', array(
			'feedback' => $feedback,
			'model' => $model,
		));
	}

	/**
	 * 发表留言
	 */
	public function actionCreate()
	{
		if(user()->isGuest) {
			$this->redirect(url('feedback'));
		}
		
		$feedback = new Feedback();
		
		if (app()->request->isPostRequest && isset($_POST['Feedback'])) {
			$post = CdcBetaTools::filterPostData(array('content', 'post_id', 'validateCode'), $_POST['Feedback']);
			$feedback->attributes = $post;
			$post_id = intval($_POST['Feedback']['post_id']);
			
			if($feedback->save()) {
				if($post_id > 0) {
					$this->redirect(url('feedback/reply', array('id'=>$post_id)));
				} else {
					$this->redirect(url('feedback'));
				}
			}
		}
		
		/*
	     * 设置面包屑导航
	     */
		$this->breadcrumbs['反馈留言'] = url('feedback');
		if($post_id > 0) {
			$this->breadcrumbs['发表回复'] = url('feedback/reply', array('id'=>$post_id));
		} else {
			$this->breadcrumbs['发表留言'] = url('feedback');
		}
		
		$errorSummary = CHtml::errorSummary($feedback);
		
		$this->pageTitle = '我要留言';
		$this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('create', array('feedback' => $feedback, 'errorSummary' => $errorSummary));
	}
	
    /*public function filters()
	{
	    return array(
	        array(
	        	'COutputCache + index',
	            'duration' => 60,
	            'varyByParam' => array('f'),
	            'cacheID' => 'fileCache',
	        ),
	    );
	}*/

}