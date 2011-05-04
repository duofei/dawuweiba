<?php

/**
 * This is the model class for table "{{UserAddress}}".
 *
 * The followings are the available columns in table '{{UserAddress}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $consignee
 * @property string $address
 * @property string $telphone
 * @property string $mobile
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $building_id
 * @property double $map_x
 * @property double $map_y
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $create_time
 * @property string $create_ip
 * @property string $is_default
 *
 */
class UserAddress extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserAddress the static model class
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
		return '{{UserAddress}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, city_id, building_id, consignee, address, telphone', 'required'),
			array('user_id, city_id, building_id, district_id, update_time, create_time, is_default', 'numerical', 'integerOnly'=>true),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			array('map_x, map_y', 'numerical'),
			array('consignee', 'length', 'max'=>60),
			array('address', 'length', 'max'=>255),
			array('telphone, mobile', 'length', 'max'=>20),
			array('telphone, mobile', 'match', 'pattern'=>'/(1\d{10})|(0\d{2,3}[-——]?\d{7,8})/'),
			array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, consignee, address, telphone, mobile, city_id, building_id, district_id, create_time, update_time, create_ip, update_ip, is_default', 'safe', 'on'=>'search'),
		);
	}

	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
			'building' => array(self::BELONGS_TO, 'Location', 'building_id', 'condition'=>'type = ' . Location::TYPE_OFFICE),
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
			'consignee' => '收货人',
			'address' => '详细地址',
			'telphone' => '联系电话',
			'mobile' => '备选电话',
			'city_id' => '分站城市',
			'building_id' => '写字楼',
			'district_id' => '行政区域',
			'map_x' => '地图坐标x',
			'map_y' => '地图坐标y',
			'update_time' => '更新时间',
			'update_ip' => 'Ip',
			'create_time' => '添加时间',
			'create_ip' => 'Ip',
			'is_default' => '设成默认',
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

		$criteria->compare('consignee',$this->consignee,true);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('district_id',$this->district_id,true);
		
		$criteria->compare('building_id',$this->building_id,true);
		
		$criteria->compare('map_x',$this->map_x,true);
		
		$criteria->compare('map_y',$this->map_y,true);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		$criteria->compare('is_default',$this->is_default,true);

		return new CActiveDataProvider('UserAddress', array(
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
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getUpdateDateTimeText()
	{
		return date(param('formatDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortUpdateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d
	 */
	public function getUpdateDateText()
	{
		return date(param('formatDate'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i:s
	 */
	public function getUpdateTimeText()
	{
		return date(param('formatTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i
	 */
	public function getShortUpdateTimeText()
	{
		return date(param('formatShortTime'), $this->update_time);
	}
}