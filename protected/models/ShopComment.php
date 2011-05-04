<?php

/**
 * This is the model class for table "{{ShopComment}}".
 *
 * The followings are the available columns in table '{{ShopComment}}':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $create_time
 * @property string $create_ip
 * @property string $content
 * @property string $reply
 * @property integer $reply_time
 * @property string $reply_ip
 */
class ShopComment extends CActiveRecord
{
	public $validateCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopComment the static model class
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
		return '{{ShopComment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, shop_id, order_id, content', 'required', 'on'=>'insert'),
			array('content', 'required', 'on'=>'reminder'),
			array('reply', 'required', 'on'=>'update'),
			array('shop_id, user_id, order_id, create_time, reply_time', 'numerical', 'integerOnly'=>true),
			array('create_ip, reply_ip', 'length', 'max'=>15, 'min'=>7),
			array('content, reply', 'length', 'max'=>255),
			array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert,reminder'),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			array('content', 'CdcDenyWordsValidator'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, user_id, order_id, create_time, create_ip, content, reply, reply_time, reply_ip', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'shop_id' => '商铺',
			'user_id' => '用户',
			'order_id' => '订单',
			'create_time' => '创建时间',
			'create_ip' => 'Ip',
			'content' => '留言',
			'reply' => '商家回复',
			'reply_time' => '回复时间',
			'reply_ip' => 'Ip',
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

		$criteria->compare('user_id',$this->user_id,true);
		
		$criteria->compare('order_id',$this->order_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('reply',$this->reply,true);

		$criteria->compare('reply_time',$this->reply_time,true);

		$criteria->compare('reply_ip',$this->reply_ip,true);

		return new CActiveDataProvider('ShopComment', array(
			'criteria'=>$criteria,
		));
	}
			
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
		return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
	       	$counters = array('comment_nums' => 1);
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	    }
	    return true;
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
	 * 格式化回复时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getReplyDateTimeText()
	{
		return date(param('formatDateTime'), $this->reply_time);
	}
	
	/**
	 * 格式化回复时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortReplyDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->reply_time);
	}
	
	/**
	 * 格式化回复时间
	 * 输出时间格式：Y-m-d
	 */
	public function getReplyDateText()
	{
		return date(param('formatDate'), $this->reply_time);
	}
	
	/**
	 * 格式化回复时间
	 * 输出时间格式：H:i:s
	 */
	public function getReplyTimeText()
	{
		return date(param('formatTime'), $this->reply_time);
	}
	
	/**
	 * 格式化回复时间
	 * 输出时间格式：H:i
	 */
	public function getShortReplyTimeText()
	{
		return date(param('formatShortTime'), $this->reply_time);
	}
}