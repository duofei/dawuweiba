<?php

/**
 * This is the model class for table "{{TuanSecondHand}}".
 *
 * The followings are the available columns in table '{{TuanSecondHand}}':
 * @property string $id
 * @property integer $trade_sort
 * @property integer $category_id
 * @property string $title
 * @property string $content
 * @property string $nums
 * @property string $price
 * @property string $mobile
 * @property string $url
 * @property string $user_id
 * @property string $city_id
 * @property integer $state
 * @property string $create_time
 * @property string $create_ip
 */
class TuanSecondHand extends CActiveRecord
{
    public $validateCode;
	/**
	 * 类型
	 */
    const TRADE_SORT_SELL = 1;
    const TRADE_SORT_BUY = 2;
    
    public static $trade_sorts = array(
        self::TRADE_SORT_SELL => '转让',
        self::TRADE_SORT_BUY  => '求购',
    );
    
    public static $states = array(
    	STATE_ENABLED => '成交',
    	STATE_DISABLED => '正常',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanSecondHand the static model class
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
		return '{{TuanSecondHand}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, trade_sort, category_id, title, nums, price, mobile, url, user_id, city_id', 'required'),
			array('trade_sort, category_id, city_id, nums, user_id, create_time, category_id, state', 'numerical', 'integerOnly'=>true),
			array('title, url', 'length', 'max'=>200),
			array('url', 'url'),
			array('price', 'numerical', 'max'=>999.99, 'min'=>0),
			array('mobile, create_ip', 'length', 'max'=>20),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, trade_sort, category_id, title, content, nums, price, mobile, url, user_id, state, create_time, create_ip, city_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'TuanCategory', 'category_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'trade_sort' => '交易类别',
			'category_id' => '分类',
			'title' => '标题',
			'content' => '内容',
			'nums' => '交易数量',
			'price' => '交易金额',
			'mobile' => '联系电话',
			'url' => '连接地址',
			'user_id' => '发帖人',
			'city_id' => '城市',
			'state' => '状态',
			'create_time' => '创建时间',
			'create_ip' => '创建ip',
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

		$criteria->compare('trade_sort',$this->trade_sort);

		$criteria->compare('category_id',$this->category_id);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('nums',$this->nums,true);

		$criteria->compare('price',$this->price,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('url',$this->url,true);

		$criteria->compare('user_id',$this->user_id,true);
		
		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('state',$this->state,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('TuanSecondHand', array(
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
	 * 获取交易类别
	 * Enter description here ...
	 */
	public function getTradeSortText()
	{
	    return self::$trade_sorts[$this->trade_sort];
	}
	
	/**
	 * 获取交易状态
	 * Enter description here ...
	 */
	public function getStateText()
	{
	    return self::$states[$this->state];
	}
	
	/**
	 * 获取发布的二手转让求购
	 */
	public function getTuanSecondOfCity($cityId)
	{
		$cityId = (int)$cityId;
		$condition = new CDbCriteria();
	   	$condition->addCondition('city_id='.$cityId);
	    $condition->order = 'id desc';
	    $condition->limit = '10';
    	$tuansecond = TuanSecondHand::model()->findAll($condition);
    	return $tuansecond;
	}
	
	/*
	 * 截取标题
	 */
	public function getTitleSub()
	{
		if (strlen($this->title) >= 54) {
			return mb_substr($this->title,'0','16','utf-8').'...';
		}else{
			return $this->title;
		}
	}
}