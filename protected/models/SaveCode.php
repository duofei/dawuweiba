<?php

/**
 * This is the model class for table "{{SaveCode}}".
 *
 * The followings are the available columns in table '{{SaveCode}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $code
 * @property float $price
 * @property integer $end_time
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $state
 */
class SaveCode extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SaveCode the static model class
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
		return '{{SaveCode}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, user_id, order_id, end_time, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>255),
			array('price', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('update_ip, create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, order_id, code, price, end_time, create_time, create_ip, update_time, update_ip, state', 'safe', 'on'=>'search'),
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
			'user_id' => '用户',
			'order_id' => '订单',
			'code' => '优惠码',
			'price' => '优惠价',
			'end_time' => '有效时间',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
			'update_time' => 'Update Time',
			'update_ip' => 'Update Ip',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_ip',$this->create_ip,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_ip',$this->update_ip,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('SaveCode', array(
			'criteria'=>$criteria,
		));
	}
}