<?php

class TestController extends Controller
{
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

    public function actionCurl()
    {
        $rest = new CdcCurl();
        $data = $rest->open()->exec('http://www.google.com');
        echo $rest->error();
        echo '<hr /><hr />';
        /*print_r($rest->http_code());
        print_r($rest->http_info());
        print_r($rest->http_headers());*/
        echo h($data);
    }
    
    public function actionSms()
    {
        $client = new CdCurl();
        $args = array(
            'id' => 'cdcchen',
            'pwd' => 'cdc790406',
            'to' => '18660157718,18653137700,13853137700',
            'content' => iconv("UTF-8","GB2312",'测试测试，严重测试00000'),
            'time' => '',
        );
        $data = $client->get('http://service.winic.org/sys_port/gateway/', $args)->rawdata();
        print_r($data);
    }
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