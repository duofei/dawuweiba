<?php

class CorrectionController extends Controller
{
	/**
	 * 用户纠错
	 */
	public function actionCreate()
	{
		/*
	     * 设置面包屑导航
	     */
		$this->breadcrumbs = array(
			'我要纠错' => url('correction/create')
		);
		
		$this->pageTitle = '我要纠错';
		$this->setPageKeyWords();
        $this->setPageDescription();
        
		$correction = new Correction();
		$correction->source = app()->request->urlReferrer;
		
		if (app()->request->isPostRequest && isset($_POST['Correction'])) {
			$post = CdcBetaTools::filterPostData(array('content','source','validateCode'), $_POST['Correction']);
			$correction->attributes = $post;
			if ($correction->save()) {
				$success = '您提交的纠错信息我们已收到，非常感谢！';
			}
			$errorSummary = CHtml::errorSummary($correction);
		}
		
		$this->render('create', array('correction'=>$correction, 'errorSummary' => $errorSummary, 'success'=>$success));
	}

}