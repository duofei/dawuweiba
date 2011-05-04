<?php

class QuestionController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(ShopComment::model()->count($criteria));
		$pages->pageSize = 8;
		$pages->applyLimit($criteria);
		return $pages;
	}

	private function _getCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.user_id' => user()->id));
		$criteria->order = 't.id desc';
		$criteria->limit = 8;
		return $criteria;
	}
	
	/**
	 * 已回复留言列表
	 */
	public function actionList()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的留言' => url('my/question/list'),
	        '已回复留言'
	    );
		$criteria = $this->_getCriteria();
		$criteria->addCondition('t.reply!=""');
		$pages = $this->_getPages($criteria);
		$list = ShopComment::model()->with('shop','order')->findAll($criteria);
		$this->pageTitle = '已回复留言';
		$this->render('list', array('data' => $list, 'pages' => $pages));
	}
	
	/**
	 * 全部留言列表
	 */
	public function actionAlllist()
	{
		$this->breadcrumbs = array(
			'个人中心' => url('my'),
	        '我的留言' => url('my/question/list'),
	        '全部留言'
	    );
		$criteria = $this->_getCriteria();
		$pages = $this->_getPages($criteria);
		$list = ShopComment::model()->with('shop','order')->findAll($criteria);
		$this->pageTitle = '全部留言';
		$this->render('alllist', array('data' => $list, 'pages' => $pages));
	}
	
	/**
     * 发表留言
     */
	public function actionCreate()
	{
		$order_id = intval($_POST['order_id']);
		$content = trim(strip_tags($_POST['content']));
		$order = Order::model()->findByPk($order_id);
		
		if($order->user_id != user()->id) {
			echo '非法操作';
			exit;
		}
		
		$shopcomment = new ShopComment('reminder');
		$shopcomment->shop_id = $order->shop_id;
		$shopcomment->order_id = $order_id;
		$shopcomment->content = $content;
		if(!$shopcomment->save()) {
			echo CHtml::errorSummary($shopcomment, null);
		}
		exit;
	}
	
	/**
	 * 删除一条留言
	 * @qaid integer 留言ID
	 */
	public function actionDelete($qaid)
	{
	    $qaid = (int)$qaid;
		$this->render('delete');
	}
	
	public function filters()
	{
	    return array(
	        'ajaxOnly + create',
	    	'postOnly + create',
	    );
	}
}