<?php

/**
 * This is the model class for table "{{Setting}}".
 *
 * The followings are the available columns in table '{{Setting}}':
 * @property string $parames
 * @property string $values
 * @property integer $city_id
 */
class Setting extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Setting the static model class
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
		return '{{Setting}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parames', 'required'),
			array('parames', 'checkParamesCityUnique', 'on'=>'insert'),
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('parames', 'length', 'max'=>32),
			array('values', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parames, values, city_id', 'safe', 'on'=>'search'),
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
			'parames' => '参数',
			'values' => '内容值',
			'city_id' => '城市',
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

		$criteria->compare('parames',$this->parames,true);
		$criteria->compare('values',$this->values,true);
		$criteria->compare('city_id',$this->city_id,true);

		return new CActiveDataProvider('Setting', array(
			'criteria'=>$criteria,
		));
	}

	public function checkParamesCityUnique($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city_id, 'parames'=>$this->parames));
		$count = self::model()->count($criteria);
		if($count) {
			$this->addError($attribute, '此参数：' . $this->parames. ' 已存在！');
		}
		return true;
	}
	
	public static function getValue($parame, $cityId=null)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('parames'=>$parame, 'city_id'=>$cityId));
		$self = self::model()->find($criteria);
		if($self) {
			return $self->values;
		}
		return null;
	}

	public static function setValue($parame, $value, $cityId=null)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('parames'=>$parame, 'city_id'=>$cityId));
		$self = self::model()->find($criteria);
		if(null === $self) {
			$self = new self();
		}
		$self->parames = $parame;
		$self->values = $value;
		$self->city_id = intval($cityId);
		$self->save();
		echo CHtml::errorSummary($self);
	}
}