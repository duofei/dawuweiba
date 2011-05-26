<?php

/**
 * This is the model class for table "{{UserBCIntegralLog}}".
 *
 * The followings are the available columns in table '{{UserBCIntegralLog}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $integral
 * @property integer $source
 * @property integer $create_time
 * @property string $create_ip
 * @property string $remark
 */
class UserBcintegralLog extends CActiveRecord
{
	
	/*
     * 获取积分来源
     */
    const SOURCE_INTERGRAL = 1;
    const SOURCE_ADMINADD = 2;
    const SOURCE_CONSUME = 3;
    const SOURCE_INVITER = 4;
    const SOURCE_INVITEE = 5;
    const SOURCE_SYSGIVE = 6;

    public static $sources = array(
        self::SOURCE_INTERGRAL => '积分兑换',
        self::SOURCE_ADMINADD => '管理员添加',
        self::SOURCE_CONSUME => '消费',
        self::SOURCE_INVITER => '邀请好友',
        self::SOURCE_INVITEE => '被好友邀请',
        self::SOURCE_SYSGIVE => '系统赠送'
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserBcintegralLog the static model class
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
		return '{{UserBCIntegralLog}}';
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
			'integral' => '白吃点值',
			'source' => '白吃点来源',
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

		return new CActiveDataProvider('UserBcintegralLog', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->remark == ''){
			$name = $this->integral > 0 ? '增加' : '消耗';
			$this->remark = $this->sourceText . ' ' . $name . ' ' . abs($this->integral) . '白吃点';
		}
		return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
	       	$counters = array('bcnums' => $this->integral);
	       	User::model()->updateCounters($counters, 'id = ' . $this->user_id);
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
	
	public function getIntegralText()
	{
		if($this->integral > 0) {
			return '+' . $this->integral;
		}
		return $this->integral;
	}
}