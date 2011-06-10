<?php

/**
 * This is the model class for table "{{Correction}}".
 *
 * The followings are the available columns in table '{{Correction}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $create_time
 * @property string $create_ip
 * @property string $content
 * @property string $source
 */
class Correction extends CActiveRecord
{
    public $validateCode;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Feedback the static model class
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
		return '{{Correction}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('content, source', 'length', 'max'=>255),
			array('user_id', 'default', 'value'=>user()->id, 'on'=>'insert', 'setOnEmpty'=>false),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, create_time, create_ip, content, source', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
	
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'content' => '内容',
			'source' => '来源',
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

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('source',$this->source,true);

		return new CActiveDataProvider('Correction', array(
			'criteria'=>$criteria,
		));
	}

	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getCreateDateTimeText()
	{
		return date(param('formatDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortCreateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d
	 */
	public function getCreateDateText()
	{
		return date(param('formatDate'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：H:i:s
	 */
	public function getCreateTimeText()
	{
		return date(param('formatTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：H:i
	 */
	public function getShortCreateTimeText()
	{
		return date(param('formatShortTime'), $this->create_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getUpdateDateTimeText()
	{
		return date(param('formatDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortUpdateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d
	 */
	public function getUpdateDateText()
	{
		return date(param('formatDate'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i:s
	 */
	public function getUpdateTimeText()
	{
		return date(param('formatTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i
	 */
	public function getShortUpdateTimeText()
	{
		return date(param('formatShortTime'), $this->update_time);
	}
}