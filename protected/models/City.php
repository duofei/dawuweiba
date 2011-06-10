<?php

/**
 * This is the model class for table "{{City}}".
 *
 * The followings are the available columns in table '{{City}}':
 * @property integer $id
 * @property string $name
 * @property double $map_x
 * @property double $map_y
 */
class City extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return City the static model class
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
		return '{{City}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('map_x, map_y', 'numerical'),
			array('name', 'length', 'max'=>60),
			array('name', 'checkNameUnique', 'on'=>'insert, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, map_x, map_y', 'safe', 'on'=>'search'),
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
			'districts' => array(self::HAS_MANY, 'District', 'city_id'),
			'searchLogs' => array(self::HAS_MANY, 'SearchLog', 'city_id'),
			'users' => array(self::HAS_MANY, 'User', 'city_id'),
			'userAddresses' => array(self::HAS_MANY, 'UserAddress', 'city_id'),
			'location' => array(self::HAS_MANY, 'Location', 'city_id'),
			'mapRegion' => array(self::HAS_MANY, 'MapRegion', 'city_id'),
		);
	}

	/**
	 * 验证城市唯一性
	 */
	public function checkNameUnique($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('name'=>$this->name));
		$count = self::model()->count($criteria);
		if($count) {
			$this->addError($attribute, '城市：' . $this->name. ' 已存在！');
		}
		return true;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '城市',
			'map_x' => '城市中心地图坐标x',
			'map_y' => '城市中心地图坐标y',
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

		$criteria->compare('name',$this->name,true);
		
		$criteria->compare('map_x',$this->map_x,true);
		
		$criteria->compare('map_y',$this->map_y,true);

		return new CActiveDataProvider('City', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
     *  返回城市 id=>name 形式的数组
     */
    public static function getCityArray()
    {
    	$city = City::model()->findAll();
    	$cityArray = array();
		foreach ($city as $row) {
			$cityArray[$row->id] = $row->name;
		}
		return $cityArray;
    }
}