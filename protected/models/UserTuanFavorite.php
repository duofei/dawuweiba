<?php

/**
 * This is the model class for table "{{UserTuanFavorite}}".
 *
 * The followings are the available columns in table '{{UserTuanFavorite}}':
 * @property string $id
 * @property string $user_id
 * @property string $tuan_id
 * @property string $create_time
 * @property string $create_ip
 */
class UserTuanFavorite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserTuanFavorite the static model class
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
		return '{{UserTuanFavorite}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, tuan_id', 'required'),
			array('user_id', 'UserTuanUnique', 'on'=>'insert'),
			array('user_id, tuan_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, tuan_id, create_time, create_ip', 'safe', 'on'=>'search'),
		);
	}
	
	public function UserTuanUnique($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('user_id'=>$this->user_id, 'tuan_id'=>$this->tuan_id));
		$count = self::model()->count($criteria);
		if($count) {
			$this->addError($attribute, '您已收藏过此团购');
		}
		return true;
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

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tuannav' => array(self::BELONGS_TO, 'Tuannav', 'tuan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => 'User',
			'tuan_id' => 'Tuan',
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

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('tuan_id',$this->tuan_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('UserTuanFavorite', array(
			'criteria'=>$criteria,
		));
	}
}