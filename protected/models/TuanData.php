<?php

/**
 * This is the model class for table "{{TuanData}}".
 *
 * The followings are the available columns in table '{{TuanData}}':
 * @property string $id
 * @property string $name
 * @property string $mobile
 * @property string $QQ
 * @property string $email
 * @property string $url
 * @property string $adress
 * @property string $create
 * @property string $logo
 * @property string $city_id
 * @property string $online_time
 * @property integer $buy_type
 * @property integer $web_type
 * @property integer $post_frequency
 * @property integer $buy_num
 * @property string $intro
 * @property string $apiurl
 * @property string $apitype
 * @property integer $orderid
 */
class TuanData extends CActiveRecord
{
	/**
	 * 购买类型
	 */
    const BUY_TYPE_TRAD = 0;
    const BUY_TYPE_WEB = 1;
    
    public static $buy_types = array(
        self::BUY_TYPE_TRAD => '线下购买',
        self::BUY_TYPE_WEB => '在线购买',
    );
    
	/**
	 * 组织频率
	 */
    const POST_FRE_LITTLE = 0;
    const POST_FRE_ORDINARY = 1;
    const POST_FRE_HIGH = 2;
    
    public static $post_frequencys = array(
        self::POST_FRE_LITTLE => '很少',
        self::POST_FRE_ORDINARY => '一般',
        self::POST_FRE_HIGH => '较高',
    );
    
	/**
	 * 平均购买人数
	 */
    const BUY_TYPE_50 = 0;
    const BUY_TYPE_100 = 1;
    const BUY_TYPE_500 = 2;
    const BUY_TYPE_1000 = 3;
    const BUY_TYPE_OVER1000 = 4;
    
    public static $buy_nums = array(
        self::BUY_TYPE_50 => '1-50',
        self::BUY_TYPE_100 => '50-100',
        self::BUY_TYPE_500 => '100-500',
        self::BUY_TYPE_1000 => '500-1000',
        self::BUY_TYPE_OVER1000 => '1000人以上',
    );

    /**
     * api类型
     */
    const API_TYPE_GENERAL = 1;
    const API_TYPE_ZUITU = 2;
    const API_TYPE_TUANQILU = 3;
    const API_TYPE_58 = 4;
	public static $apitypes = array(
		self::API_TYPE_GENERAL => '通用API',
		self::API_TYPE_ZUITU => '最土API',
		self::API_TYPE_TUANQILU => '团齐鲁API',
		self::API_TYPE_58 => '58团购API',
	);
	public static $apitypename = array(
		self::API_TYPE_GENERAL => 'general',
		self::API_TYPE_ZUITU => 'zuitu',
		self::API_TYPE_TUANQILU => 'tuanqilu',
		self::API_TYPE_58 => '58',
	);
	public function getApiTypeText()
	{
		return self::$apitypes[$this->apitype];
	}
	public function getApiTypeName() {
		return self::$apitypename[$this->apitype];
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return TuanData the static model class
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
		return '{{TuanData}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, url, logo, city_id, buy_type, web_type, post_frequency, buy_num, mobile', 'required'),
			array('buy_type, web_type, post_frequency, buy_num, city_id, apitype, orderid', 'numerical', 'integerOnly'=>true),
			array('name, email, url, adress, create, logo, apiurl', 'length', 'max'=>255),
			array('email', 'email'),
			array('url, apiurl', 'url'),
			array('mobile, QQ, online_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, mobile, QQ, email, url, adress, create, logo, city_id, online_time, buy_type, web_type, post_frequency, buy_num, intro, apiurl, apitype, orderid', 'safe', 'on'=>'search'),
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
			'name' => '名称',
			'mobile' => '电话',
			'QQ' => 'Qq',
			'email' => '邮箱',
			'url' => 'Url',
			'adress' => '地址',
			'create' => '创办者',
			'logo' => 'Logo',
			'city_id' => '城市',
			'online_time' => '上线时间',
			'buy_type' => '购买类型',
			'web_type' => '网站类型',
			'post_frequency' => '组织频率',
			'buy_num' => '平均购买人数',
			'intro' => '简介',
			'apiurl' => 'api地址',
			'apitype' => 'api类型',
			'orderid' => '排序值',
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

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('QQ',$this->QQ,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('url',$this->url,true);

		$criteria->compare('adress',$this->adress,true);

		$criteria->compare('create',$this->create,true);

		$criteria->compare('logo',$this->logo,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('online_time',$this->online_time,true);

		$criteria->compare('buy_type',$this->buy_type);

		$criteria->compare('web_type',$this->web_type);

		$criteria->compare('post_frequency',$this->post_frequency);

		$criteria->compare('buy_num',$this->buy_num);

		$criteria->compare('intro',$this->intro,true);
		
		$criteria->compare('apiurl',$this->apiurl,true);
		
		$criteria->compare('apitype',$this->apitype,true);
		
		$criteria->compare('orderid',$this->orderid,true);

		return new CActiveDataProvider('TuanData', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取购买类型
	 */
	public function getBuyTypeText()
	{
		return self::$buy_types[$this->buy_type];
	}
	
	/**
	 * 获取组织频率
	 */
	public function getPostFrequencyText()
	{
		return self::$post_frequencys[$this->post_frequency];
	}
	
	/**
	 * 获取平均购买人数
	 */
	public function getBuyNumText()
	{
		return self::$buy_nums[$this->buy_num];
	}

	/**
	 * 获取该城市团购网信息
	 */
	public function getTuanDataOfCity($cityId, $count = 0)
	{
		$cityId = (int)$cityId;
		$condition = new CDbCriteria();
	   	$condition->addCondition('city_id='.$cityId);
	    $condition->order = 'orderid desc, id asc';
	    if ((int)$count) $condition->limit = $count;
    	$tuandata = TuanData::model()->findAll($condition);
    	return $tuandata;
	}
}