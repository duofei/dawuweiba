<?php

/**
 * This is the model class for table "{{UserLoginLog}}".
 *
 * The followings are the available columns in table '{{UserLoginLog}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $referer
 * @property integer $login_type
 * @property integer $create_time
 * @property string $create_ip
 */
class UserLoginLog extends CActiveRecord
{
	
    /*
     * 账号来源，新浪1，人人2
     */
    const SOURCE_SINA = 1;
    const SOURCE_RENREN = 2;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserLoginLog the static model class
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
		return '{{UserLoginLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, login_type, referer', 'required'),
			array('create_time, user_id, login_type', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('referer', 'length', 'max'=>255),
			array('referer', 'url'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, create_time, create_ip, referer, login_type', 'safe', 'on'=>'search'),
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
			'create_time' => '登陆时间',
			'create_ip' => 'Ip',
			'referer' => '来源页面',
			'login_type' => '登陆模式',
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

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('referer',$this->referer,true);

		$criteria->compare('login_type',$this->login_type);

		return new CActiveDataProvider('UserLoginLog', array(
			'criteria'=>$criteria,
		));
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
}