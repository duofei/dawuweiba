<?php

class CorrectionController extends Controller
{
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id desc';
		$correction = Correction::model()->with('user')->findAll($criteria);
		$this->render('list', array(
			'correction' => $correction,
			'pages' => $pages
		));
	}
	
	/**
	 * 分页
	 */
	private function _getPages($criteria)
	{
		$pages = new CPagination(Correction::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}

}