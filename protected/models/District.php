<?php

/**
 * This is the model class for table "{{District}}".
 *
 * The followings are the available columns in table '{{District}}':
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property double $map_x
 * @property double $map_y
 */
class District extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return District the static model class
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
		return '{{District}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('map_x, map_y', 'numerical'),
			array('name', 'length', 'max'=>60),
			array('name, city_id', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, city_id, name, map_x, map_y', 'safe', 'on'=>'search'),
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
			'buildings' => array(self::HAS_MANY, 'Building', 'district_id'),
			'userAddress' => array(self::HAS_MANY, 'UserAddress', 'district_id'),
			'user' => array(self::HAS_MANY, 'User', 'district_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
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
			'map_x' => '地图坐标x', 
			'map_y' => '地图坐标y'
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

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('name',$this->name,true);
		$criteria->compare('map_x',$this->map_x,true);
		$criteria->compare('map_y',$this->map_y,true);

		return new CActiveDataProvider('District', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
     *  返回行政区域 id=>name 形式的数组 
     */
    public static function getDistrictArray($city_id=0)
    {
    	$criteria = new CDbCriteria();
    	if($city_id > 0) {
    		$criteria->addCondition('city_id = ' . $city_id);
    	}
    	$district = District::model()->findAll($criteria);
    	$districtArray = array();
		foreach ($district as $row) {
			$districtArray[$row->id] = $row->name;
		}
		return $districtArray;
    }
}