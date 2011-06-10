<?php

/**
 * This is the model class for table "{{ShopCommonReply}}".
 *
 * The followings are the available columns in table '{{ShopCommonReply}}':
 * @property string $id
 * @property string $shop_id
 * @property string $content
 * @property string $create_time
 * @property string $create_ip
 */
class ShopCommonReply extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopCommonReply the static model class
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
		return '{{ShopCommonReply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, content', 'required', 'on'=>'insert'),
			array('shop_id, create_time', 'length', 'max'=>10),
			array('content', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15),
			array('content', 'CdcDenyWordsValidator'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, content, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'shop_id' => '店铺',
			'content' => '内容',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
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

		$criteria->compare('content',$this->content,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('ShopCommonReply', array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	    		'updateAttribute' => NULL,
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	        	'updateAttribute' => NULL,
	        )
	    );
	}
	
}