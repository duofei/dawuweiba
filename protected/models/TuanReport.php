<?php

/**
 * This is the model class for table "{{TuanReport}}".
 *
 * The followings are the available columns in table '{{TuanReport}}':
 * @property string $id
 * @property string $email
 * @property integer $type
 * @property integer $tuan_id
 * @property integer $city_id
 * @property string $content
 * @property string $create_time
 * @property string $create_ip
 */
class TuanReport extends CActiveRecord
{
    public $validateCode;
	/**
	 * 购买类型
	 */
    const TYPE_INFO_BAD = 1;
    const TYPE_WEB_ERR = 2;
    const TYPE_INFO_ERR = 3;
    const TYPE_OTHER = 4;
    
    public static $types = array(
        self::TYPE_INFO_BAD => '不良信息',
        self::TYPE_WEB_ERR => '网页有错',
        self::TYPE_INFO_ERR => '信息错误',
        self::TYPE_OTHER => '其它',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanReport the static model class
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
		return '{{TuanReport}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, tuan_id, city_id', 'required'),
			array('type, tuan_id, city_id, create_time', 'numerical', 'integerOnly'=>true),
			array('email', 'email'),
			array('email', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>20),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, type, tuan_id, city_id, content, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'tuannav' => array(self::BELONGS_TO, 'Tuannav', 'tuan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'email' => '邮箱',
			'type' => '原因',
			'tuan_id' => 'tuan_id',
			'city_id' => 'city_id',
			'content' => 'Content',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
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

		$criteria->compare('email',$this->email,true);

		$criteria->compare('type',$this->type);
		
		$criteria->compare('tuan_id',$this->tuan_id);
		
		$criteria->compare('city_id',$this->city_id);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('TuanReport', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取举报原因
	 */
	public function getTypeText()
	{
		return self::$types[$this->type];
	}
	
	public function getCreateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}
}