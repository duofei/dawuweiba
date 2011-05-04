<?php
//set_time_limit(3600);
class BevinController extends Controller
{
	/**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'goods'=>array(
                'class'=>'CWebServiceAction',
                'classMap'=>array(
                    'User'=>'User'
                )
            ),
        );
    }

    /**
     * @return User[]  // <= return类型为User和object，输出结果会有差别
     * @soap
     */
    public function getUser()
    {
        $user = User::model()->findAll();
        return $user;
    }
    
	public function actionMakePinyin()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('pinyin=""');
		$criteria->limit = '1000';
		
		$criteria->select = "id, name, pinyin, city_id, map_x, map_y";
		$locations = Location::model()->findAll($criteria);
		$i = 0;
		foreach($locations as $l) {
			$l->save();
			$i++;
		}
		echo $i;
	}
	
	public function actionT()
	{
		exit;
		$session = app()->session;
		$session->setSessionID('d9eaf61fc5cd165d67ac878b0403d9a3');
		print_r($session->useCustomStorage);
		echo $session->readSession('d9eaf61fc5cd165d67ac878b0403d9a3');
		echo $session->get('email');
		exit;
		$a = CdcBetaTools::curl("http://maps.google.com/maps/api/geocode/json?address=%E6%B5%8E%E5%8D%97%E6%95%B0%E7%A0%81%E6%B8%AF&sensor=false&region=cn");
		echo $a;
	}
	
	public function actionTest()
	{
		echo User::sendVoiceVerifyCode(8, '18660808812');
		echo '<br />';
		echo User::sendVoiceVerifyCode(7, '13625410237');
		echo '<br />';
		echo User::sendVoiceVerifyCode(6, '18660157718');
		//echo User::checkSmsVerifyCode(8, '2276');
		exit;
		echo User::checkSmsVerifyCode(8, '123123');
		exit;
		$username = file('username.txt');
			foreach ($username as $u) {
			$user = new User();
			$user->username = trim($u);
			$user->password = 'xxxxxx';
			$user->clear_password = 'xxxxxx';
			$user->gender = 3;
			$user->save();
			echo CHtml::errorSummary($user);
			unset($user);
		}
		exit;
		$img = 'http://img1.kbcdn.com/n03_a/cb21/42/ef/514be9cc5fd8cc44055200e10aa9_200x150.jpg';
		$file = file_get_contents($img);
		file_put_contents('a.jpg', $file);
		exit;
		$session = app()->session;
		$sessionid = $session->getSessionID();
		echo $session->get('email');
		echo '<br />';
		echo $sessionid;
		//print_r($_SESSION);
		exit;
		phpinfo();
		exit;
		print_r($_SERVER);
		exit;
		function a($e, $b) {
			echo $e . $b;
		}
		register_shutdown_function('a', 2, 3);
		echo 'bbb';
		exit(100);
		
		
		$file = file_get_contents('a.txt');
		$file .= "\n" . time();
		//file_put_contents('a.txt', $file);
		//$this->render('/test/bevin');
		//exit('ccc');
		
		$request = 'method=cart.getList&token=eaba8be466b26450573226be93a818b3&apikey=0985251f3d13076beec69aca778ea31f&format=xml&pagenum=10';
		//$post = 'token=eaba8be466b26450573226be93a818b3&city_id=1&consignee=陈贤鹤&address=山大路&message=柴荆具茶茗&telphone=13625410237&mobile=12345678&building_id=5738&deliver_time=12:00';
		$post = 'goods_id=161&token=eaba8be466b26450573226be93a818b3';
		
		$fp = fsockopen("www.52wm.com",80);
		fputs($fp,"POST /api?$request HTTP/1.0\n");
		fputs($fp,"Host: www.52wm.com\n");
		fputs($fp,"Authorization: Basic " . base64_encode("bevin:123123a") . "\n");
		fputs($fp,"Content-type: application/x-www-form-urlencoded\n");
		fputs($fp,"Content-length: " . strlen($post) . "\n\n");
		fputs($fp,$post);
		fpassthru($fp);
		exit;

		/*
		// Sina
		header('Content-type: text/html; charset=' . app()->charset);
		$request = 'source=' . urlencode('1449812750');
		//d374698a8ceeb24928ba37a6812eb834
		//$request .= '&method=' . urlencode('User.getInfo');
		//$request .= '&format=' . urlencode('XML');
		$fp = fsockopen("api.t.sina.com.cn",80);
		fputs($fp,"POST /favorites.json HTTP/1.0\n");
		fputs($fp,"Host: api.t.sina.com.cn\n");
		fputs($fp,"Authorization: Basic " . base64_encode("user:pass") . "\n");
		fputs($fp,"Content-type: application/x-www-form-urlencoded\n");
		fputs($fp,"Content-length: " . strlen($request) . "\n\n");
		fputs($fp,$request);
		fpassthru($fp);
		exit;
		*/
		/*
		// RenRen
		header('Content-type: text/html; charset=' . app()->charset);
		$request = 'api_key=' . urlencode('791edf43a74140018c85a9fd087c9e91');
		$request .= '&method=' . urlencode('friends.get');
		$request .= '&format=' . urlencode('JSON');
		$fp = fsockopen("api.renren.com",80);
		fputs($fp,"POST /restserver.do HTTP/1.0\n");
		fputs($fp,"Host: api.renren.com\n");
		fputs($fp,"Authorization: Basic " . base64_encode("user:pass") . "\n");
		fputs($fp,"Content-type: application/x-www-form-urlencoded\n");
		fputs($fp,"Content-length: " . strlen($request) . "\n\n");
		fputs($fp,$request);
		fpassthru($fp);
		exit;
		*/
		//
		
		/*
		echo $_SERVER['PHP_AUTH_USER'];
		echo $_SERVER['PHP_AUTH_PW'];
//		print_r($_GET);
//		print_r($_POST);
//		print_r($_REQUEST);
		exit;
		echo $this->getUser();
		//print_r($_SERVER);
		exit;
		$useraction = new UserAction();
		$postData = array(
			'id' => '12',
			'name' => 'nabbme',
			'value' => 'vaaalue',
			'age'	=> '12'
		);
		$post = CdcBetaTools::filterPostData(array('id','name','value'), $postData);
		print_r($post);
		exit;
		$useraction->atype = 1;
		$useraction->content = 'asdfsafd';
		$useraction->save();
		$this->render('/test/bevin');
	*/
	/*
		$criteria = new CDbCriteria();
		$criteria->select = 'id, username, email, password';
		$criteria->disableBehaviors();
		$user = User::model()->findByPk(8, $criteria);

		$user->setAttributes(array('telphone'=>'1'));
		$user->saveAttributes(array('username','telphone','update_time'));
		echo CHtml::errorSummary($user);*/
		
	}

	public function actionPost()
	{
		
		print_r($_POST);
	}
	
	
		
	/**
	 * 获取商铺分类
	 * @Example /api/getShopCategory
	 * @return 返回商铺分类
	 */
	public function actionGetShopCategory()
	{
		$this->renderPartial('shopcategory');
	}
	
	/**
	 * 通过商铺ID获取蛋糕商品列表信息
	 * @Example /api/getCakeGoodsListByShopId/shopid/37/
	 * @param integer $shopid
	 * @param integer $category 蛋糕的分类ID，默认为Null
	 * @param integer $shape 蛋糕的形状ID，默认为Null
	 * @param integer $purpose 蛋糕的用途ID，默认为Null
	 * @param integer $variety 蛋糕的品种ID，默认为Null
	 * @return 返回商品列表信息
	 */
	public function actionGetCakeGoodsListByShopId($shopid, $category=null, $shape=null, $purpose=null, $variety=null)
	{
		$shopid = intval($shopid);
		if($shopid <=0 ) {
			throw new CException('ShopId参数错误', 400);
		}
		$shop = Shop::model()->findByPk($shopid);
		$model = Goods::$goodsTbl[$shop->category_id];
		if(!$model) {
			throw new CException('系统错误', 500);
		}
		if($model != 'cakeGoods') {
			throw new CException('此商铺不是蛋糕店', 602);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.shop_id'=>$shopid, 't.state'=>Goods::STATE_SELL));
		$criteria->order = 't.orderid desc';
		
		$withmodel = array($model, 'tags');
		$withmodel[] = $model . '.cakePrices';
		$withmodel[] = $model . '.Purposes';
		$withmodel[] = $model . '.Varietys';
		
		$category = intval($category);
		if($category) {
			$criteria->addColumnCondition(array($model . '.category_id'=>$category));
		}
		$shape = intval($shape);
		if($shape) {
			$criteria->addColumnCondition(array($model . '.shape_id'=>$shape));
		}
		$purpose = intval($purpose);
		if($purpose) {
			$criteria->addColumnCondition(array('Purposes.id'=>$purpose));
		}
		$variety = intval($variety);
		if($variety) {
			$criteria->addColumnCondition(array('Varietys.id'=>$variety));
		}

		$goodslist = Goods::model()->with($withmodel)->findAll($criteria);
		
		if(!$goodslist) {
			throw new CException('没有查询结果', 601);
		}
		
		$this->renderPartial(strtolower($model) . 'list', array(
			'goodslist' => $goodslist,
			'model' => $model,
		));
	}
	
	/**
	 * 通过商铺ID获取美食商品列表信息
	 * @Example /api/getFoodGoodsListByShopId/shopid/19/
	 * @param integer $shopid
	 * @param integer $category 美食的分类ID，默认为Null
	 * @param integer $week 周几，默认为Null，范围1-7
	 * @return 返回美食商品列表信息
	 */
	public function actionGetFoodGoodsListByShopId($shopid, $category=null, $week=null)
	{
		$shopid = intval($shopid);
		if($shopid <=0 ) {
			throw new CException('ShopId参数错误', 400);
		}
		$shop = Shop::model()->findByPk($shopid);
		$model = Goods::$goodsTbl[$shop->category_id];
		if(!$model) {
			throw new CException('系统错误', 500);
		}
		if($model != 'foodGoods') {
			throw new CException('此商铺不是餐馆', 602);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('t.shop_id'=>$shopid, 't.state'=>Goods::STATE_SELL));
		$criteria->order = 't.orderid desc';
		
		$withmodel = array($model, 'tags');
		$withmodel[] = $model . '.goodsCategory';
		if($shop->is_dailymenu == STATE_ENABLED) {
			$week = intval($week);
			if($week <=0 || $week >7) {
				throw new CException('Week参数错误', 400);
			}
			$withmodel[] = 'dayList';
			$criteria->addColumnCondition(array('dayList.week'=>$week));
		}
		$category = intval($category);
		if($category) {
			$criteria->addColumnCondition(array($model . '.category_id'=>$category));
		}

		$goodslist = Goods::model()->with($withmodel)->findAll($criteria);
		
		if(!$goodslist) {
			throw new CException('没有查询结果', 601);
		}
		
		$this->renderPartial(strtolower($model) . 'list', array(
			'goodslist' => $goodslist,
			'model' => $model,
		));
	}

	/**
	 * 通过商铺ID获取美食商品分类
	 * @Example /api/getFoodCategory/shopid/19/
	 * @param integer $shopid
	 * @return 返回美食商品分类
	 */
	public function actionGetFoodCategory($shopid)
	{
		$shopid = intval($shopid);
		if($shopid <=0 ) {
			throw new CException('ShopId参数错误', 400);
		}
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id'=>$shopid));
		$criteria->order = 'orderid desc';
		$category = GoodsCategory::model()->findAll($criteria);
		if(!$category) {
			throw new CException('没有查询结果', 601);
		}
		$this->renderPartial('foodcategory', array(
			'category' => $category,
		));
	}

	public function actionUpdateLocation()
	{
		$building = Building::model()->findAll();
		foreach ($building as $b) {
			$name = $b->name;
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('name'=>$name, 'category'=>''));
			$location = Location::model()->find($criteria);
			if(null === $location) {
				$location = new Location();
			}

			$location->city_id = 1;
			$location->district_id = $b->district_id;
			$location->name = $b->name;
			$location->address = $b->address;
			$location->map_x = $b->map_x;
			$location->map_y = $b->map_y;
			$location->type = $b->type;
			$location->state = $b->state;
			$location->letter = $b->letter;
			if(!$location->save()) {
				echo CHtml::errorSummary($location);
			}
		}
	}

	public function actionMap()
	{
		$this->renderPartial('/test/bevin');
	}

	public function actionTesta()
	{
		echo Setting::setValue('bevina', '123123');
		$bevin = Setting::getValue('bevin');
		echo $bevin;
		exit;
		/*
		$data['center'] = array(
	    	'map_y' => $this->city['map_y'],
	    	'map_x' => $this->city['map_x'],
    	);
    	$criteria = new CDbCriteria();
    	$criteria->select = "id, shop_name, map_region, create_time";
    	$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
    	$criteria->addInCondition('business_state', array(Shop::BUSINESS_STATE_OPEN, Shop::BUSINESS_STATE_SUSPEND));
    	$data['allshop'] = Shop::model()->findAll($criteria);
		*/
		$this->renderPartial('/test/bevin');
	}
	
	public function actionImage()
	{
		$criteria = new CDbCriteria();
    	$criteria->select = "id, shop_name, map_region, create_time";
    	$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
    	$criteria->addInCondition('business_state', array(Shop::BUSINESS_STATE_OPEN, Shop::BUSINESS_STATE_SUSPEND));
    	$shops = Shop::model()->findAll($criteria);
    	$maxMin = array(
    		'max' => array('x'=>0, 'y'=>0),
    		'min' => array('x'=>1000, 'y'=>1000),
    	);
    	$regions = array();
    	$regionsMonthAgo = array();
    	foreach ($shops as $shop) {
    		$region = explode('|', $shop->map_region);
    		foreach ($region as $r) {
    			$temp = explode(',', $r);
    			if($maxMin['max']['x'] < $temp[0]) {
    				$maxMin['max']['x'] = $temp[0];
    			}
    			if($maxMin['max']['y'] < $temp[1]) {
    				$maxMin['max']['y'] = $temp[1];
    			}
    			if($maxMin['min']['x'] > $temp[0]) {
    				$maxMin['min']['x'] = $temp[0];
    			}
    			if($maxMin['min']['y'] > $temp[1]) {
    				$maxMin['min']['y'] = $temp[1];
    			}
    			if($shop->create_time < (time()-2592000)) {
    				$regionsMonthAgo[$shop->id][] = array($temp[0], $temp[1]);
    			}
    			$regions[$shop->id][] = array($temp[0], $temp[1]);
    		}
    	}
    	$baseNum = 5000;
    	$width = ($maxMin['max']['x'] - $maxMin['min']['x']) * $baseNum;
    	$height = ($maxMin['max']['y'] - $maxMin['min']['y']) * $baseNum;
    	
    	// 图片处理开始
    	$image = ImageCreate($width, $height);
    	$bg = imagecolorallocatealpha($image, 255, 255, 255, 100);
    	$color = imagecolorallocatealpha($image, 255, 151, 0, 80);
    	foreach ($regions as $key=>$coored) {
    		$values = array();
    		foreach ($coored as $c) {
    			$values[] = ($c[0]-$maxMin['min']['x']) * $baseNum;
    			$values[] = ($maxMin['max']['y']-$c[1]) * $baseNum;
    		}
    		imagefilledpolygon($image, $values, count($values)/2, $color);
    	}
		imagepng($image, param('staticBasePath') . 'ditu' . '/ditu_now.png');
		imagedestroy($image);
		
		$imageMonthAgo = ImageCreate($width, $height);
		$bgMonthAgo = imagecolorallocatealpha($imageMonthAgo, 255, 255, 255, 100);
		$colorMonthAgo = imagecolorallocatealpha($imageMonthAgo, 255, 103, 4, 80);
		foreach ($regionsMonthAgo as $key=>$coored) {
    		$values = array();
    		foreach ($coored as $c) {
    			$values[] = ($c[0]-$maxMin['min']['x']) * $baseNum;
    			$values[] = ($maxMin['max']['y']-$c[1]) * $baseNum;
    		}
    		imagefilledpolygon($imageMonthAgo, $values, count($values)/2, $colorMonthAgo);
    	}
		imagepng($imageMonthAgo, param('staticBasePath') . 'ditu' . '/ditu_monthago.png');
		imagedestroy($imageMonthAgo);
	}
}