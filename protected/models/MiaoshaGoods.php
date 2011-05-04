<?php

/**
 * This is the model class for table "{{MiaoshaGoods}}".
 *
 * The followings are the available columns in table '{{MiaoshaGoods}}':
 * @property integer $id
 * @property integer $miaosha_id
 * @property integer $goods_id
 */
class MiaoshaGoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MiaoshaGoods the static model class
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
		return '{{MiaoshaGoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, miaosha_id, goods_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, miaosha_id, goods_id', 'safe', 'on'=>'search'),
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
			'miaosha' => array(self::BELONGS_TO, 'Miaosha', 'miaosha_id'),
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'miaosha_id' => 'Miaosha',
			'goods_id' => 'Goods',
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

		$criteria->compare('id',$this->id);

		$criteria->compare('miaosha_id',$this->miaosha_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		return new CActiveDataProvider('MiaoshaGoods', array(
			'criteria'=>$criteria,
		));
	}

	public static function getGoodsList($miaosha_id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id));
		$miaoshagoods = MiaoshaGoods::model()->findAll($criteria);
		$array = array();
		foreach ((array)$miaoshagoods as $m) {
			$array[] = $m->goods_id;
		}
		return $array;
	}
}