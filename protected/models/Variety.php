<?php

/**
 * This is the model class for table "{{Variety}}".
 *
 * The followings are the available columns in table '{{Variety}}':
 * @property integer $id
 * @property string $name
 */
class Variety extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Variety the static model class
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
		return '{{Variety}}';
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
			array('name', 'length', 'max'=>60),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'CakeGoods' => array(self::MANY_MANY, 'CakeGoods', '{{CakeVariety}}(variety_id, goods_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => '品种名称',
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

		return new CActiveDataProvider('Variety', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取品种key=>value形式的数组
	 */
	public static function getVarietyArray()
	{
		$array = array();
		$rows = self::model()->findAll();
		foreach ($rows as $v) {
			$array[$v->id] = $v->name;
		}
		return $array;
	}
}