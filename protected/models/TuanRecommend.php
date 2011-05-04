<?php

/**
 * This is the model class for table "{{TuanRecommend}}".
 *
 * The followings are the available columns in table '{{TuanRecommend}}':
 * @property string $id
 * @property string $url
 * @property string $city_id
 * @property string $create_time
 * @property string $create_ip
 */
class TuanRecommend extends CActiveRecord
{
	/*
     * 状态
     */
    const STATE_NORMAL = 0;
    const STATE_PASS = 1;
    const STATE_IGNORE = 2;
    
    public static $states = array(
    	self::STATE_NORMAL => '正常',
    	self::STATE_PASS => '通过',
    	self::STATE_IGNORE => '忽略'
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanRecommend the static model class
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
		return '{{TuanRecommend}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, city_id', 'required'),
			array('create_time, city_id, state, nums', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15),
			array('url', 'url'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url, city_id, create_time, create_ip, state, nums', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'url' => '网址',
			'nums' => '推荐次数',
			'city_id' => '城市',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
			'state' => 'state',
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

		$criteria->compare('url',$this->url,true);
		
		$criteria->compare('nums',$this->nums,true);
		
		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('TuanRecommend', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCreateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}
	
	public function getStateText()
	{
		return self::$states[$this->state];
	}
}