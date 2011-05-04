<?php

/**
 * This is the model class for table "{{DayList}}".
 *
 * The followings are the available columns in table '{{DayList}}':
 * @property string $shop_id
 * @property string $goods_id
 * @property integer $week
 */
class DayList extends CActiveRecord
{
	public static $weeks = array(
		WEEK_MONDAY => '星期一',
		WEEK_TUESDAY => '星期二',
		WEEK_WEDNESDAY => '星期三',
		WEEK_THURSDAY => '星期四',
		WEEK_FRIDAY => '星期五',
		WEEK_SATURDAY => '星期六',
		WEEK_SUNDAY => '星期天',
	);
	/**
	 * Returns the static model of the specified AR class.
	 * @return DayList the static model class
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
		return '{{DayList}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('week', 'numerical', 'integerOnly'=>true),
			array('shop_id, goods_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shop_id, goods_id, week', 'safe', 'on'=>'search'),
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
			'shop_id' => 'Shop',
			'goods_id' => 'Goods',
			'week' => 'Week',
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('week',$this->week);

		return new CActiveDataProvider('DayList', array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getWeeksText() {
		return self::$weeks[$this->week];
	}
}