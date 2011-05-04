<?php

class GiftController extends Controller
{
    /**
     * 礼品中心首页，礼品列表
     */
	public function actionIndex()
	{
	    $gifts = Gift::getSortGiftList();
	    //var_dump($gifts);exit;
	    $this->breadcrumbs = array(
			'礼品中心' => url('gift'),
	    );
	    
	    $this->pageTitle = '礼品中心';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('index', array(
		    'gifts' => $gifts,
		));
	}

	/**
	 * 礼品详细资料
	 * @param integer $giftid 礼品ID
	 */
	public function actionShow($giftid)
	{
	    $giftid = (int)$giftid;
	    $gift = Gift::model()->findByPk($giftid);
	    if (null === $gift) throw new CHttpException(404);
	    
	    $this->breadcrumbs = array(
			'礼品中心' => url('gift'),
	        $gift->name,
	    );
	    
	    $this->pageTitle = $gift->name . '_' . '礼品中心';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('show', array('gift'=>$gift));
	}

	/**
	 * 兑换礼品
	 * @param integer $giftid 礼品ID
	 */
	public function actionExchange($giftid)
	{
		// 判断用户是否已登陆
		if(user()->isGuest) {
			$this->redirect(url('site/login'));
		}
		
		// 获取礼品信息
		$giftid = intval($giftid);
		$gift = Gift::model()->findByPk($giftid);
		if (null === $gift) throw new CHttpException(404);
		
		// 判断用户积分是否够兑换礼品
		if($_SESSION['integral'] - $gift->integral < 0) {
			$this->redirect(url('gift/show', array('giftid'=>$gift->id)));
		}
		
		$this->breadcrumbs = array(
			'礼品中心' => url('gift'),
			$gift->name => url('gift/show', array('giftid'=>$gift->id)),
	        '兑换礼品',
	    );
	    $this->pageTitle = $gift->name . '_' . '礼品中心';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    
	    $giftexchange = new GiftExchangeLog();
	    
	    $address = UserAddress::model()->findAllByAttributes(array('user_id'=>user()->id));
		foreach($address as $value) {
		 	if($value->is_default) {
		 		$address_default = $value;
		  	}
		}
	    
		if (app()->request->isPostRequest && isset($_POST['GiftExchange'])) {
	        /*
	         * 保存或添加收货人地址
	         */
	        $uaid = (int)$_POST['GiftExchange']['id'];
	        if (empty($uaid))
	            $userAddress = new UserAddress();
	        elseif ($_POST['GiftExchange'])
                $userAddress = UserAddress::model()->findByPk($uaid);
            if ($userAddress) {
            	$post = CdcBetaTools::filterPostData(array('id', 'consignee', 'address', 'telphone', 'mobile', 'city_id'),$_POST['GiftExchange']);
                $userAddress->attributes = $post;
                try {$userAddress->save();} catch (CException $e) {}
            }
            
            /*
             * 保存兑换记录
             */
            $giftexchange->consignee = trim(strip_tags($_POST['GiftExchange']['consignee']));
            $giftexchange->address = trim(strip_tags($_POST['GiftExchange']['address']));
            $giftexchange->telphone = $_POST['GiftExchange']['telphone'];
            $giftexchange->mobile = $_POST['GiftExchange']['mobile'];
            $giftexchange->message = trim(strip_tags($_POST['GiftExchange']['message']));
            $giftexchange->city_id = $_POST['GiftExchange']['city_id'];
            $giftexchange->integral = $gift->integral;
            $giftexchange->gift_id = $_POST['gift_id'];
            
            if ($giftexchange->save()) {
	           $this->redirect(url('gift/exchangesuccess'));
            }
	    }
	    
		$this->render('exchange', array(
			'gift' => $gift,
			'giftexchange' => $giftexchange,
			'address' => $address,
			'address_default' => $address_default,
		));
	}

	/*
	 * 兑换礼品成功提示页
	 */
	public function actionExchangesuccess()
	{
		$this->breadcrumbs = array(
			'礼品中心' => url('gift'),
	        '兑换礼品',
	    );
	    
	    $this->pageTitle = '礼品中心';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
	    
		$this->render('exchangesuccess');
	}
}