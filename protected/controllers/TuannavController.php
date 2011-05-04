<?php

class TuannavController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(Tuannav::model()->count($criteria));
		$pages->pageSize = 12;
		$pages->applyLimit($criteria);
		return $pages;
	}

	/**
	 * 列表
	 */
    public function actionList()
    {
    	if(app()->request->isPostRequest && isset($_POST)) {
    		$condition = new CDbCriteria();
    		$condition->addColumnCondition(array('city_id' => $this->city['id']));
			$condition->addColumnCondition(array('url' => $_POST['url']));
			$tuanrecommend = TuanRecommend::model()->find($condition);
			if ($tuanrecommend){
				$tuanrecommend->nums +=1;
			}else {
				$tuanrecommend = new TuanRecommend();
				$tuanrecommend->city_id = $this->city['id'];
				$tuanrecommend->url = $_POST['url'];
				$tuanrecommend->nums =1;
			}
			if (!$tuanrecommend->save()) {
			    $success = CHtml::errorSummary($tuanrecommend);
			}else {
				$success = '您的信息已提交成功！';
			}
		}
//		echo $success;exit;
    	$today = date('Y-m-d');
    	$category = TuanCategory::getTuanCategory();
    	$tuandata = TuanData::getTuanDataOfCity($this->city['id']);
    	$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
    	$condition = new CDbCriteria();
	   	$condition->addColumnCondition(array('t.city_id' => $this->city['id'], 'state'=>STATE_ENABLED));
	   	$condition->addCondition('effective_time >= \''.$today.'\'');
		$sort = new CSort('Tuannav');
	   	$sort->attributes = array(
	    	'buy_num'=> array(
	    		'label' => '流行度',
	    		'asc' => 't.buy_num asc',
	    		'desc' => 't.buy_num desc',
	    	),
	    	'group_price'=> array(
	    		'label' => '价格',
	    		'asc' => 't.group_price asc',
	    		'desc' => 't.group_price desc',
	    	),
	    	'discount'=> array(
	    		'label' => '折扣',
	    		'asc' => 't.discount asc',
	    		'desc' => 't.discount desc',
	    	),  't.favorite_nums', 't.rate_avg',
	    );
	   	$sort->applyOrder($condition);
	   	if (!$_GET['sort']){
	    	$condition->order = "FROM_UNIXTIME(t.create_time,'%Y%m%d') desc, tuandata.orderid desc";
	   	}
		$pages = $this->_getPages($condition);
    	$tuannav = Tuannav::model()->with('tuandata')->findAll($condition);
    	
    	$order = explode('.', trim(strip_tags($_GET['sort'])));
	    $sortclass[$order[0]] = 'checked' . $order[1];

    	$this->breadcrumbs = array(
    		'团购导航' => url('tuannav/list'),
    		'今日团购' => url('tuannav/list'),
    	);
    	$this->pageTitle = $this->city['name'] . $today . '团购导航';
    	$this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	$sites = implode(',', CHtml::listData($tuandata, 'id', 'name'));
    	$this->setPageKeyWords($this->pageTitle . ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航,' . $sites);
	    $this->render('list', array('sort'=>$sort, 'sortclass'=>$sortclass, 'tuannav'=>$tuannav, 'tuandata'=>$tuandata, 'category'=>$category, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'pages'=>$pages, 'success'=>$success));
    }

	/**
	 * 查看
	 */
	public function actionShow($id)
	{
    	$today = date('Y-m-d');
		$id = (int)$id;
	    $tuannav = Tuannav::model()->findByPk($id);
	    if (null === $tuannav) throw new CHttpException(500, '该团购信息不存在');
	    $tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
	    $tuanComment = TuanComment::getTuanComment($id);
    	$this->breadcrumbs = array(
			'团购导航' => url('tuannav/list'),
    		$tuannav->titleSub => url('tuannav/show', array('id'=>$tuannav->id)),
    	);
		$this->pageTitle = $tuannav->title . ',' . $this->city['name'] . '团购导航';
		$this->setPageDescription($this->pageTitle);
		$this->setPageKeyWords($this->city['name'] . '团购,' . $this->city['name'] . '团购导航');
		$this->render('show', array('tuannav' => $tuannav, 'tuanComment'=>$tuanComment, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond));
	}

	/**
	 * 留言添加
	 */
	public function actionComment()
	{
		if (user()->isGuest) {
			$this->redirect(url('site/login', array('referer' => app()->request->urlReferrer)));
    		exit(0);
    	}
    	$today = date('Y-m-d');
		if (app()->request->isPostRequest && isset($_POST['Comment'])){
			$id = (int)$_POST['id'];
			$tuannav = Tuannav::model()->findByPk($id);
			if (null === $tuannav) throw new CHttpException(500, '该团购信息不存在');
			$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);

			$comment = new TuanComment();
			$comment->tuan_id = $id;
			$comment->user_id = user()->id;
			$comment->validateCode = $_POST['validateCode'];
			$comment->content = $_POST['Comment']['content'];
			if (!$comment->save()){
				user()->setFlash('errorSummaryC',CHtml::errorSummary($comment));
				$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
			    $tuanComment = TuanComment::getTuanComment($id);
		    	$this->breadcrumbs = array(
	    			'团购导航' => url('tuannav/list'),
		    		$tuannav->titleSub => url('tuannav/show', array('id'=>$tuannav->id)),
		    	);
	    		$this->pageTitle = '发表评论出错';
				$this->render('show', array('tuannav' => $tuannav, 'tuanComment'=>$tuanComment, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond));
			} else {
				$tuannav->comment_nums = $tuannav->comment_nums+1;
				if (!$tuannav->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				}
				$this->redirect(url('tuannav/show', array('id'=>$id)));
			}
		}
	}

	public function actionSearch($category_id = 0, $day = '')
	{
        $category_id = (int)$category_id;
    	$day = strip_tags(trim($day));
    	$date = $day;
   		$day = strtotime($day);
	   	$today = date('Y-m-d');
    	$category = TuanCategory::getTuanCategory();
    	$tuandata = TuanData::getTuanDataOfCity($this->city['id']);
    	$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
    	$condition = new CDbCriteria();
    	if ($category_id) {
   			$condition->addColumnCondition(array('city_id' => $this->city['id']));
		   	$condition->addCondition('category_id = ' . $category_id);
			if ($day) {
				$condition->addCondition('create_time>='.$day);
			   	$condition->addCondition('create_time<='.strtotime('next Day', $day));
			}else{
		   		$condition->addCondition('effective_time >= \''.$today.'\'');
			}
    	} else{
    		if ($day) {
       			$condition->addColumnCondition(array('city_id' => $this->city['id']));
    		   	$condition->addCondition('create_time>='.$day);
    		   	$condition->addCondition('create_time<='.strtotime('next Day', $day));
    	   	}else {
    		   	$condition->addColumnCondition(array('city_id' => $this->city['id']));
    		   	$condition->addCondition('effective_time >= \''.$today.'\'');
    	   	}
    	}
		$sort = new CSort('Tuannav');
	   	$sort->attributes = array(
	    	'buy_num'=> array(
	    		'label' => '流行度',
	    		'asc' => 'buy_num asc',
	    		'desc' => 'buy_num desc',
	    	),
	    	'group_price'=> array(
	    		'label' => '价格',
	    		'asc' => 'group_price asc',
	    		'desc' => 'group_price desc',
	    	),
	    	'discount'=> array(
	    		'label' => '折扣',
	    		'asc' => 'discount asc',
	    		'desc' => 'discount desc',
	    	),  'favorite_nums', 'rate_avg',
	    );
	   	$sort->applyOrder($condition);
	   	if (!$_GET['sort']){
	    	$condition->order = 'id desc';
	   	}
	   	
    	$pages = $this->_getPages($condition);
    	$tuannav = Tuannav::model()->findAll($condition);
	    
    	$order = explode('.', trim(strip_tags($_GET['sort'])));
	    $sortclass[$order[0]] = 'checked' . $order[1];
    	
    	$this->breadcrumbs = array(
    		'团购导航' => url('tuannav/list'),
    		'历史团购'.$date => url('tuannav/search', array('day'=>$date)),
    	);

		$this->pageTitle = $this->city['name'] . $date . '团购导航';
		$this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	$sites = implode(',', CHtml::listData($tuandata, 'id', 'name'));
    	$this->setPageKeyWords($this->pageTitle. ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航,' . $sites);

    	$this->render('list', array('sort'=>$sort, 'sortclass'=>$sortclass, 'tuannav'=>$tuannav, 'tuandata'=>$tuandata, 'category'=>$category, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'pages'=>$pages, 'date'=>$date));
	}

	/**
	 * 团购网站详细信息
	 */
	public function actionInfo($source_id)
	{
    	$today = date('Y-m-d');
		$source_id = (int)$source_id;

		$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);

    	if ($source_id){
	    	$tuaninfo = TuanData::model()->findByPk($source_id);
			if (null === $tuaninfo) throw new CHttpException(500, '该团购网站信息不存在');
		    $condition = new CDbCriteria();
	   		$condition->addCondition('source_id='.$source_id);
	    	$condition->order = 'id desc';
	    	$tuannav = Tuannav::model()->findAll($condition);
	    	$tuannav_id = CHtml::listData($tuannav, 'id', 'id');

	    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);

	    	$condition = new CDbCriteria();
	    	$condition->addInCondition('tuan_id', $tuannav_id);
	    	$condition->order = 'id desc';
		    $pages = new CPagination(TuanComment::model()->count($condition));
			$pages->pageSize = 20;
			$pages->applyLimit($condition);
		    $tuanComment = TuanComment::model()->findAll($condition);
		    
	    	$this->breadcrumbs = array(
    			'团购导航' => url('tuannav/list'),
	    		$tuaninfo->name => url('tuannav/info', array('source_id'=>$tuaninfo->id)),
	    	);
			$this->pageTitle = $tuaninfo->name . '团购网_' . $this->city['name'] . '团购导航';
    	    $this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	    $this->setPageKeyWords($this->pageTitle . ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航');
		    $this->render('info', array('tuanComment'=>$tuanComment, 'tuaninfo'=>$tuaninfo, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'pages'=>$pages));
    	}
	}

	/**
	 * 团购网站详细信息-历史团购
	 */
	public function actionOld($source_id = 0)
	{
    	$today = date('Y-m-d');
		$source_id = (int)$source_id;
    	$category = TuanCategory::getTuanCategory();
    	$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
    	if ($source_id){
	    	$tuaninfo = TuanData::model()->findByPk($source_id);
			if (null === $tuaninfo) throw new CHttpException(500, '该团购网站信息不存在');
	    	$condition = new CDbCriteria();
	   		$condition->addCondition('source_id='.$source_id);
		    $condition->order = 'id desc';
			$pages = $this->_getPages($condition);
	    	$tuannav = Tuannav::model()->findAll($condition);
	    	
	    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
	    	
	    	$this->breadcrumbs = array(
    			'团购导航' => url('tuannav/list'),
	    		$tuaninfo->name => url('tuannav/info', array('source_id'=>$tuaninfo->id)),
	    	);
			$this->pageTitle = $tuaninfo->name . '团购网历史团购_' . $this->city['name'] . '团购导航';
    	    $this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	    $this->setPageKeyWords($this->pageTitle . ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航');
		    $this->render('old', array('tuannav'=>$tuannav, 'tuaninfo'=>$tuaninfo, 'category'=>$category, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'pages'=>$pages));
    	}
	}

	/**
	 * 二手信息
	 */
	public function actionSecond($sort='')
	{
    	$today = date('Y-m-d');
    	$sort = strip_tags(trim($sort));

    	$this->pageTitle = $this->city['name'] . '团购导航';
    	$category = TuanCategory::getTuanCategory();
    	$tuandata = TuanData::getTuanDataOfCity($this->city['id']);
    	$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
		$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
		if ($sort=='buy'){
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $this->city['id']));
		   	$condition->addCondition('trade_sort='.TuanSecondHand::TRADE_SORT_BUY);
		    $condition->order = 'id desc';
		    $pages = new CPagination(TuanSecondHand::model()->count($condition));
			$pages->pageSize = 20;
			$pages->applyLimit($condition);
	    	$secondhard = TuanSecondHand::model()->findAll($condition);
	    	
	    	$this->breadcrumbs = array(
    			'团购导航' => url('tuannav/list'),
    			'求购' => url('tuannav/second', array('sort'=>'buy'))
	    	);
	    	$this->pageTitle = '求购优惠券_' . $this->city['name'] . '团购导航';
    	    $this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	    $this->setPageKeyWords($this->pageTitle . ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航');
		    $this->render('second_buy', array('secondhard'=>$secondhard, 'tuandata'=>$tuandata, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'category'=>$category, 'pages'=>$pages));
		}
		if ($sort=='sell'){
			$condition = new CDbCriteria();
		   	$condition->addColumnCondition(array('city_id' => $this->city['id']));
		   	$condition->addCondition('trade_sort='.TuanSecondHand::TRADE_SORT_SELL);
		    $condition->order = 'id desc';
		    $pages = new CPagination(TuanSecondHand::model()->count($condition));
			$pages->pageSize = 20;
			$pages->applyLimit($condition);
	    	$secondhard = TuanSecondHand::model()->findAll($condition);
	    	$this->breadcrumbs = array(
    			'团购导航' => url('tuannav/list'),
    			'转让' => url('tuannav/second', array('sort'=>'sell'))
	    	);
		    $this->pageTitle = '转让优惠券_' . $this->city['name'] . '团购导航';
    	    $this->setPageDescription($this->pageTitle . '，最新最全的' . $this->city['name'] . '团购信息尽在我爱外卖网');
    	    $this->setPageKeyWords($this->pageTitle . ',' . $this->city['name'] . '团购,' . $this->city['name'] . '团购导航');
		    $this->render('second_sell', array('secondhard'=>$secondhard, 'tuandata'=>$tuandata, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'category'=>$category, 'pages'=>$pages));
		}
	}

	/**
	 * 添加
	 */
	public function actionSecondCreate()
	{
		if (user()->isGuest) {
    		user()->loginRequired();
    		exit(0);
    	}
		$category = TuanCategory::getTuanCategory();

    	$Tuansecond = new TuanSecondHand();
		if(app()->request->isPostRequest && isset($_POST['TuanSecondHand'])) {
			$post = CdcBetaTools::filterPostData(array('trade_sort', 'category_id', 'title', 'url', 'content', 'nums', 'price', 'mobile'), $_POST['TuanSecondHand']);
			$Tuansecond->attributes = $post;
			$Tuansecond->city_id = $_SESSION['city_id'];
			$Tuansecond->user_id = user()->id;
			$Tuansecond->validateCode = $_POST['validateCode'];
			if ($Tuansecond->save()) {
			    $this->redirect(url('tuannav/second', array('sort'=>'sell')));
			}
		}
	    $this->breadcrumbs = array(
    		'团购导航' => url('tuannav/list'),
    		'发布信息' => url('tuannav/secondCreate'),
	    );
    	$this->pageTitle = '团购导航';
	    $this->render('secondcreate', array('category'=>$category, 'model'=>$Tuansecond));
	}

	/**
	 * 二手搜索
	 */
	public function actionSecondSearch($category_id = 0, $sort = '', $keywords = '')
	{
    	$today = date('Y-m-d');
		$category_id = (int)$category_id;
    	$sort = strip_tags(trim($sort));
    	$kw = strip_tags(trim($keywords));
    	
	    $this->pageTitle = $this->city['name'] . '团购导航';
    	$category = TuanCategory::getTuanCategory();
    	$tuandata = TuanData::getTuanDataOfCity($this->city['id']);
    	$tuanbuy = Tuannav::getTuannavBuyOfCity($this->city['id']);
    	$tuansecond = TuanSecondHand::getTuanSecondOfCity($this->city['id']);
    		if ($sort=='sell'){
		    	$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('city_id' => $this->city['id']));
		    	if ($category_id){
			   		$condition->addCondition('category_id='.$category_id);
		    	}
			   	$condition->addCondition('trade_sort='.TuanSecondHand::TRADE_SORT_SELL);
    			if ($kw) {
			   		$condition->addSearchCondition('title', $kw);
    			}
			    $condition->order = 'id desc';
			    $pages = new CPagination(TuanSecondHand::model()->count($condition));
				$pages->pageSize = 20;
				$pages->applyLimit($condition);
		    	$secondhard = TuanSecondHand::model()->findAll($condition);
		    	$this->breadcrumbs = array(
    				'团购导航' => url('tuannav/list'),
	    			'转让' => url('tuannav/second', array('sort'=>'sell'))
		    	);
			    $this->render('second_sell', array('secondhard'=>$secondhard, 'tuandata'=>$tuandata, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'category'=>$category, 'kw'=>$kw, 'pages'=>$pages));
    		}
    		if ($sort=='buy'){
		    	$condition = new CDbCriteria();
			   	$condition->addColumnCondition(array('city_id' => $this->city['id']));
    			if ($category_id){
			   		$condition->addCondition('category_id='.$category_id);
		    	}
			   	$condition->addCondition('trade_sort='.TuanSecondHand::TRADE_SORT_BUY);
    			if ($kw) {
			   		$condition->addSearchCondition('title', $kw);
    			}
			    $condition->order = 'id desc';
			    $pages = new CPagination(TuanSecondHand::model()->count($condition));
				$pages->pageSize = 20;
				$pages->applyLimit($condition);
		    	$secondhard = TuanSecondHand::model()->findAll($condition);
		    	$this->breadcrumbs = array(
    				'团购导航' => url('tuannav/list'),
	    			'求购' => url('tuannav/second', array('sort'=>'buy'))
		    	);
			    $this->render('second_buy', array('secondhard'=>$secondhard, 'tuandata'=>$tuandata, 'tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'category'=>$category, 'kw'=>$kw, 'pages'=>$pages));
    		}
	}

	/**
	 * 举报
	 */
	public function actionReport($id)
	{
		$id = (int)$id;
		$tuannav = Tuannav::model()->findByPk($id);
		if (null === $tuannav) throw new CHttpException(500, '该团购信息不存在');
		$tuanreport = new TuanReport();
		if(app()->request->isPostRequest && isset($_POST['TuanReport'])) {
			$tuanreport->city_id = $this->city['id'];
			$tuanreport->tuan_id = $_POST['id'];
			$tuanreport->type = $_POST['TuanReport']['type'];
			$tuanreport->email = $_POST['TuanReport']['email'];
			$tuanreport->content = $_POST['TuanReport']['content'];
			$tuanreport->validateCode = $_POST['validateCode'];

			if ($tuanreport->save()) {
			    $this->redirect(url('tuannav/show', array('id'=>$_POST['id'])));
			}
		}
		$this->breadcrumbs = array(
			'团购导航' => url('tuannav/list'),
			'举报' => url('tuannav/report', array('id'=>$id)),
		);
		$this->pageTitle = $this->city['name'] . '团购导航';
		$this->render('report', array('tuannav'=>$tuannav, 'model'=>$tuanreport));
	}

	/**
	 * 团购收藏 买过
	 */
	public function actionFavorite($id, $type)
	{
		$id = (int)$id;
    	$type = strip_tags(trim($type));

		$tuannav = Tuannav::model()->findByPk($id);
		if (null === $tuannav) throw new CHttpException(500, '该团购信息不存在');

    	if ($type=='favorite') {
			if (user()->isGuest) {
				$this->redirect(url('site/login', array('referer' => app()->request->urlReferrer)));
	    		exit(0);
	    	}
	    	$favorite = new UserTuanFavorite();
	    	$favorite->tuan_id = $id;
	    	$favorite->user_id = user()->id;
	    	if(!$favorite->save()){
	    		user()->setFlash('errorSummary',CHtml::errorSummary($favorite));
	    	}else{
				$tuannav->favorite_num = $tuannav->favorite_num+1;
				if (!$tuannav->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				}
	    	}
    	}
		if ($type=='buy') {
			if ($_COOKIE['buy_id'.$id]!=$id){
				$tuannav->buy_num = $tuannav->buy_num+1;
				if (!$tuannav->save()){
					user()->setFlash('errorSummary',CHtml::errorSummary($tuannav));
				}
				SetCookie('buy_id'.$id, $id);
			}
			$this->redirect($tuannav->url);
    	}
    	$this->redirect(url('tuannav/show', array('id'=>$id)));
	}
}
?>