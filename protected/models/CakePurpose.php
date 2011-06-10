<?php

/**
 * This is the model class for table "{{CakePurpose}}".
 *
 * The followings are the available columns in table '{{CakePurpose}}':
 * @property integer $goods_id
 * @property integer $purpose_id
 */
class CakePurpose extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CakePurpose the static model class
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
		return '{{CakePurpose}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, purpose_id', 'required'),
			array('goods_id, purpose_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('goods_id, purpose_id', 'safe', 'on'=>'search'),
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
			//'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
			//'cakeGoods' => array(self::BELONGS_TO, 'CakeGoods', 'goods_id'),
			//'purpose' => array(self::BELONGS_TO, 'Purpose', 'purpose_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'goods_id' => '商品',
			'purpose_id' => '用途',
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

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('purpose_id',$this->purpose_id,true);

		return new CActiveDataProvider('CakePurpose', array(
			'criteria'=>$criteria,
		));
	}
}