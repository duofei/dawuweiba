<?php

class TuannavController extends Controller
{
	/**
	 * 二手交易管理
	 */
	public function actionTuanSecond()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '团购管理' => url('my/tuannav/favorite'),
	        '发布的二手'
	    );
		$condition = new CDbCriteria();
	   	$condition->addCondition('user_id='.user()->id);
	    $condition->order = 'trade_sort asc';
		$pages = new CPagination(TuanSecondHand::model()->count($condition));
		$pages->pageSize = 20;
		$pages->applyLimit($condition);
    	$tuansecond = TuanSecondHand::model()->findAll($condition);
	    $this->render('tuansecond', array('tuansecond'=>$tuansecond, 'pages'=>$pages));
	}

	/**
	 * 成交二手交易
	 */
	public function actionSecondState($id)
	{
		if ($id) {
		    $id = (int)$id;
			$condition = new CDbCriteria();
			$condition->addColumnCondition(array('user_id' => user()->id));
			$tuansecond = TuanSecondHand::model()->findByPk($id, $condition);
			$tuansecond->state = 1;
			if (!$tuansecond->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuansecond));
			}
			$this->redirect('/my/tuannav/tuansecond');
		}
		$this->redirect('/my/tuannav/tuansecond');
	}
	
	/**
     * 团购收藏
     */
	public function actionFavorite()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '团购管理' => url('my/tuannav/favorite'),
	        '团购收藏'
	    );
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id' => user()->id));
		$criteria->order = 'id desc';
		$pages = new CPagination(UserTuanFavorite::model()->count($criteria));
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$tuanfavorite = UserTuanFavorite::model()->findAll($criteria);
		$this->pageTitle = '团购收藏';
		$this->render('favorite', array('tuanfavorite' => $tuanfavorite, 'pages' => $pages));
	}
	
	/**
	 * 删除一条收藏记录
	 * @param integer $fid 要删除的收藏记录的ID号
	 * @param string $type 收藏分类，shop/goods
	 */
	public function actionFavoriteDelete($fid, $type)
	{
		$fid = (int)$fid;
		$condition = new CDbCriteria();
		$condition->addColumnCondition(array('user_id' => user()->id));
		if($type=='tuan') {
			$tuanfavorite = UserTuanFavorite::model()->findByPk($fid, $condition);
			if ($tuanfavorite->delete()) {
				$tuannav = Tuannav::model()->findByPk($tuanfavorite->tuan_id);
				$tuannav->favorite_num = $tuannav->favorite_num-1;
				if (!$tuannav->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				}
			}else {
				user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
			}
			$this->redirect('/my/tuannav/favorite');
		}
		$this->redirect('/my/tuannav/favorite');
	}
	
}
?>