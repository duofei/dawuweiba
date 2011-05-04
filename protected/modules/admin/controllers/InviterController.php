<?php

class InviterController extends Controller
{
	
	/**
	 * 分页
	 */
	private function _getPages($criteria)
	{
		$pages = new CPagination(UserInviter::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id desc';
		$list = UserInviter::model()->with('user', 'invitee')->findAll($criteria);
		$this->render('list', array(
			'list' => $list,
			'pages' => $pages
		));
	}
	
}