<?php

/**
 * This is the model class for table "{{Order}}".
 *
 * The followings are the available columns in table '{{Order}}':
 * @property integer $id
 * @property integer $order_sn
 * @property integer $shop_id
 * @property integer $user_id
 * @property integer $groupon_id
 * @property string $consignee
 * @property string $address
 * @property string $telphone
 * @property string $mobile
 * @property integer $building_id
 * @property integer $city_id
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 * @property float $dispatching_amount
 * @property float $amount
 * @property float $group_amount
 * @property float $paid_amount
 * @property string $paid_remark
 * @property float $due_amount
 * @property float $actual_money
 * @property integer $is_pay
 * @property integer $buy_type
 * @property integer $pay_type
 * @property string $message
 * @property integer $delivery_id
 * @property integer $cancel_state
 * @property string $cancel_reason
 * @property string $deliver_time
 * @property integer $is_carry
 * @property integer $is_print
 * @property integer $verify_state
 */
class Order extends CActiveRecord
{
	public $token = null;
	
	const STATUS_UNDISPOSED = 0;
	const STATUS_PROCESS = 1;
    const STATUS_DELIVERING = 2;
    const STATUS_COMPLETE = 3;
    const STATUS_CANCEL = 4;
    const STATUS_INVAIN = 5;

    public static $states = array(
    	self::STATUS_UNDISPOSED => '未加工',
        self::STATUS_PROCESS => '加工中',
        self::STATUS_DELIVERING => '配送中',
        self::STATUS_COMPLETE => '已完成',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_INVAIN => '无效订单',
    );
    
    public static $printStates = array(
    	self::STATUS_UNDISPOSED => '未处理',
        self::STATUS_PROCESS => '加工中',
        self::STATUS_DELIVERING => '配送中',
        self::STATUS_COMPLETE => '已确定',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_INVAIN => '无效订单',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
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
		return '{{Order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		
		return array(
			array('shop_id, amount','required'),
			array('consignee, address, telphone', 'required', 'on'=>'checkout'),
			array('telphone, mobile', 'match', 'pattern'=>'/(1\d{10})|((0\d{2,3}[-——]?)?\d{7,8})/', 'on'=>'checkout'),
			array('status, is_pay, order_sn, shop_id, user_id, groupon_id, building_id, city_id, create_time, buy_type, pay_type, delivery_id, cancel_state, is_carry, is_print, verify_state', 'numerical', 'integerOnly'=>true),
			//array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert, checkout'),
			array('consignee', 'length', 'max'=>60),
			array('token', 'length', 'is'=>32),
			array('address, message, cancel_reason, paid_remark', 'length', 'max'=>255),
			array('telphone, mobile, deliver_time', 'length', 'max'=>20),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('amount, actual_money, paid_amount, due_amount, group_amount, dispatching_amount', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('consignee, address telphone, mobile', 'filter', 'filter'=>'strip_tags'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_sn, shop_id, user_id, consignee, address, telphone, mobile, building_id, city_id, groupon_id, create_ip, create_time, status, amount, actual_money, paid_amount, due_amount, is_pay, buy_type, pay_type, message, delivery_id, cancel_state, cancel_reason, is_carry, deliver_time, is_print, verify_state', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'deliveryMan' => array(self::BELONGS_TO, 'DeliveryMan', 'delivery_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'orderGoods' => array(self::HAS_MANY, 'OrderGoods', 'order_id'),
			'orderLogs' => array(self::HAS_MANY, 'OrderLog', 'order_id'),
			'shopComment' => array(self::HAS_MANY, 'ShopComment', 'order_id'),
			'userCreditLogs' => array(self::HAS_ONE, 'UserCreditLog', 'order_id'),
			'shopCreditLogs' => array(self::HAS_ONE, 'ShopCreditLog', 'order_id'),
			'userPayLogs' => array(self::HAS_ONE, 'UserPayLog', 'order_id'),
			'orderGoodsCount' => array(self::STAT, 'OrderGoods', 'order_id'),
		    'orderDeliveringLog' => array(self::HAS_ONE, 'OrderLog', 'order_id',
		        'condition' => 'orderDeliveringLog.type_id = ' . self::STATUS_DELIVERING,
		    ),
		    'orderCompleteLog' => array(self::HAS_ONE, 'OrderLog', 'order_id',
		        'condition' => 'orderCompleteLog.type_id = ' . self::STATUS_COMPLETE,
		    ),
		    'building' => array(self::BELONGS_TO, 'Location', 'building_id', 'condition'=>'type = ' . Location::TYPE_OFFICE),
		    'groupon' => array(self::BELONGS_TO, 'Groupon', 'groupon_id'),
		);
	}

	
	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	    		'updateAttribute' => NULL,
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	        	'updateAttribute' => NULL,
	        )
	    );
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_sn' => '订单号',
			'shop_id' => '商铺',
			'user_id' => '用户',
			'groupon_id' => '同楼订餐',
			'consignee' => '收货人',
			'address' => '详细地址',
			'telphone' => '联系电话',
			'mobile' => '备选电话',
			'building_id' => '建筑物',
			'city_id' => '城市',
			'create_ip' => '添加IP',
			'create_time' => '添加时间',
			'status' => '状态',
			'dispatching_amount' => '送餐费',
			'amount' => '总金额',
			'group_amount' => '同楼订餐总金额',
			'paid_amount' => '已付金额',
			'paid_remark' => '已付说明',
			'due_amount' => '应收金额',
			'actual_money' => '实收金额',
			'is_pay' => '是否已付款',
			'buy_type' => '订餐方式',
			'pay_type' => '付款方式',
			'message' => '附言',
			'delivery_id' => '配送员',
			'cancel_state' => '取消订单申请状态',
			'cancel_reason' => '取消订单理由',
			'is_carry' => '自提',
			'deliver_time' => '送达时间',
			'is_print' => '是否打印',
			'verify_state' => '审核状态'
		);
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

		$criteria->compare('order_sn',$this->order_sn,true);

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('user_id',$this->user_id,true);
		
		$criteria->compare('groupon_id',$this->groupon_id,true);

		$criteria->compare('consignee',$this->consignee,true);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('building_id',$this->building_id,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('dispatching_amount',$this->dispatching_amount,true);
		$criteria->compare('amount',$this->amount,true);
		
		$criteria->compare('group_amount',$this->group_amount,true);
		$criteria->compare('paid_amount',$this->paid_amount,true);
		$criteria->compare('paid_remark',$this->paid_remark,true);
		$criteria->compare('due_amount',$this->due_amount,true);
		
		$criteria->compare('actual_money',$this->actual_money,true);

		$criteria->compare('is_pay',$this->is_pay);
		
		$criteria->compare('buy_type',$this->buy_type);
		
		$criteria->compare('pay_type',$this->pay_type);

		$criteria->compare('message',$this->message,true);
		
		$criteria->compare('delivery_id',$this->delivery_id,true);
		
		$criteria->compare('cancel_state',$this->cancel_state,true);
		
		$criteria->compare('cancel_reason',$this->cancel_reason,true);
		
		$criteria->compare('is_carry',$this->is_carry,true);
		
		$criteria->compare('deliver_time',$this->deliver_time,true);
		
		$criteria->compare('is_print',$this->is_print,true);
		$criteria->compare('verify_state',$this->verify_state,true);
		
		return new CActiveDataProvider('Order', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 订单号中取订单id
	 */
	public function getOrderId($order_num)
	{
		return substr($order_num, 8);
	}
	
	/**
	 * 获取订单号
	 */
	public function getOrderSn()
	{
		return $this->order_sn . $this->id;
	}
	
	/**
	 * 获取订餐方式
	 */
	public function getBuyTypeText()
	{
		return Shop::$buytype[$this->buy_type];
	}
	
	/**
	 * 获取支付方式
	 */
	public function getPayTypeText()
	{
		return Shop::$paytype[$this->pay_type];
	}
	
	/**
	 * 获取订单状态
	 */
	public function getStatusText()
	{
		if($this->buy_type == Shop::BUYTYPE_PRINTER) {
			if($this->verify_state != STATE_ENABLED) {
				return '待审核';
			}
			if($this->status == self::STATUS_COMPLETE) {
				return self::$printStates[$this->status] . '(预计送达时间' . $this->deliver_time . ')';
			}
			return self::$printStates[$this->status];
		} else {
			return self::$states[$this->status];
		}
	}
		
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
		return true;
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->isNewRecord) {
			//array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// 把rules里的更改到了这里
			if(user()->id) {
				$this->user_id = user()->id;
			}
			
			$this->order_sn = date('Ymd', $_SERVER['REQUEST_TIME']);
			$cart = Cart::getGoodsList($this->token);
	       	foreach ($cart as $v) {
	       		$amount += $v->goods_price * $v->goods_nums;
	       		$group_amount += $v->group_price * $v->goods_nums;
	       	}
			$this->amount = $amount + $this->dispatching_amount;
			$this->group_amount = $group_amount;
			
			// 计算应收金额
			$this->due_amount = $this->amount - $this->paid_amount;
		}
		return true;
	}
		
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			/* 增加商铺订单数量 */
	       	$counters = array('order_nums' => 1, 'undressed_order_nums' => 1);
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	       	
	       	/* orderGoods表增加记录 */
	       	$cart = Cart::getGoodsList($this->token);
	       	foreach ($cart as $v) {
	       	    $orderGoods = new OrderGoods();
	       	    $orderGoods->token = $this->token;
	       	    $orderGoods->goods_id = $v->goods_id;
	       	    $orderGoods->order_id = $this->id;
	       	    $orderGoods->save();
	       	}
	       	Cart::clearCart($this->token);
	       	
	       	/* 增加用户积分 */
	       	if(!user()->isGuest) {
		       	$userinteral = new UserIntegralLog();
		       	$userinteral->attributes = array(
		       		'user_id' => user()->id,
		       		'source' => UserIntegralLog::SOURCE_CONSUMPTION,
		       		'integral' => param('markUserAddOrder')
		       	);
		       	$userinteral->save();
	       	}
	       	
	       	$currentAtId = Location::getLastVisit();
	       	if(!is_array($currentAtId) && $currentAtId) {
		       	if($this->buy_type == Shop::BUYTYPE_NETWORK) {
		       		Location::addUseNums($currentAtId, 10);
		       	} else {
		       		Location::addUseNums($currentAtId, 2);
		       	}
	       	}
	       	
	       	// 增加到最新动态
	       	if(user()->isGuest) {
	       		if($this->buy_type == Shop::BUYTYPE_NETWORK) {
	       			UserAction::addNewAction(UserAction::TYPE_MAKE_NEWORDER, substr($this->telphone, 0, -4).'XXXX',$this->shop->shop_name);
	       		}
	       	} else {
				UserAction::addNewAction(UserAction::TYPE_MAKE_NEWORDER, user()->getState('screenName'),$this->shop->shop_name);
	       	}
	    }
	    return true;
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('my/order/list', array('state' => 0));
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('my/order/list', array('state' => 0));
	}
	
	/**
	 * 格式配送费
	 */
	public function getDispatchingAmountPrice()
	{
		if ((int)$this->dispatching_amount == ($this->dispatching_amount + 0)) return (int)$this->dispatching_amount;
		return app()->format->number($this->dispatching_amount);
	}
	
	/**
	 * 格式化总金额
	 */
	public function getAmountPrice()
	{
		if ((int)$this->amount == ($this->amount + 0)) return (int)$this->amount;
		return app()->format->number($this->amount);
	}
	
	/**
	 * 格式化同楼团购总金额
	 */
	public function getGroupAmountPrice()
	{
		if ((int)$this->group_amount == ($this->group_amount + 0)) return (int)$this->group_amount;
		return app()->format->number($this->group_amount);
	}
	
	/**
	 * 格式化实收金额
	 */
	public function getActualMoney()
	{
		if ((int)$this->actual_money == ($this->actual_money + 0)) return (int)$this->actual_money;
		return app()->format->number($this->actual_money);
	}
	
	/**
	 * 格式化已付金额
	 */
	public function getPaidAmountPrice()
	{
		if ((int)$this->paid_amount == ($this->paid_amount + 0)) return (int)$this->paid_amount;
		return app()->format->number($this->paid_amount);
	}
	
	/**
	 * 格式化应收金额
	 */
	public function getDueAmountPrice()
	{
		if ((int)$this->due_amount == ($this->due_amount + 0)) return (int)$this->due_amount;
		return app()->format->number($this->due_amount);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getCreateDateTimeText()
	{
		return date(param('formatDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortCreateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d
	 */
	public function getCreateDateText()
	{
		return date(param('formatDate'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：H:i:s
	 */
	public function getCreateTimeText()
	{
		return date(param('formatTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：H:i
	 */
	public function getShortCreateTimeText()
	{
		return date(param('formatShortTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：m-d H:i
	 */
	public function getPastCreateTimeText()
	{
		$pasttime = $_SERVER['REQUEST_TIME'] - $this->create_time;
		
		if($pasttime < 60) {
			return $pasttime . '秒前';
		} elseif($pasttime < 3600) {
			return floor($pasttime/60) . '分钟前';
		} elseif ($pasttime < 3600*24) {
			return floor($pasttime/3600) . '小时前';
		} elseif ($pasttime < 3600*24*30) {
			return floor($pasttime/(3600*24)) . '天前';
		} else {
			return $this->shortCreateDateTimeText;
		}
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
	
	/**
	 * 返回送餐时间的数据数组
	 * @return array
	 */
	public static function getDeliverTimeData($ahead = 1)
	{
		$ahead = $ahead ? $ahead : 1;
	    $d = getdate();
	    $m = $d['minutes'] % 15;
	    $time = $_SERVER['REQUEST_TIME'] - $m * 60 + $ahead * 3600;
	    $h = date('H', $time);
	    while ($h < 23) {
	        $time += 15*60;
	        $d = date('H:i', $time);
	        $date[$d] = $d;
	        $h = date('H', $time);
	    }
	    return (array)$date;
	}

	/**
	 * 返回一个城市总的订单数量
	 * @param integer $cityId 城市ID
	 * @return integer 一个城市的总订单数
	 */
	public static function getCountOfCity($cityId=0)
	{
	    $cityId = (int)$cityId;
	    $criteria = new CDbCriteria();
	    if($cityId) {
	    	$criteria->addColumnCondition(array('city_id' => $cityId));
	    }
	    return  self::model()->count($criteria);
	}
	
	/**
	 * 返回一个城市总的订单金额
	 * @param integer $cityId 城市ID
	 * @return float 一个城市的总订金额
	 */
	public static function getAmountOfCity($cityId=0)
	{
		$cityId = (int)$cityId;
	    $criteria = new CDbCriteria();
	    if($cityId) {
	    	$sql = 'select sum(amount) from {{Order}} where city_id='.$cityId;
	    } else {
	    	$sql = 'select sum(amount) from {{Order}}';
	    }
	    $row = app()->db->createCommand($sql)->queryColumn();
		return ($row[0]+0);
	}
	
	/**
	 * 返回某个城市某个月份的订单数量
	 * @param integer $month 月(1-12)
	 * @param integer $year 年，如果不赋值则取当前年份
	 * @param integer $cityId 城市ID
	 * @return integer 某一个城市某一天的订单数量
	 * @throws CException 如果$day参数不在1-31范围之内
	 */
	public static function getCountOfMonth($month, $year = null, $cityId = 0)
	{
	    if ($month <= 1 || $month > 12) throw new CException('$month参数不正确', 0);
	    
	    $date = getdate();
	    if (empty($year)) $year = $date['year'];
	    
	    $criteria = new CDbCriteria();
        $cityId = (int)$cityId;
        if($cityId) {
            $criteria->addColumnCondition(array('city_id' => $cityId));
        }
        $t1 = mktime(0, 0, 0, $month, 1, $year);
        $t2 = strtotime('next Month', $t1);
        $criteria->addBetweenCondition('create_time', $t1, $t2);
        
        return self::model()->count($criteria);
	}
	
	/**
	 * 返回某个城市最近几天的订单数量
	 * @param integer $days 天数
	 * @param integer $cityId 城市ID
	 * @return integer 某一个城市最近天的订单数量
	 */
	public static function getCountOfDays($days = 1, $cityId = 0)
	{
		$cityId = (int)$cityId;
		$criteria = new CDbCriteria();
        if($cityId) {
            $criteria->addColumnCondition(array('city_id' => $cityId));
        }
	    
	    $days = intval($days);
	    $t1 = mktime(0, 0, 0, date('m'), (date('d')+1), date('Y'));
	    $t2 = strtotime('-' . $days . ' day', $t1);
	    $criteria->addBetweenCondition('create_time', $t2, $t1);

	    return self::model()->count($criteria);
	}
	
    /**
	 * 返回某个城市最近几天的订单总金额
	 * @param integer $days 天数
	 * @param integer $cityId 城市ID
	 * @return float 某一个城市最近天的订单总金额
	 * @throws CException 如果该城市不存在
	 */
    public static function getAmountOfDays($days = 1, $cityId = 0)
    {
    	$cityId = (int)$cityId;
	    
	    $days = intval($days);
	    $t1 = mktime(0, 0, 0, date('m'), date('j'), date('Y'));
        $t2 = strtotime('-' . $days . ' day', $t1);
    	if($cityId) {
           $sql = "select sum(amount) from {{Order}} where create_time>'$t2' and create_time<'$t1' and city_id=$cityId";
        } else {
        	$sql = "select sum(amount) from {{Order}} where create_time>'$t2' and create_time<'$t1'";
        }
	    $row = app()->db->createCommand($sql)->queryColumn();
		return ($row[0]+0);
    }
    
	/**
	 * 返回某个城市某一天的订单数量
	 * @param integer $day 日(1-31)
	 * @param integer $month 月(1-12)，如果不赋值则取当前月份
	 * @param integer $year 年，如果不赋值则取当前年份
	 * @param integer $cityId 城市ID
	 * @return integer 某一个城市某一天的订单数量
	 * @throws CException 如果$day参数不在1-31范围之内
	 */
	public static function getCountOfDay($day, $month = null, $year = null, $cityId = 0)
	{
	    if ($day <= 0 || $day > 31) throw new CException('$day参数不正确', 0);
	    
	    $date = getdate();
	    if (empty($year)) $year = $date['year'];
	    if (empty($month)) $month = $date['mon'];
	    
		$cityId = (int)$cityId;
		$criteria = new CDbCriteria();
        if($cityId) {
            $criteria->addColumnCondition(array('city_id' => $cityId));
        }
        $t1 = mktime(0, 0, 0, $month, $day, $year);
        $t2 = strtotime('next Day', $t1);
        $criteria->addBetweenCondition('create_time', $t1, $t2);
        
        return self::model()->count($criteria);
	}

	/**
	 * 获取某个城市当天的订单数量
	 * @param integer $cityId 城市ID，默认为0，即取所有城市当天订单量总和
	 * @return integer 当天订单量
	 */
    public static function getCountOfToday($cityId = 0)
    {
        $day = date('j');
        return self::getCountOfDay($day, null, null, $cityId);
    }
    
    /**
     * 获取某个城市当天的订单总金额
     * @param integer $cityId 城市ID，默认为0
     * @return float 当天的订单总金额
     */
    public static function getAmountOfToday($cityId = 0)
    {
	    $t1 = mktime(0, 0, 0, date('m'), date('j'), date('Y'));
        $t2 = strtotime('next Day', $t1);
        
    	$cityId = (int)$cityId;
        if($cityId) {
            $sql = "select sum(amount) from {{Order}} where create_time>'$t1' and create_time<'$t2' and city_id=$cityId";
        } else {
        	$sql = "select sum(amount) from {{Order}} where create_time>'$t1' and create_time<'$t2'";
        }
        
	    $row = app()->db->createCommand($sql)->queryColumn();
		return ($row[0]+0);
    }
    
    /**
     * 获取最近24小时内订单数量
     * @param integer $cityId 城市ID，默认为0，即取所有城市最近24小时订单量总和
     * @return 最近24小时订单量
     */
    public static function getCountOf24Hours($cityId = 0)
    {
    	$cityId = (int)$cityId;
		$criteria = new CDbCriteria();
        if($cityId) {
            $criteria->addColumnCondition(array('city_id' => $cityId));
        }
        $t2 = $_SERVER['REQUEST_TIME'];
        $t1 = strtotime('last Day', $t2);
        $criteria->addBetweenCondition('create_time', $t1, $t2);
        return self::model()->count($criteria);
    }

    /**
     * 获取某个店铺的订单数量
     * @param 店铺ID $shopid
     * @param 订单状态 $status
     * @return integer 订单数量
     */
	public static function getTodayOrderNums($shopid, $status)
	{
	    $shopid = (int)$shopid;
	    $status = (int)$status;
	    $date = date('Y-m-d');
		$start = strtotime($date);
		$end = $start + 24*60*60;
    	$condition = new CDbCriteria();
	   	$condition->addCondition("create_time >= $start");
	   	$condition->addCondition("create_time <= $end");
	   	$condition->addColumnCondition(array('shop_id' => $shopid));
		$condition->addColumnCondition(array('status' => $status));
	   	$count = Order::model()->count($condition);
	    return $count;
	}
}