<?php

/**
 * This is the model class for table "{{Building}}".
 *
 * The followings are the available columns in table '{{Building}}':
 * @property integer $id
 * @property integer $district_id
 * @property string $name
 * @property string $address
 * @property double $map_x
 * @property double $map_y
 * @property integer $type
 * @property integer $state
 * @property string $letter
 * @property integer $shop_nums
 * @property integer $use_nums
 */
class Building_bak extends CActiveRecord
{
	public $validateCode;
	
	const TYPE_OFFICE = 1;
	const TYPE_SUBDISTRICT = 2;

    public static $types = array(
    	self::TYPE_OFFICE => '写字楼',
        self::TYPE_SUBDISTRICT => '小区',
    );
    
    public static $states = array(
    	STATE_DISABLED => '未审核',
    	STATE_ENABLED => '已审核'
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Building the static model class
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
		return '{{Building}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, map_x, map_y', 'required'),
			array('type, state, district_id, shop_nums, use_nums', 'numerical', 'integerOnly'=>true),
			array('map_x, map_y', 'numerical'),
			array('letter', 'length', 'is'=>1),
			array('name, address', 'length', 'max'=>255, 'min'=>'3'),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'userpost'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, district_id, name, address, map_x, map_y, type, state', 'safe', 'on'=>'search'),
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
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
			'userAddresses' => array(self::HAS_MANY, 'UserAddress', 'building_id'),
		);
	}
	
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
		return true;
	}
	
	protected function beforeSave()
	{
		parent::afterSave();
		$this->letter = CdcBetaTools::getFirstLetter($this->name);
		return true;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'district_id' => '行政区域',
			'name' => '名称',
			'address' => '地址',
			'map_x' => '地图坐标X',
			'map_y' => '地图坐标Y',
			'type' => '类型',
			'state' => '状态',
			'letter' => '字母',
			'shop_nums' => '商家数量',
			'use_nums' => '使用数量',
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

		$criteria->compare('district_id',$this->district_id,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('map_x',$this->map_x,true);

		$criteria->compare('map_y',$this->map_y,true);

		$criteria->compare('type',$this->type,true);

		$criteria->compare('state',$this->state,true);

		$criteria->compare('letter',$this->letter,true);

		$criteria->compare('shop_nums',$this->shop_nums,true);

		$criteria->compare('use_nums',$this->use_nums,true);

		return new CActiveDataProvider('Building', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTypeText()
	{
		return self::$types[$this->type];
	}
	public function getStateText()
	{
		return self::$states[$this->state];
	}
}