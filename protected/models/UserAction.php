<?php

/**
 * This is the model class for table "{{UserAction}}".
 *
 * The followings are the available columns in table '{{UserAction}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $atype
 * @property string $content
 * @property integer $create_time
 * @property string $create_ip
 */
class UserAction extends CActiveRecord
{
	const TYPE_USER_REGISTER = 1;
	const TYPE_SHOP_REGISTER = 2;
	const TYPE_MAKE_NEWORDER = 3;
	
    public static $types = array(
    	self::TYPE_USER_REGISTER => '欢迎<span class="useraction">{user}</span>成为我爱外卖会员',
        self::TYPE_SHOP_REGISTER => '欢迎<span class="useraction">[{shop}]</span>加盟我爱外卖',
        self::TYPE_MAKE_NEWORDER => '<span class="useraction">{user}</span>在<span class="useraction">[{shop}]</span>下了新订单',        
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserAction the static model class
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
		return '{{UserAction}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, content, atype', 'required'),
			array('user_id, create_time, atype', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, atype, content, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'user_id' => '用户',
			'atype' => '操作类型',
			'content' => '操作记录',
			'create_time' => '操作时间',
			'create_ip' => 'Ip',
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

		$criteria->compare('atype',$this->atype);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('UserAction', array(
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
	 * 增加最新动态 
	 */
	public static function addNewAction($type=1, $username=null, $shopname=null)
	{
		$useraction = new self();
		$useraction->atype = $type;
		$useraction->content = str_replace(array('{user}', '{shop}'), array($username, $shopname), self::$types[$type]);
		$useraction->save();
	}
	
	/**
	 * 获取最新动态 
	 */
	public static function getContentList($num=4)
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'create_time desc';
		$criteria->limit = $num;
		$list = self::model()->findAll($criteria);
		return $list;
	}
}