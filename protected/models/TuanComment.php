<?php

/**
 * This is the model class for table "{{TuanComment}}".
 *
 * The followings are the available columns in table '{{TuanComment}}':
 * @property string $id
 * @property string $tuan_id
 * @property string $user_id
 * @property string $content
 * @property string $create_time
 * @property string $create_ip
 */
class TuanComment extends CActiveRecord
{
    public $validateCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanComment the static model class
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
		return '{{TuanComment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, tuan_id, user_id', 'required'),
			array('tuan_id, user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
			array('content', 'CdcDenyWordsValidator'),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tuan_id, user_id, content, create_time, create_ip', 'safe', 'on'=>'search'),
		);
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'tuan_id' => 'Tuan',
			'user_id' => 'User',
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

		$criteria->compare('tuan_id',$this->tuan_id,true);

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('TuanComment', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCreateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}

	/**
	 * 获取某团购的评论
	 */
	public function getTuanComment($id)
	{
		$id = (int)$id;
		$condition = new CDbCriteria();
   		$condition->addCondition('tuan_id='.$id);
    	$condition->order = 'id desc';
	    $tuanComment = TuanComment::model()->findAll($condition);
    	return $tuanComment;
	}
}