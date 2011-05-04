<?php

/**
 * This is the model class for table "{{UserIntegralLog}}".
 *
 * The followings are the available columns in table '{{UserIntegralLog}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $integral
 * @property integer $source
 * @property integer $create_time
 * @property string $create_ip
 * @property string $remark
 */
class UserIntegralLog extends CActiveRecord
{
	
	/*
     * 获取积分来源
     */
    const SOURCE_REGISTER = 1;
    const SOURCE_MAPLABEL = 2;
    const SOURCE_DATAPERFECTION = 3;
    const SOURCE_INVITE = 4;
    const SOURCE_CONSUMPTION = 5;
    const SOURCE_GOODSEVALUATE = 6;
    const SOURCE_SERVEEVALUATE = 7;
    const SOURCE_ERRORCORRECTION = 8;
    
    const SOURCE_GIFT = 9;
    const SOURCE_BCINTEGRAL = 10;
    const SOURCE_BADEVALUATE = 11;
    
    const SOURCE_ADMIN = 12;
    
    public static $sources = array(
        self::SOURCE_REGISTER => '注册',
        self::SOURCE_MAPLABEL => '地图标注',
        self::SOURCE_DATAPERFECTION => '资料完善',
        self::SOURCE_INVITE => '邀请好友',
        self::SOURCE_CONSUMPTION => '点餐消费',
        self::SOURCE_GOODSEVALUATE => '商品点评',
        self::SOURCE_SERVEEVALUATE => '商家服务点评',
        self::SOURCE_ERRORCORRECTION => '用户纠错',
        
        self::SOURCE_GIFT => '兑换礼品',
        self::SOURCE_BCINTEGRAL => '兑换白吃点',
        self::SOURCE_BADEVALUATE => '差评',
        
        self::SOURCE_ADMIN => '管理员操作',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserIntegralLog the static model class
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
		return '{{UserIntegralLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, source, integral', 'required'),
			array('user_id, source, create_time, integral', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('remark', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, integral, source, create_time, create_ip, remark', 'safe', 'on'=>'search'),
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => '用户',
			'integral' => '积分值',
			'source' => '积分来源',
			'create_time' => '操作时间',
			'create_ip' => 'Ip',
			'remark' => '备注',
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

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('integral',$this->integral);

		$criteria->compare('source',$this->source,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider('UserIntegralLog', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->remark == ''){
			$name = $this->integral>0 ? '增加' : '消耗';
			$this->remark = $this->sourceText . ' ' . $name . ' ' . abs($this->integral) . '积分';
		}
		return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			/* 增加用户积分 */
	       	$counters = array('integral' => $this->integral);
	       	User::model()->updateCounters($counters, 'id = ' . $this->user_id);
	       	
	       	/* 更新session里积分的值 */
	       	$user = User::model()->findByPk($this->user_id);
			$session = app()->session;
			$session['integral'] = $user->integral;
	       	
	       	/* 如果是兑换白吃点操作 */
	       	if(self::SOURCE_BCINTEGRAL == $this->source)
	       	{
	       		$userbcintegrallog = new UserBcintegralLog();
	       		$userbcintegrallog->attributes = array(
	       			'user_id' => $this->user_id,
					'source' => UserBcintegralLog::SOURCE_INTERGRAL,
					'integral' => abs($this->integral)/1000,
	       		);
	       		$userbcintegrallog->save();
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
	
	/**
     * 获取积分来源
     */
	public function getSourceText()
	{
	    return self::$sources[$this->source];
	}
	
	/**
	 * 增加用户积分增减操作记录
	 */
	static function addUserIntegralLog($source, $integral, $user_id=0) {
		if(!$user_id) {
			$user_id = user()->id;
		}
		$userIntegralLog = new UserIntegralLog();
		$userIntegralLog->user_id = $user_id;
		$userIntegralLog->source = $source;
		$userIntegralLog->integral = $integral;
		if($userIntegralLog->save()) {
			return true;
		} else {
		 	return Chtml::errorSummary($userIntegralLog);
		}
	}
	
}