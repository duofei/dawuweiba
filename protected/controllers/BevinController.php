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
}