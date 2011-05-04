<?php

/**
 * This is the model class for table "{{ShopSuggest}}".
 *
 * The followings are the available columns in table '{{ShopSuggest}}':
 * @property integer $id
 * @property integer $city_id
 * @property string $email
 * @property string $telphone
 * @property string $shop_address
 * @property string $shop_name
 * @property string $comment
 * @property string $remark
 * @property integer $create_time
 * @property string $create_ip
 */
class ShopSuggest extends CActiveRecord
{
    public $validateCode;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopSuggest the static model class
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
		return '{{ShopSuggest}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_address, shop_name, city_id', 'required'),
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('comment, remark, email, shop_address', 'length', 'max'=>255),
			array('email', 'email'),
			array('telphone', 'match', 'pattern'=>'/(1\d{10})|(0\d{2,3}[-——]?\d{7,8})/', 'on'=>'insert'),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, create_time, create_ip, telphone, shop_address, shop_name, comment, remark', 'safe', 'on'=>'search'),
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
		    'reply' => array(self::HAS_MANY, 'ShopSuggest', 'post_id'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => '城市',
			'email' => '邮箱',
			'telphone' => '电话',
			'shop_address' => '商铺地址',
			'shop_name' => '商铺名称',
			'comment' => '留言',
			'remark' => '备注',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
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
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telphone',$this->telphone,true);
		$criteria->compare('shop_address',$this->shop_address,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_ip',$this->create_ip,true);
		
		return new CActiveDataProvider('ShopSuggest', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
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
	
}