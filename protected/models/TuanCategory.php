<?php

/**
 * This is the model class for table "{{TuanCategory}}".
 *
 * The followings are the available columns in table '{{TuanCategory}}':
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property integer $state
 */
class TuanCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanCategory the static model class
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
		return '{{TuanCategory}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, parent_id, name', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parent_id, state', 'safe', 'on'=>'search'),
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
			'name' => '分类名称',
			'parent_id' => '父分类id',
			'state' => '状态',
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

		$criteria->compare('parent_id',$this->parent_id,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('TuanCategory', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获取分类
	 */
	public function getTuanCategory()
	{
		$condition = new CDbCriteria();
	   	$condition->addCondition('state='.STATE_ENABLED);
    	$category = TuanCategory::model()->findAll($condition);
    	return $category;
	}
}