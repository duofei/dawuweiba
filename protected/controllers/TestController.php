<?php

class TestController extends Controller
{
    const API_KEY = '7224d1f9252c829b6b8e67dee1edd7fc';
    const API_URL = 'http://www.52wm.com/api/';
    
	public function actionIndex()
	{
	    /*//exit;
	    header('content-type:image/png');
	    
	    $client = new SoapClient("f:\\Downloads\\QRBarCodeService.wsdl");
	    $params = array('Data'=>'123456', 'PicFormat'=>'png');
	    $picData = $client->GenerateBarCode($params);
	    echo $picData->BarCode;
	    exit;*/
	    $this->render('index');
	}
	
	public function actionInfo()
	{
	    phpinfo();
	}
	
	public function actionSina()
	{
	    
	    $sina = new SinaTApp(param('sinaApiKey'), param('sinaApiSecret'));
	    $url = $sina->getConnectUrl();
	    echo l('连接', $url);
	    
	}
	
	/**
	 * 商铺及商品搜索
	 * @param string $kw 搜索关键字
	 */
	public function actionSearch($kw, $cid = 0)
	{
	    $atid = Location::getLastVisit();
    	$kw = urldecode(strip_tags(trim($_GET['kw'])));
	    if (empty($atid) || empty($kw)) $this->redirect(app()->homeUrl);
    	
	    $location = Location::model()->findByPk($atid);
    	if (null == $location) throw new CHttpException(404);
    	
    	/*
	     * 获取商铺
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
	    $data = Shop::getLocationShopList($location, $cid, $criteria);
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    unset($data);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addInCondition('shop_id', $shopIds);
	    $criteria->addSearchCondition('name', $kw);
	    $criteria->order = 't.shop_id asc, t.id asc';
	    
	    $goods = Goods::model()->with('shop')->findAll($criteria);
	    
	    foreach ($goods as $v) {
	        $data['goods'][$v->shop->shop_name][] = $v;
	    }
	    
	    $data['cart'] = Cart::getGoodsList();
	    
	    $this->breadcrumbs = array(
			$location->name => url('shop/list', array('atid'=>$location->id, 'cid'=>$shop->category_id)),
			'搜索：' . $kw,
		);
	    
	    $this->render('/shop/search', $data);
	}
	
	public function actionSpSave()
	{
	    $data = array('aaa', 'bbb', 'ccc');
	    sp()->save($data);
	}
	
    public function actionSpLoad()
	{
	    var_dump(sp()->load());
	}
	
	public function actionMail()
	{
	    exit;
	    require('PHPMailer/class.phpmailer.php');
	    $mail = new PHPMailer();
	    $mail->CharSet = 'utf-8';
    	$mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.exmail.qq.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port
        
        $mail->Username   = "chendong@52wm.com";  // GMAIL username
        $mail->Password   = "cdc790406";            // GMAIL password
        
        $mail->From       = "chendong@52wm.com";
        $mail->FromName   = "我爱外卖网";
        $mail->Subject    = "This is the subject";
        $mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
        $mail->WordWrap   = 50; // set word wrap
        $body = "This is the body when user 陈小东  views in plain text format";
        $mail->MsgHTML($body);
        
        $mail->AddAddress("cdcchen@163.com", "陈小东");
        
        $mail->IsHTML(true); // send as HTML
        
        if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
          echo "Message has been sent";
        }
	}
    
	public function actionBasic()
	{
    	if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Hellotianma Login"');
            header('HTTP/1.1 401 Unauthorized');
        } else {
            echo "<p>用户名：{$_SERVER['PHP_AUTH_USER']}.</p>";
            echo "<p>密码： {$_SERVER['PHP_AUTH_PW']} </p>";
        }
        exit;
	}
	
	public function actionGoBasic()
	{
	    $fp = fsockopen("www.52wm.cn",80);
        fputs($fp,"GET /test/basic HTTP/1.0");
        fputs($fp,"Host: www.52wm.cn");
        fputs($fp,"Authorization: Basic " . base64_encode("abc:123"));
        echo fpassthru($fp);
	}
	
	public function actionFsread()
	{
	    $mdb = app()->mdb;
	    $mdb->initConnection();
	    $db = $mdb->getDb();
	    $grid = $db->getGridFS();
	    //var_dump($grid);
	    $file = $grid->get(new MongoId('4d0ec7097ead2e400f0c0000'));
	    //print_r($file);
	    
	    header('Content-Type: image/jpeg');
	    echo $file->getBytes();
	}
	
	public function actionFsWrite()
	{
	    $mdb = app()->mdb;
	    $mdb->initConnection();
	    $db = $mdb->getDb();
	    $grid = $db->getGridFS();
	    print_r($grid);
	    $id = $grid->storeFile('e://8.jpg', array('date'=>new MongoDate()));
	    echo $id;
	}
	
	public function actionFsUpdate()
	{
	    $mdb = app()->mdb;
	    $mdb->initConnection();
	    $db = $mdb->getDb();
	    $grid = $db->getGridFS();
	    
	    $criteria = array('_id' => new MongoId('4d0ec7097ead2e400f0c0000'));
	    $metaData = array('$set' => array(
	        'comment' => 'test test',
	        'views' => null,
	    ));
	    
	    $id = $grid->update($criteria, $metaData);
	    echo $id;
	}
	
    public function actionPoint()
    {
        $p1 = array('lat'=>36.618558, 'lon'=>117.189432);
        $p2 = array('lat'=>36.658563, 'lon'=>117.191210);
        echo distanceBetweenPoints($p1, $p2);
    }

    public function actionConvert()
    {
        $point = array(5, 5);
        $ids = CDShopGis::fetchShopListId($point, 3);
        print_r($ids);
        exit;
        
        /*$region = array(array(0, 0), array(10, 0), array(10, 10), array(0, 10), array(0, 0));
        $ring = CDShopGis::setShopRegion(3, $region);
        var_dump($ring);
        exit;*/
        
        echo '<pre>';
        $data = CDShopGis::fetchShopRegion(1);
        print_r($data);

    }
    
    public function actionGo()
    {
        $point = array(1,2);
        $r1 = array(array(0,0), array(10,0), array(10, 10), array(0,10));
        $r2 = array(array(0,0), array(10,0), array(10, 10), array(0,10));
        $r3 = array(array(0,0), array(10,0), array(10, 10), array(0,10));
        CDShopGis::insert(7, '家家欢乐餐厅', $point, $r1, $r2, $r3);
    }
    
    /**
     * 将商家送餐范围存储由mysql转到pgsql
     */
    public function actionMyToPg()
    {
        return ;
        $criteria = new CDbCriteria();
        $criteria->select = 'id, shop_name, map_y, map_x, map_region, map_region2, map_region3';
        $criteria->order = 'id asc';
        $shops = Shop::model()->findAll($criteria);

        foreach ($shops as $shop) {
            $shopid = $shop->id;
            $shop_name = $shop->shop_name;
            $point = array($shop->map_x, $shop->map_y);
            $region1 = regionToArray($shop->map_region);
            $region2 = regionToArray($shop->map_region2);
            $region3 = regionToArray($shop->map_region3);
            print_r($region1);
            print_r($region2);
            print_r($region3);
            CDShopGis::insert($shopid, $shop_name, $point, $region1, $region2, $region3);
            unset($shopid, $shop_name, $point, $region1, $region2, $region3);
        }
    }
    
    public function actionApiShop($locid = 0, $lat = 0, $lon = 0, $cityid = 1)
    {
        $locid = (int)$locid;
        $lat = strip_tags(trim($lat));
        $lon = strip_tags(trim($lon));
        $cityid = (int)$cityid;
        
        if (empty($locid) && (empty($lat) || empty($lon)))
            $this->redirect(aurl('bdapp'));
        
        $history = self::getBdLocationHistory();
        Location::addSearchHistory($locid);
        
        if ($locid) {
            $args = array(
                'apikey' => self::API_KEY,
                'method' => 'shop.getListByLocation',
                'location_id' => $locid,
                'city_id' => $cityid,
            );
        }
        else {
            $args = array(
                'apikey' => self::API_KEY,
                'method' => 'shop.getListByCoordinate',
                'lat' => $lat,
                'lon' => $lon,
                'city_id' => $cityid,
            );
        }
        
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = json_decode(api_execute($url), true);
        
        if (empty($data) || $data['errorCode']) {
            $this->render('no_shop', array(
                'history' => $history,
            ));
            app()->end();
        }
        
        $pages = new CPagination(count($data));
        $pages->setPageSize(15);
        
    
        $page = (int)$_GET['page'];
    	$offset = $page ? ($page-1)*10 : 0;
    	$data = array_slice($data, $offset, 5, true);
    	
    	print_r($data);
    }
    
    private static function getBdLocationHistory()
    {
        $html = '';
	    $history = Location::getSearchHistoryData(3);
	    foreach ((array)$history as $v) {
        	$html .= l($v->name, aurl('bdapp/shopSearch', array('locid'=>$v->id)), array('title'=>$v->name)) . '&nbsp;&nbsp;';
	    }
	    return $html;
    }
    
    public function actionUrl()
    {
        $s = 'http://www.aaa.com/user/陈东啊啊啊-1000';
        echo urlencode($s);
    }
    
    public function actionTestOrder()
    {
        $cityid = 1;
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'order.checkout',
            'city_id' => $cityid,
            'consignee' => '陈东',
            'address' => '数码港7d-5',
            'message' => 'xxoo',
            'telphone' => '13853137700',
            'mobile' => '13853137700',
            'deliver_time' => '12:00',
            'token' => '1fe6235cb283feff32fdf719403410d0',
        );
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = json_decode(api_execute($url, $args, 'cdcchen', 'cdc790406'), true);
        print_r($data);
    }
    
    public function actionUserAddr()
    {
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'user.getAddress',
        );
        $url = buildApiUrl(self::API_URL, $args);
        $data = json_decode(api_execute($url, array(), 'cdcchen', 'cdc790406'), true);
        print_r($data);
    }
}

function regionToArray($region)
{
    if (empty($region)) return null;
    
    $points = explode('|', $region);
    array_push($points, $points[0]);
    foreach ($points as $p) {
        $data[] = array_reverse(explode(',', $p));
    }
    
    return $data;
}


function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function distanceBetweenPoints($p1, $p2)
{
    if (!$p1 || !$p2) return 0;
    
    $R = 6371000;
    $dLat = ($p2['lat']- $p1['lat']) * M_PI / 180;
    $dLon = ($p2['lon'] - $p1['lon']) * M_PI / 180;
    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos($p1['lat'] * M_PI / 180) * cos($p2['lat'] * M_PI / 180) *
        sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c;
    return (int)$d;
}


/**
 * 创建执行url
 * @param string $apiurl api地址
 * @param array $args 需要传递的参数
 * @return string 格式化之后的url地址
 */
function buildApiUrl($apiurl, $args)
{
    if (empty($apiurl) || !is_array($args))
        return null;
        
    return $apiurl . '?' . http_build_query($args);
}

/**
 * 执行api
 * @param string $url
 * @return string 返回JSON编码的字符串
 */
function api_execute($url, $args, $user, $pass)
{
    if (empty($url)) return false;
    if (false === filter_var($url, FILTER_VALIDATE_URL)) return false;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);

    if ($user && $pass)
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $pass);

    if ($args) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    }

    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


