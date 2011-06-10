<?php

class GrouponController extends Controller
{
	public function actionIndex($atid = null)
	{
	    $this->breadcrumbs = array('同楼订餐' => aurl('groupon'));
	    $this->pageTitle = '同楼订餐';
	    
	    $atid = (int)$atid;
	    
	    $history = Location::getLastVisit();
	    if (!is_array($history)) {
	        $location = Location::model()->findByPk((int)$history, 'type = ?', array(Location::TYPE_OFFICE));
	        $atid = (int)$history;
	        if ($location) $this->breadcrumbs[] = $location->name;
	        //var_dump($location);
	    }
	    /*
	     * 如果用户登录了，并且登录用户资料存在，则读取用户设置的办公楼及所住的楼宇
	     * 如果用户曾经订过餐或在用户中心中添加过送餐地址，一并读取出来
	     * 最后楼宇的资料存放在$offics变量中
	     */
	    if (!user()->isGuest && $user = User::model()->findByPk(user()->id)) {
	        $user->office_building_id && $buildings[] = $user->office_building_id;
	        $user->home_building_id && $buildings[] = $user->home_building_id;
	        foreach ($user->userAddresses as $v) {
	            $v->building_id && $buildings[] = $v->building_id;
	        }
	        if ($buildings) {
	            $criteria = new CDbCriteria();
	            $criteria->addInCondition('id', $buildings);
	            $criteria->addColumnCondition(array('state' => STATE_ENABLED));
	            $offices = Location::model()->findAll($criteria);
	        }
	    }
	    
	    /*
	     * 获取商铺
	     */
	    $shops = self::loadShops($atid);
	    
	    $shopIds = CHtml::listData((array)$shops['shops'], 'id', 'id');
	    /*
	     * 获取热卖美食
	     */
	    $goods = Goods::getHotGoods($shopIds);
	    
	    /*
	     * 生成排序class名称，注意生成的变量需要与排序字段对应，如下面的：
	     * $taste_avg，$order_nums，$service_avg
	     */
	    $order = explode('.', trim(strip_tags($_GET['sort'])));
	    ${$order[0]} = 'checked' . $order[1];
	    
	    $endtime = strtotime(param('grouponEndTime'));
	    $remaintime = getdate(mktime(0,0,0,1,1,1970) + $endtime - $_SERVER['REQUEST_TIME']);
	    $currentLocation = Location::getSearchHistoryData(1);
	    
	    // 截入pager.css
		$path = Yii::getPathOfAlias('system.web.widgets.pagers');
		$url = app()->assetManager->publish($path) . '/';
		cs()->registerCssFile($url . 'pager.css', 'screen');
	    
	    $this->render('index', array(
	        'shops' => $shops['shops'],
	        'sort' => $shops['sort'],
	        'goods' => $goods,
	        'tasteSort' => $taste_avg,
	        'orderSort' => $order_nums,
	        'serviceSort' => $service_avg,
	        'remaintime' => $remaintime,
	        'currentLocation' => $currentLocation[0],
	        'building' => $location,
	        'offices' => $offices,
	    ));
	}

	private static function loadShops($atid)
	{
	    $atid = (int)$atid;
    	$location = Location::model()->findByPk($atid);
        //if (null == $location) throw new CHttpException(404, '该楼宇不存在');
        
        /*
         * 获取商铺
         */
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('is_group'=>STATE_ENABLED));
        $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
        $criteria->with = array('tags');
        //$criteria->order = 't.id desc'; //排序暂时未定
        $data = Shop::getLocationShopList($location, 0, $criteria);
    	    
	    return $data;
	}

	
}