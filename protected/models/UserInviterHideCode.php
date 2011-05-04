<?php

/**
 * This is the model class for table "{{UserInviterHideCode}}".
 *
 * The followings are the available columns in table '{{UserInviterHideCode}}':
 * @property integer $id
 * @property string $hcode
 * @property integer $integral
 * @property integer $use_nums
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 */
class UserInviterHideCode extends CActiveRecord
{
	public static $states = array(
		STATE_DISABLED => '不能使用',
		STATE_ENABLED => '可以使用'
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserInviterHideCode the static model class
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
		return '{{UserInviterHideCode}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hcode, integral', 'required'),
			array('hcode', 'unique'),
			array('state, integral, use_nums, create_time', 'numerical', 'integerOnly'=>true),
			array('hcode', 'length', 'max'=>10),
			array('create_ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hcode, integral, use_nums, create_time, create_ip, state', 'safe', 'on'=>'search'),
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
			'id' => 'Id',
			'hcode' => '隐藏码',
			'integral' => '白吃点数',
			'use_nums' => '被使用次数',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
			'state' => '状态',
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

		$criteria->compare('hcode',$this->hcode,true);

		$criteria->compare('integral',$this->integral,true);

		$criteria->compare('use_nums',$this->use_nums,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('UserInviterHideCode', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	

	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortCreateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->create_time);
	}
}