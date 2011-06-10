<?php

/**
 * This is the model class for table "{{IpAddress}}".
 *
 * The followings are the available columns in table '{{IpAddress}}':
 * @property integer $id
 * @property string $startip
 * @property string $endip
 * @property string $city
 * @property string $code
 */
class IpAddress extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return IpAddress the static model class
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
		return '{{IpAddress}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('startip, endip, city, code', 'required'),
			array('startip, endip', 'length', 'max'=>15, 'min'=>7),
			array('city', 'length', 'max'=>60),
			array('code', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, startip, endip, city, code', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'startip' => '开始IP段',
			'endip' => '结束IP段',
			'city' => '城市',
			'code' => '区号',
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

		$criteria->compare('startip',$this->startip,true);

		$criteria->compare('endip',$this->endip,true);

		$criteria->compare('city',$this->city,true);

		$criteria->compare('code',$this->code,true);

		return new CActiveDataProvider('IpAddress', array(
			'criteria'=>$criteria,
		));
	}
}