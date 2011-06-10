<?php

/**
 * This is the model class for table "{{ShopCaiji}}".
 *
 * The followings are the available columns in table '{{ShopCaiji}}':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $city_id
 * @property string $shop_name
 * @property string $address
 * @property string $telphone
 * @property float $transport_amount
 * @property string $transport_time
 * @property string $transport_condition
 * @property float $dispatching_amount
 * @property string $goods_name
 * @property string $goods_img
 * @property string $goods_price
 * @property integer $state
 */
class ShopCaiji extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopCaiji the static model class
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
		return '{{ShopCaiji}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_name', 'required'),
			array('shop_id, city_id, state', 'numerical', 'integerOnly'=>true),
			array('transport_amount, dispatching_amount', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('shop_name, address, telphone, transport_time, transport_condition', 'length', 'max'=>255),
			array('goods_name, goods_img, goods_price', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, city_id, shop_name, address, telphone, transport_amount, transport_time, transport_condition, dispatching_amount, goods_name, goods_img, goods_price, state', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'shop_id' => 'Shop',
			'city_id' => 'City',
			'shop_name' => 'Shop Name',
			'address' => 'Address',
			'telphone' => 'Telphone',
			'transport_amount' => 'Transport Amount',
			'transport_time' => 'Transport Time',
			'transport_condition' => 'Transport Condition',
			'dispatching_amount' => 'Dispatching Amount',
			'goods_name' => 'Goods Name',
			'goods_img' => 'Goods Img',
			'goods_price' => 'Goods Price',
			'state' => 'State',
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('shop_name',$this->shop_name,true);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('transport_amount',$this->transport_amount,true);

		$criteria->compare('transport_time',$this->transport_time,true);

		$criteria->compare('transport_condition',$this->transport_condition,true);

		$criteria->compare('dispatching_amount',$this->dispatching_amount,true);

		$criteria->compare('goods_name',$this->goods_name,true);

		$criteria->compare('goods_img',$this->goods_img,true);

		$criteria->compare('goods_price',$this->goods_price,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('ShopCaiji', array(
			'criteria'=>$criteria,
		));
	}
}