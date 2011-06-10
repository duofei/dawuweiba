<?php

/**
 * This is the model class for table "{{User}}".
 *
 * The followings are the available columns in table '{{User}}':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $clear_password
 * @property string $realname
 * @property integer $gender
 * @property string $birthday
 * @property string $telphone
 * @property string $mobile
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $last_login_time
 * @property string $last_login_ip
 * @property integer $login_nums
 * @property string $portrait
 * @property integer $integral
 * @property integer $credit
 * @property integer $credit_nums
 * @property integer $bcnums
 * @property string $qq
 * @property string $msn
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $source
 * @property integer $source_uid
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $office_building_id
 * @property double $office_map_x
 * @property double $office_map_y
 * @property integer $home_building_id
 * @property double $home_map_x
 * @property double $home_map_y
 * @property integer $state
 * @property integer $manage_city_id
 * @property integer $super_admin
 * @property integer $super_shop
 * @property integer $approve_state
 * @property integer $is_sendmail
 */
class User extends CActiveRecord
{
    /*
     * 性别：保密0，男1，女2
     */
    const GENDER_SECRERT = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    
    /*
     * 账号来源，新浪1，人人2
     */
    const SOURCE_SELF = 0;
    const SOURCE_SINA = 1;
    const SOURCE_RENREN = 2;
    
    public static $sources = array(
    	self::SOURCE_SELF => '本站',
    	self::SOURCE_SINA => '新浪',
    	self::SOURCE_RENREN => '人人',
    );
    
    public static $genders = array(
        self::GENDER_SECRERT => '保密',
        self::GENDER_MALE => '帅哥',
        self::GENDER_FEMALE => '美女',
    );
    
    public static $states = array(
    	STATE_DISABLED => '禁用',
    	STATE_ENABLED => '正常'
    );
    
    /*
     * 认证状态
     */
    const APPROVE_STATE_UNSETTLED = 0;
    const APPROVE_STATE_VERIFY = 1;
    const APPROVE_STATE_BLACKLIST = 2;
    public static $approve_states = array(
    	self::APPROVE_STATE_UNSETTLED => '未认证',
    	self::APPROVE_STATE_VERIFY => '已认证',
    	self::APPROVE_STATE_BLACKLIST => '黑名单'
    );
    public function getApproveStateText()
    {
    	return self::$approve_states[$this->approve_state];
    }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{User}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('username, password', 'required'),
		    array('username, email', 'unique'),
		    array('password', 'filter', 'filter'=>'md5', 'on'=>'insert'),
		    array('state', 'default', 'value'=>1, 'setOnEmpty'=>false, 'on'=>'insert'),
		    array('email', 'email'),
			array('gender, source, source_uid, is_sendmail, create_time, last_login_time, login_nums, integral, credit, credit_nums, bcnums, city_id, district_id, office_building_id, home_building_id, state, manage_city_id, super_admin, super_shop, approve_state', 'numerical', 'integerOnly'=>true),
			array('username, realname', 'length', 'max'=>60),
			array('office_map_x, office_map_y, home_map_x, home_map_y', 'numerical'),
			array('password', 'length', 'max'=>32),
			array('telphone, mobile, clear_password', 'length', 'max'=>20),
			array('create_ip, last_login_ip, qq', 'length', 'max'=>15),
			array('portrait, msn, email', 'length', 'max'=>255),
			array('birthday', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, credit_nums, is_sendmail, realname, gender, birthday, telphone, mobile, create_time, create_ip, last_login_time, last_login_ip, login_nums, portrait, integral, credit, bcnums, qq, msn, city_id, source, source_uid, district_id, office_building_id, home_building_id, office_map_x, office_map_y, home_map_x, home_map_y, state, manage_city_id, super_admin, super_shop, approve_state', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'errorReports' => array(self::HAS_MANY, 'ErrorReport', 'user_id'),
			'feedbacks' => array(self::HAS_MANY, 'Feedback', 'user_id'),
			'friends' => array(self::HAS_MANY, 'Friend', 'user2_id'),
			'giftExchangeLogs' => array(self::HAS_MANY, 'GiftExchangeLog', 'user_id'),
			'goodsRateLogs' => array(self::HAS_MANY, 'GoodsRateLog', 'user_id'),
			'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
			'searchLogs' => array(self::HAS_MANY, 'SearchLog', 'user_id', 'order'=>'searchLogs.create_time desc'),
			'shops' => array(self::HAS_MANY, 'Shop', 'user_id'),
		    'shopCount' => array(self::STAT, 'Shop', 'user_id'),
			'shopComments' => array(self::HAS_MANY, 'ShopComment', 'user_id', 'order'=>'shopComments.create_time desc'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'manageCity' => array(self::BELONGS_TO, 'City', 'manage_city_id'),
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
			'officeBuilding' => array(self::BELONGS_TO, 'Location', 'office_building_id', 'condition'=>'officeBuilding.type = ' . Location::TYPE_OFFICE),
			'homeBuilding' => array(self::BELONGS_TO, 'Location', 'home_building_id', 'condition'=>'homeBuilding.type = ' . Location::TYPE_OFFICE),
			'userActions' => array(self::HAS_MANY, 'UserAction', 'user_id'),
			'userAddresses' => array(self::HAS_MANY, 'UserAddress', 'user_id'),
			'userBCIntegralLogs' => array(self::HAS_MANY, 'UserBcintegralLog', 'user_id'),
			'userCreditLogs' => array(self::HAS_MANY, 'UserCreditLog', 'user_id'),
			'userGoodsFavorites' => array(self::HAS_MANY, 'UserGoodsFavorite', 'user_id'),
			'userIntegralLogs' => array(self::HAS_MANY, 'UserIntegralLog', 'user_id'),
			'userLoginLogs' => array(self::HAS_MANY, 'UserLoginLog', 'user_id', 'order'=>'userLoginLogs.create_time desc'),
			'userPayLogs' => array(self::HAS_MANY, 'UserPayLog', 'user_id'),
			'userShopFavorites' => array(self::HAS_MANY, 'UserShopFavorite', 'user_id'),
			'tags' => array(self::MANY_MANY, 'Tag', '{{UserTag}}(user_id, tag_id)'),
			'orderCompleteCount' => array(self::STAT, 'Order', 'user_id',
				'condition' => 'status = ' . Order::STATUS_COMPLETE,
			),
			'orderPrinterCompleteCount' => array(self::STAT, 'Order', 'user_id',
				'condition' => 'status = ' . Order::STATUS_COMPLETE . ' and buy_type=' . Shop::BUYTYPE_PRINTER,
			),
		    'yewuOpenNums' => array(self::STAT, 'Shop', 'yewu_id',
			    'condition' => 'state = ' . Shop::BUSINESS_STATE_OPEN,
			),
		    'yewuSuspendNums' => array(self::STAT, 'Shop', 'yewu_id',
			    'condition' => 'state = ' . Shop::BUSINESS_STATE_SUSPEND,
			),
		    'yewuCloseNums' => array(self::STAT, 'Shop', 'yewu_id',
			    'condition' => 'state = ' . Shop::BUSINESS_STATE_CLOSE,
			),
		);
	}
	
	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	        ),
	    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => '用户名',
			'email' => '邮箱',
			'password' => '密码',
			'realname' => '真实姓名',
			'gender' => '性别',
			'birthday' => '生日 ',
			'telphone' => '电话',
			'mobile' => '手机',
			'create_time' => '注册时间',
			'create_ip' => '注册ip',
			'last_login_time' => '最后登陆时间',
			'last_login_ip' => '最后登陆IP',
			'login_nums' => '登陆次数',
			'portrait' => '头像',
			'integral' => '积分',
			'credit' => '信用值',
			'credit_nums' => '信用评价次数',
			'bcnums' => '白吃点',
			'qq' => 'Qq',
			'msn' => 'Msn',
			'city_id' => '城市',
			'district_id' => '行政区域',
			'source' => '来源',
			'office_building_id' => '办公楼',
           	'office_map_x' => '办公楼地图坐标x',
           	'office_map_y' => '办公楼地图坐标y',
           	'home_building_id' => '小区',
           	'home_map_x' => '小区地图坐标x',
           	'home_map_y' => '小区地图坐标y',
           	'state' => '状态',
           	'manage_city_id' => '管理员城市',
           	'super_admin' => '超级管理人员',
           	'super_shop' => '超级商家',
			'approve_state' => '认证状态',
			'is_sendmail' => '是否接受邮件',
		);
	}
	
	public function getGenderText()
	{
	    return self::$genders[$this->gender];
	}
	
	public function getCreateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}
	
	/**
	 * 获取ip地址对应的城市
	 */
	public function getCreateIpCityText() {
		$ipArray = CdcBetaTools::checkIpInfo($this->create_ip);
		if($ipArray['city']) {
			return $ipArray['city'];
		} else {
			return '未知';
		}
	}
	
    public function getUpdateTimeText()
	{
	    return date(param('formatDateTime'), $this->update_time);
	}
	
	public function getLastLoginTimeText()
	{
	    if ($this->last_login_time)
	        return date(param('formatDateTime'), $this->last_login_time);
	    else
	        return null;
	}
	
	/**
	 * 获取ip地址对应的城市
	 */
	public function getLastLoginIpCityText() {
		$ipArray = CdcBetaTools::checkIpInfo($this->last_login_ip);
		if($ipArray['city']) {
			return $ipArray['city'];
		} else {
			return '未知';
		}
	}
	
	public function getPortraitUrl()
	{
	    if ($this->portrait)
	        return sbu($this->portrait);
	    else
	        return resBu(param('defaultPortrait'));
	}
	
	/**
	 * 获取用户信用平均值
	 */
	public function getCreditAverageMark()
	{
	    if (0 == $this->credit_nums) return '100%';
		return round($this->credit / $this->credit_nums * 100, 1) . '%';
	}
	
	/**
	 * 获取用户头像
	 */
	public function getPortraitHtml()
	{
		return CHtml::image($this->portraitUrl, $this->username, array('class'=>'user-portrait'));
	}
	
	/**
	 * 获取用户头像链接
	 */
	public function getPortraitLinkHtml()
	{
		return l($this->portraitHtml, url('my'), array('title'=>$this->username, 'class'=>'user-portrait-link'));
	}
	
	
	public function getScreenName()
	{
	    return $this->realname ? $this->realname : $this->username;
	}
	
	/**
	 * 获取用户购买过商品的数量
	 */
	public static function getUserOrderGoodsNums($user_id)
	{
	    $user_id = (int)$user_id;
		$goodsNums = 0;
		$condition = new CDbCriteria();
		$condition->select = 'id';
		$condition->addCondition('user_id=' . $user_id);
		$orderList = Order::model()->findAll($condition);
		foreach ($orderList as $order) {
			$goodsNums += $order->orderGoodsCount;
		}
		return (int)$goodsNums;
	}
	
	public function getOrderGoodsNums()
	{
	    return self::getUserOrderGoodsNums($this->id);
	}
	
	/**
	 * 获取用户未点评订单的数量
	 */
	public function getNoRatingNums()
	{
		return self::getUserNoRatingNums($this->id);
	}
	
	public static function getUserNoRatingNums($user_id)
	{
	    static $noRatingNums = null;
		if (null !== $noRatingNums) return $noRatingNums;
		
		$condition = new CDbCriteria();
		$condition->addCondition('t.user_id=' . $user_id);
		$condition->addInCondition('t.status', array(Order::STATUS_COMPLETE, Order::STATUS_DELIVERING));
		$orderList = Order::model()->with('shopCreditLogs', 'orderGoods', 'orderGoods.goodsRateLog')->findAll($condition);
		foreach ($orderList as $row) {
			if($row->consignee && $row->telphone){ //用户是通过post产生,非查看电话的订单
				if(!$row->shopCreditLogs->id) {
					$noRatingNums++;
				} else {
					foreach($row->orderGoods as $goods) {
						if(!$goods->goodsRateLog->goods_id) {
							$noRatingNums++;
							break;
						}
					}
				}
			}
		}
		return $noRatingNums;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('username',$this->username,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('password',$this->password,true);

		$criteria->compare('realname',$this->realname,true);

		$criteria->compare('gender',$this->gender);

		$criteria->compare('birthday',$this->birthday,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('last_login_time',$this->last_login_time,true);

		$criteria->compare('last_login_ip',$this->last_login_ip,true);

		$criteria->compare('login_nums',$this->login_nums,true);

		$criteria->compare('portrait',$this->portrait,true);

		$criteria->compare('integral',$this->integral,true);

		$criteria->compare('credit',$this->credit,true);
		
		$criteria->compare('credit_nums',$this->credit_nums,true);

		$criteria->compare('bcnums',$this->bcnums,true);

		$criteria->compare('qq',$this->qq,true);

		$criteria->compare('msn',$this->msn,true);

		$criteria->compare('city_id',$this->city_id,true);
		
		$criteria->compare('district_id',$this->district_id,true);

		$criteria->compare('source',$this->source);
		
		$criteria->compare('office_building_id',$this->office_building_id);
		
		$criteria->compare('office_map_x',$this->office_map_x);
		
		$criteria->compare('office_map_y',$this->office_map_y);
		
		$criteria->compare('home_building_id',$this->home_building_id);
		
		$criteria->compare('home_map_x',$this->home_map_x);
		
		$criteria->compare('home_map_y',$this->home_map_y);
		
		$criteria->compare('state',$this->state);
		
		$criteria->compare('manage_city_id',$this->manage_city_id);
		
		$criteria->compare('super_admin',$this->super_admin);
		
		$criteria->compare('super_shop',$this->super_shop);
		
		$criteria->compare('approve_state',$this->approve_state);
		$criteria->compare('is_sendmail',$this->is_sendmail);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getStateText()
	{
		return self::$states[$this->state];
	}

	public function getSourceText()
	{
		return self::$sources[$this->source];
	}
	
	/**
	 * 获取当前城市的注册用户数量
	 * @param integer $cityId
	 * @return integer 注册用户数量
	 */
	public function getCountOfCity($cityId=0)
	{
		$cityId = intval($cityId);
	    $criteria = new CDbCriteria();
	    if($cityId)
	    	$criteria->addColumnCondition(array('city_id' => $cityId));
	    return  self::model()->count($criteria);
	}
	
	/**
	 * 获取当前城市人人网的注册用户数量
	 * @param integer $cityId
	 * @return integer 注册用户数量
	 */
	public function getCountOfRenren($cityId=0)
	{
		$cityId = intval($cityId);
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('source'=>self::SOURCE_RENREN));
	    if($cityId)
	    	$criteria->addColumnCondition(array('city_id' => $cityId));
	    return  self::model()->count($criteria);
	}
	
	/**
	 * 获取当前城市新浪网的注册用户数量
	 * @param integer $cityId
	 * @return integer 注册用户数量
	 */
	public function getCountOfSina($cityId=0)
	{
		$cityId = intval($cityId);
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('source'=>self::SOURCE_SINA));
	    if($cityId)
	    	$criteria->addColumnCondition(array('city_id' => $cityId));
	    return  self::model()->count($criteria);
	}
	
	/**
	 * 获取当前城市最近几天的注册用户数量
	 * @param integer $days 天数
	 * @param integer $cityId
	 * @return integer 注册用户数量
	 */
	public function getCountOfDays($days, $cityId=0)
	{
		$cityId = intval($cityId);
	    $criteria = new CDbCriteria();
	    if($cityId)
	    	$criteria->addColumnCondition(array('city_id' => $cityId));
	    $t1 = mktime(0, 0, 0, date('m'), (date('d')+1), date('Y'));
	    $t2 = strtotime('-' . intval($days) . ' day', $t1);
	    $criteria->addBetweenCondition('create_time', $t2, $t1);

	    return  self::model()->count($criteria);
	}
	
	public function afterLogin()
	{
	    $this->last_login_time = $_SERVER['REQUEST_TIME'];
	    $this->last_login_ip = CdcBetaTools::getClientIp();
	    $this->login_nums++;
	    $this->save();
	    
	    $loginlog = new UserLoginLog();
	    $loginlog->user_id = intval($this->id);
	    $loginlog->referer = CdcBetaTools::getReferrer();
	    $loginlog->login_type = $this->source;
	    $loginlog->save();
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			// 增加到最新动态
			UserAction::addNewAction(UserAction::TYPE_USER_REGISTER, $this->username);
		}
		return true;
	}

	/**
	 * 获取一个虚假用户
	 */
	public static function getRandomUntrueUser()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('email'=>'', 'gender'=>'3', 'clear_password'=>'xxxxxx'));
		$criteria->order = 'rand()';
		$criteria->limit = 1;
		return self::model()->find($criteria);
	}

	/**
	 * 发送手机验证码 - 认证用户
	 */
	public static function sendSmsVerifyCode($user_id, $phone)
	{
		if($user_id) {
			$key = 'smsVerifyCode' . $user_id;
			$value = rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$cache = app()->cache->set($key, $value);
			if(SendSms::filter_mobile($phone)) {
				$content = iconv('utf-8', 'gb2312', '我爱外卖网：您的手机验证码为' . $value);
				$d = SendSms::send_sms($phone, $content);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 发送语音验证码 - 认证用户
	 */
	public static function sendVoiceVerifyCode($user_id, $phone)
	{
		if($user_id) {
			$key = 'smsVerifyCode' . $user_id;
			if(SendVoice::filter_phone($phone)) {
				$d = SendVoice::send_voice($phone);
				if($d) {
					$cache = app()->cache->set($key, $d);
					return $d;
				}
			}
		}
		return false;
	}
	
	/**
	 * 验证手机验证码 - 认证用户
	 */
	public static function checkSmsVerifyCode($user_id, $code)
	{
		if($user_id && $code) {
			$key = 'smsVerifyCode' . $user_id;
			$cache = app()->cache->get($key);
			if($code == $cache) {
				//app()->cache->delete($key);
				return true;
			}
		}
		return false;
	}
}