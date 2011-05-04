<?php

/**
 * This is the model class for table "{{Setting}}".
 *
 * The followings are the available columns in table '{{Setting}}':
 * @property string $parames
 * @property string $values
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
			array('parames', 'unique'),
			array('parames', 'length', 'max'=>32),
			array('values', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parames, values', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider('Setting', array(
			'criteria'=>$criteria,
		));
	}

	public static function getValue($parame)
	{
		$self = self::model()->findByPk($parame);
		if($self) {
			return $self->values;
		}
		return null;
	}

	public static function setValue($parame, $value)
	{
		$self = self::model()->findByPk($parame);
		if(null === $self) {
			$self = new self();
		}
		$self->parames = $parame;
		$self->values = $value;
		return $self->save();
	}
}