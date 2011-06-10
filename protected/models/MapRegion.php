<?php

/**
 * This is the model class for table "{{MapRegion}}".
 *
 * The followings are the available columns in table '{{MapRegion}}':
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $region
 * @property integer $create_time
 * @property string $create_ip
 */
class MapRegion extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return MapRegion the static model class
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
		return '{{MapRegion}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, city_id, region', 'required'),
			array('city_id, create_time', 'numerical', 'integerOnly'=>true),
			array('name, region', 'length', 'max'=>255, 'min'=>'3'),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('region', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, city_id, name, region, create_ip, create_time', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
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
			'city_id' => '城市',
			'name' => '名称',
			'region' => '地图范围坐标',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		return new CActiveDataProvider('MapRegion', array(
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