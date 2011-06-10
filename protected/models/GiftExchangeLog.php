<?php
    
/**
 * This is the model class for table "{{GiftExchangeLog}}".
 *
 * The followings are the available columns in table '{{GiftExchangeLog}}':
 * @property integer $id
 * @property integer $gift_id
 * @property integer $user_id
 * @property integer $integral
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $city_id
 * @property string $consignee
 * @property string $address
 * @property string $telphone
 * @property string $mobile
 * @property string $message
 * @property integer $state
 */
class GiftExchangeLog extends CActiveRecord
{
    const STATE_NOHANDLE = 0;
    const STATE_SEND = 1;
    const STATE_COMPLETE = 2;
    
    public static $states = array(
        self::STATE_NOHANDLE => '未处理',
        self::STATE_SEND => '已寄出',
        self::STATE_COMPLETE => '已兑换',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return GiftExchangeLog the static model class
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
		return '{{GiftExchangeLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gift_id, user_id, integral, city_id, consignee, address, telphone', 'required'),
			array('gift_id, user_id, integral, create_time, city_id, state', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('consignee', 'length', 'max'=>60),
			array('address, message', 'length', 'max'=>255),
			array('telphone, mobile', 'length', 'max'=>20),
			array('telphone, mobile', 'match', 'pattern'=>'/(1\d{10})|((0\d{2,3}[-——]?)?\d{7,8})/', 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, gift_id, user_id, integral, create_time, create_ip, city_id, consignee, address, telphone, mobile, message, state', 'safe', 'on'=>'search'),
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
			'gift' => array(self::BELONGS_TO, 'Gift', 'gift_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'gift_id' => '礼品',
			'user_id' => '用户',
			'integral' => '积分',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'city_id' => '城市',
			'consignee' => '联系人',
			'address' => '收货地址',
			'telphone' => '联系电话 ',
			'mobile' => '备选电话',
			'message' => '备注',
			'state' => '状态'
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
		$criteria->compare('gift_id',$this->gift_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('integral',$this->integral,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_ip',$this->create_ip,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('consignee',$this->consignee,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('telphone',$this->telphone,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('GiftExchangeLog', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			$userIntegralLog = new UserIntegralLog();
			$userIntegralLog->attributes = array(
				'user_id' => $this->user_id,
				'integral' => $this->integral * -1,
				'source' => UserIntegralLog::SOURCE_GIFT,
			);
			$userIntegralLog->save();
	    }
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
		}
		return true;
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
	
	public function getStateText()
	{
	    return self::$states[$this->state];
	}
}