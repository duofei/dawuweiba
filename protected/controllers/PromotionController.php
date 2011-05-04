<?php

class PromotionController extends Controller
{
    /**
     * 优惠信息频道首页(列表页)
     * @param integer $atid
     */
	public function actionIndex($atid = 0, $pid = 0)
	{
	    $atid = (int)$atid;
	    $pid = (int)$pid;
	    if (0 == $atid) $atid = Location::getLastVisit();

	    $location = Location::model()->findByPk($atid);

	    if (null == $location) {
	        /*
    	     * 设置面包屑导航
    	     */
    	    $this->breadcrumbs = array(
    			'优惠信息' => url('promotion'),
    	    );
            $this->pageTitle = "当前位置未确定";
            $this->setPageKeyWords($this->pageTitle);
	        $this->setPageDescription($this->pageTitle);
	        $this->render('index');
	        app()->end();
	    }

	    /*
	     * 获取商铺
	     */
	    $criteria = new CDbCriteria();
	    $criteria->select = 'id, shop_name, map_region';
	    $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
	    $criteria->with = array('tags');
	    $criteria->order = 't.id desc'; //排序暂时未定
	    $data = Shop::getLocationShopList($location, $cid, $criteria);
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    unset($data);
	    /*
	     * 获取优惠信息
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addInCondition('shop_id', $shopIds);
	    $promotions = Promotion::model()->with('shop')->findAll($criteria);

	    /*
	     * 设置面包屑导航
	     */
	    $this->breadcrumbs = array(
			$location->name => url('shop/list', array('atid'=>$location->id)),
    		'优惠信息' => url('promotion'),
	    );

	    $this->pageTitle = "{$location->name}周边商铺优惠信息";
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('index', array('data'=>$promotions));
	}


}