<?php

/**
 * This is the model class for table "{{Message}}".
 *
 * The followings are the available columns in table '{{Message}}':
 * @property integer $id
 * @property integer $fromuid
 * @property integer $touid
 * @property string $title
 * @property string $content
 * @property integer $is_read
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 */
class Message extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Message the static model class
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
		return '{{Message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, touid', 'required'),
			array('title', 'length', 'max'=>255),
			array('create_time, update_time, is_read', 'numerical', 'integerOnly'=>true),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, create_time, create_ip', 'safe', 'on'=>'search'),
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

	
	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
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
			'fromuid' => '发送用户id ',
			'touid' => '接收用户id',
			'is_read' => '是否已读',
			'title' => '标题',
			'content' => '内容',
			'create_time' => '发表时间',
			'create_ip' => '发表IP',
			'update_time' => '已读时间',
			'update_ip' => '已读IP',
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
		$criteria->compare('fromuid',$this->fromuid,true);
		$criteria->compare('touid',$this->touid,true);
		$criteria->compare('is_read',$this->is_read,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_ip',$this->update_ip,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('Message', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTitleLinkHtml()
	{
		return l($this->title, url('my/message/show',array('msgid'=>$this->id)));
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
	 * 发送一条站内短消息
	 * @param integer $touid
	 * @param string $title
	 * @param string $content
	 * @param integer $fromuid
	 */
	static public function sendMessage($touid, $title, $content, $fromuid=null) {
		$model = new self();
		$model->touid = intval($touid);
		$model->title = $title;
		$model->content = $content;
		if($fromuid == null && user()->id) {
			$fromuid = user()->id;
		}
		$model->fromuid = intval($fromuid);
		if($model->save()) {
			return $model;
		} else {
			return false;
		}
	}

	static public function getNoReadMsgCount($userId = NULL)
	{
		if($userId == NULL && user()->id) {
			$userId	= user()->id;
		}
		$userId = intval($userId);
		if($userId == 0) return Null;
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('touid'=>$userId, 'is_read'=>STATE_DISABLED));
		$count = self::model()->count($criteria);
		return $count;
	}
}