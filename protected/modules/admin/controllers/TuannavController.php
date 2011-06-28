<?php

class TuannavController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Tuannav::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 列表
	 */
    public function actionList()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$tuannav = Tuannav::model()->findAll($condition);
    	$list = 'list';
	    $this->render('list', array('tuannav'=>$tuannav, 'list'=>$list, 'pages'=>$pages));
    }

	/**
	 * 今日团购
	 */
    public function actionToday()
    {
    	$today = date('Y-m-d');
    	$today = strtotime($today);
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   	$condition->addCondition('create_time>='.$today);
	    $condition->order = 'id desc';
		$pages = $this->_getPages($condition);
    	$tuannav = Tuannav::model()->findAll($condition);
	    $this->render('list', array('tuannav'=>$tuannav, 'pages'=>$pages));
    }

	/**
	 * 搜索团购
	 */
    public function actionSearch()
    {
    	foreach ((array)$_GET['Tuannav'] as $key=>$val){
			$tuannav_get[$key] = strip_tags(trim($val));
		}
    	if($tuannav_get) {
			$start_time = strtotime($tuannav_get['create_time_start']);
			$end_time = strtotime($tuannav_get['create_time_end']);
			$end_time = strtotime('next Day', $end_time);
	    	$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
    	
		    if ($tuannav_get['create_time_start']) {
		    	$condition->addCondition('create_time>=' . $start_time);
		    }
		    if ($tuannav_get['create_time_end']) {
		    	$condition->addCondition('create_time<=' . $end_time);
		    }
		    $condition->order = 'id desc';
			$pages = $this->_getPages($condition);
	    	$tuannav = Tuannav::model()->findAll($condition);
    		$list = 'list';
		    $this->render('list', array('tuannav'=>$tuannav, 'tuannav_get'=>$tuannav_get, 'list'=>$list, 'pages'=>$pages));
    	}
    }
    
	/**
	 * 编辑
	 */
	public function actionEdit($id = 0)
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
    	$category = TuanCategory::model()->findAll($condition);
    	
    	$tuandata = TuanData::getTuanDataOfCity($_SESSION['manage_city_id']);
		if(app()->request->isPostRequest && isset($_POST['Tuannav'])) {
			$tuannav_post = $_POST['Tuannav'];
			$tuannav = Tuannav::model()->findByPk($_POST['id']);
			$tuannav->attributes = $tuannav_post;
			if($_POST['editTime']) {
				$d = intval($_POST['d']);
				$h = intval($_POST['h']);
				$i = intval($_POST['i']);
				$tuannav->effective_time = date(param('formatDateTime'), (time() + $i*60 + $h*3600 + $d*3600*24));
			}
			if (!$tuannav->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				$this->render('edit', array('tuannav' => $tuannav, 'category'=>$category, 'tuandata'=>$tuandata));
				exit;
			} else {
				AdminLog::saveManageLog('编辑团购(' . $tuannav->title . ')信息');
			}
			$referer = $_POST['referer'];
			if($referer) {
				$this->redirect($referer);
			} else {
				$this->redirect(url('admin/tuannav/list'));
			}
		}
		$id = (int)$id;
		if($id) {
		    $tuannav = Tuannav::model()->findByPk($id);
		    $tuannav->state = 1;
			$this->render('edit', array('tuannav' => $tuannav, 'category'=>$category, 'tuandata'=>$tuandata));
		}
	}
	
	/**
	 * 添加
	 */
	public function actionCreate()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('state' => STATE_ENABLED));
    	$category = TuanCategory::model()->findAll($condition);
    	
    	$tuandata = TuanData::getTuanDataOfCity($_SESSION['manage_city_id']);
    	
		if(app()->request->isPostRequest && isset($_POST['Tuannav'])) {
			$tuannav_post = $_POST['Tuannav'];
			$tuannav = new Tuannav();
			$tuannav->attributes = $tuannav_post;
			$tuannav->city_id = $_SESSION['manage_city_id'];
			$d = intval($_POST['d']);
			$h = intval($_POST['h']);
			$i = intval($_POST['i']);
			$tuannav->effective_time = date(param('formatDateTime'), (time() + $i*60 + $h*3600 + $d*3600*24));
			if (!$tuannav->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				$this->render('create', array('tuannav_post' => $tuannav_post, 'category'=>$category, 'tuandata'=>$tuandata, 'tuannav'=>$tuannav));
				exit;
			}else{
				AdminLog::saveManageLog('添加团购(' . $tuannav->title . ')信息');
			}
			$this->redirect(url('admin/tuannav/list'));
		}
		$tuannav_post['state'] = 1;
	    $this->render('create', array('category'=>$category, 'tuandata'=>$tuandata, 'tuannav_post'=>$tuannav_post));
	}
    
	/**
	 * 查看
	 */
	public function actionInfo($id = 0)
	{
		$id = (int)$id;
		if($id) {
		    $tuannav = Tuannav::model()->findByPk($id);
			$this->render('info', array('tuannav' => $tuannav));
		}
	}

	/**
	 * 删除
	 */
	public function actionDelete($id = 0)
	{
	    $tuan_id = (int)$id;
		if ($tuan_id) {
			$tuannav = Tuannav::model()->findByPk($tuan_id);
			if (!$tuannav->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
			}else{
				AdminLog::saveManageLog('删除团购(' . $tuannav->title . ')信息');
			}
		}
		$this->redirect(url('admin/tuannav/list'));
	}

	/**
	 * 管理评论
	 */
	public function actionComment($id = 0)
	{
		$id = (int)$id;
		if($id) {
		    $tuannav = Tuannav::model()->findByPk($id);
		    
		    $condition = new CDbCriteria();
	   		$condition->addCondition('tuan_id='.$id);
		    $tuanComment = TuanComment::model()->findAll($condition);
			$this->render('comment', array('tuannav' => $tuannav, 'tuanComment'=>$tuanComment));
		}
	}
	
	/**
	 * 删除评论
	 */
	public function actionCommentDelete($id = 0)
	{
	    $id = (int)$id;
		if ($id) {
			$tuanComment = TuanComment::model()->findByPk($id);
			if (!$tuanComment->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuanComment));
			}else{
				AdminLog::saveManageLog('删除团购评论(' . $tuanComment->content . ')信息');
			}
		}
		$this->redirect(url('admin/tuannav/comment',array('id'=>$tuanComment->tuan_id)));
	}
	
	/**
	 * 团购网管理
	 */
	public function actionTuanData()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'orderid desc';
	    $pages = new CPagination(TuanData::model()->count($condition));
		$pages->pageSize = 20;
		$pages->applyLimit($condition);
    	$tuandata = TuanData::model()->findAll($condition);
	    $this->render('tuandata', array('tuandata'=>$tuandata, 'pages'=>$pages));
	}
	
	/**
	 * 添加团购网站
	 */
	public function actionTuanCreate()
	{
		if(app()->request->isPostRequest && isset($_POST['Tuandata'])) {
			$tuandata_post = $_POST['Tuandata'];
			$tuandata = new TuanData();
			$tuandata->attributes = $tuandata_post;
			$tuandata->intro = $tuandata_post['intro'];
			$tuandata->city_id = $_SESSION['manage_city_id'];
			if (!$tuandata->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuandata));
				$this->render('tuancreate', array('tuandata_post' => $tuandata_post));
				exit;
			}else{
				AdminLog::saveManageLog('添加团购网站(' . $tuandata->name . ')信息');
			}
			$this->redirect(url('admin/tuannav/tuandata'));
		}
	    $this->render('tuancreate', array('tuandata_post'=>$tuandata_post));
	}

	/**
	 * 删除团购网站
	 */
	public function actionTuanDelete($id)
	{
	    $id = (int)$id;
		if ($id) {
			$tuandata = TuanData::model()->findByPk($id);
			if (!$tuandata->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuandata));
			}else{
				AdminLog::saveManageLog('删除团购网站(' . $tuandata->name . ')信息');
			}
		}
		$this->redirect(url('admin/tuannav/tuandata'));
	}
    
	/**
	 * 编辑团购网站
	 */
	public function actionTuanEdit($id = 0)
	{
		if(app()->request->isPostRequest && isset($_POST['Tuandata'])) {
			$tuandata_post = $_POST['Tuandata'];
			$tuandata = TuanData::model()->findByPk($_POST['id']);
			$tuandata->attributes = $tuandata_post;
			$tuandata->intro = $tuandata_post['intro'];
			if (!$tuandata->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuandata));
				$this->render('tuanedit', array('tuandata' => $tuandata));
				exit;
			}else{
				AdminLog::saveManageLog('编辑团购网站(' . $tuandata->name . ')信息');
			}
			$this->redirect(url('admin/tuannav/tuandata'));
		}
		$id = (int)$id;
		if($id) {
		    $tuandata = TuanData::model()->findByPk($id);
			$this->render('tuanedit', array('tuandata' => $tuandata));
		}
	}
	
	/**
	 * 二手交易管理
	 */
	public function actionTuanSecond()
	{
		$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'trade_sort asc';
		$pages = new CPagination(TuanSecondHand::model()->count($condition));
		$pages->pageSize = 20;
		$pages->applyLimit($condition);
    	$tuansecond = TuanSecondHand::model()->findAll($condition);
	    $this->render('tuansecond', array('tuansecond'=>$tuansecond, 'pages'=>$pages));
	}

	/**
	 * 删除二手交易
	 */
	public function actionSecondDelete($id)
	{
	    $id = (int)$id;
		if ($id) {
			$tuansecond = TuanSecondHand::model()->findByPk($id);
			if (!$tuansecond->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuansecond));
			}else{
				AdminLog::saveManageLog('删除二手交易(' . $tuansecond->title . ')信息');
			}
		}
		$this->redirect(url('admin/tuannav/tuansecond'));
	}
	
	/**
	 * 举报
	 */
    public function actionReport()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'id desc';
		$pages = new CPagination(TuanReport::model()->count($condition));
		$pages->pageSize = 20;
		$pages->applyLimit($condition);
    	$tuanreport = TuanReport::model()->findAll($condition);
	    $this->render('report', array('tuanreport'=>$tuanreport, 'pages'=>$pages));
    }
    
    /**
     * 用户推荐
     * @see CController::accessRules()
     */
    public function actionPost()
    {
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	    $condition->order = 'state asc, nums desc, id desc';
		$pages = new CPagination(TuanRecommend::model()->count($condition));
		$pages->pageSize = 20;
		$pages->applyLimit($condition);
    	$tuanRecommend = TuanRecommend::model()->findAll($condition);
	    $this->render('post', array('tuanRecommend'=>$tuanRecommend, 'pages'=>$pages));
    }

	/**
	 * 删除
	 */
	public function actionRecommendDelete($id = 0)
	{
	    $id = (int)$id;
		if ($id) {
			$condition = new CDbCriteria();
	   		$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   		$tuanRecommend = TuanRecommend::model()->deleteByPk($id, $condition);
			if (!$tuanRecommend) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuanRecommend));
			}else{
				AdminLog::saveManageLog('删除推荐团购网址(' . $tuanRecommend->url . ')信息');
			}
		}
		$this->redirect(url('admin/tuannav/post'));
	}
	
	/**
	 * 状态操作
	 */
	public function actionRecommendState($id = '', $state = 0)
	{
		$id = (int)$id;
		if ($id){
		    $condition = new CDbCriteria();
	   		$condition->addColumnCondition(array('city_id' => $_SESSION['manage_city_id']));
	   		$tuanRecommend = TuanRecommend::model()->findByPk($id, $condition);
	   		$tuanRecommend->state = (int)$state;
			if(!$tuanRecommend->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuanRecommend));
			} else {
				AdminLog::saveManageLog('更改用户推荐网址(' . $tuanRecommend->url . ')状态');
			}
			$this->redirect(url('admin/tuannav/post'));
		}
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'actions' => array('edit', 'create', 'delete', 'commentDelete', 'tuanCreate', 'tuanDelete', 'tuanEdit', 'secondDelete'),
	            'roles' => array('Editor'),
	        ),
	        array('deny',
	            'actions' => array('edit', 'create', 'delete', 'commentDelete', 'tuanCreate', 'tuanDelete', 'tuanEdit', 'secondDelete'),
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
?>