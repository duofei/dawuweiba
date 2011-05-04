<?php
class FriendlinkController extends Controller
{
	public function actionIndex()
	{
		/*
	     * 设置面包屑导航
	     */
	    $this->breadcrumbs = array(
    		'友情链接' => url('friendlink/index'),
	    );
	    
	    $this->pageTitle = '友情链接列表';
        $this->setPageKeyWords();
        $this->setPageDescription();
	    
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('city_id=' . $this->city[id] . ' or city_id=0');
	    $criteria->addColumnCondition(array('isvalid'=>STATE_ENABLED));
	    $criteria->order = 'city_id asc, order_id desc';
	    $friendlinks = FriendLink::model()->findAll($criteria);
	    
		$this->render('index', array(
			'friendlinks' => $friendlinks
		));
	}
	
	public function actionCreate()
	{
		/*
	     * 设置面包屑导航
	     */
	    $this->breadcrumbs = array(
    		'友情链接' => url('friendlink/index'),
	    	'申请友情链接' => url('friendlink/create'),
	    );
	    
	    $this->pageTitle = '申请友情链接';
        $this->setPageKeyWords();
        $this->setPageDescription();
        
		$friendlink = new FriendLink('newlink');
		$friendlink->city_id = $this->city['id'];
		if (app()->request->isPostRequest && isset($_POST['FriendLink'])) {
			$post = CdcBetaTools::filterPostData(array('validateCode', 'name', 'homepage', 'logo', 'desc'), $_POST['FriendLink']);
			$friendlink->attributes = $post;
			if($friendlink->save()) {
				$success = true;
			}
		}
		
		$this->render('create', array(
			'friendlink' => $friendlink,
			'success' => $success
		));
	}
}