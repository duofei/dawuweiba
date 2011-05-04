<?php

/**
 * This is the model class for table "{{Location}}".
 *
 * The followings are the available columns in table '{{Location}}':
 * @property integer $id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $food_nums
 * @property integer $cake_nums
 * @property string $name
 * @property double $map_x
 * @property double $map_y
 * @property string $category
 * @property string $address
 * @property integer $state
 * @property integer $type
 * @property string $pinyin
 * @property string $letter
 * @property integer $use_nums
 * @property integer $source
 * @property integer $create_time
 * @property string $create_ip
 */
class Location extends CActiveRecord
{
	public $validateCode;
	
    const DELIMITER_SEARCH_HISTORY = ',';
    const DELIMITER_SEARCH_LAT_LON = '|';
    
    public static $states = array(
    	STATE_DISABLED => '未审核',
    	STATE_ENABLED => '已审核'
    );
    
    /**
     * 来源
     */
    const SOURCE_SYSTEM = 0;
    const SOURCE_SEARCH = 1;
    const SOURCE_USERPOST = 2;
    public static $sources = array(
    	self::SOURCE_SYSTEM => '系统添加',
    	self::SOURCE_SEARCH => '搜索添加',
    	self::SOURCE_USERPOST => '用户提交'
    );
    public function getSourceText()
    {
    	return self::$sources[$this->source];
    }
    
    /**
     * 地址类型
	 */
    //const TYPE_ADDRESS = 0;
	const TYPE_OFFICE = 1;
	const TYPE_SUBDISTRICT = 2;
    public static $types = array(
    	//self::TYPE_ADDRESS => '地址',
    	self::TYPE_OFFICE => '写字楼',
        self::TYPE_SUBDISTRICT => '小区',
    );
	public function getTypeText()
	{
		return self::$types[$this->type];
	}
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Location the static model class
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
		return '{{Location}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, map_x, map_y, city_id', 'required'),
			array('city_id, district_id, type, food_nums, cake_nums, state, use_nums, source, create_time', 'numerical', 'integerOnly'=>true),
			array('map_x, map_y', 'numerical'),
			array('name, address, pinyin', 'length', 'max'=>255),
			array('name', 'checkNameCityUnique', 'on'=>'insert, userpost'),
			array('category', 'length', 'max'=>100),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('letter', 'length', 'is'=>1),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'userpost'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, map_x, map_y, category, city_id, address, food_nums, cake_nums, pinyin, use_nums, source, create_time, create_ip', 'safe', 'on'=>'search'),
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
	 * 验证在一个城市下地址唯一性
	 */
	public function checkNameCityUnique($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city_id, 'name'=>$this->name));
		$count = self::model()->count($criteria);
		if($count) {
			$this->addError($attribute, '地址：' . $this->name. ' 已存在！');
		}
		return true;
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
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
			'userAddresses' => array(self::HAS_MANY, 'UserAddress', 'building_id'),
			'Tags' => array(self::MANY_MANY, 'Tag', '{{LocationTag}}(location_id, tag_id)'),
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
			'name' => '地址名称',
			'map_x' => '地图坐标X',
			'map_y' => '地图坐标Y',
			'category' => '分类',
			'address' => '地址',
			'food_nums' => '美食商家数量',
			'cake_nums' => '蛋糕商家数量',
			'state' => '审核',
			'pinyin' => '名称全拼',
			'use_nums' => '使用次数',
			'district_id' => '行政区域',
			'type' => '类型',
			'letter' => '字母',
			'source' => '来源',
			'create_time' => '添加时间',
			'create_ip' => 'ip',
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

		$criteria->compare('name',$this->name,true);

		$criteria->compare('map_x',$this->map_x);

		$criteria->compare('map_y',$this->map_y);
		
		$criteria->compare('category',$this->category);
		
		$criteria->compare('address',$this->address);
		
		$criteria->compare('food_nums',$this->food_nums);
		
		$criteria->compare('cake_nums',$this->cake_nums);
		
		$criteria->compare('state',$this->state);
		
		$criteria->compare('pinyin',$this->pinyin);
		
		$criteria->compare('use_nums',$this->use_nums);
		
		$criteria->compare('district_id',$this->district_id);
		
		$criteria->compare('type',$this->type);
		
		$criteria->compare('letter',$this->letter);
		$criteria->compare('source',$this->source);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_ip',$this->create_ip);

		return new CActiveDataProvider('Location', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		$this->pinyin = self::makePinyin($this->name);
		if($this->type == self::TYPE_OFFICE) $this->letter = CdcBetaTools::getFirstLetter($this->name);
		return true;
	}
	
	protected function beforeValidate()
	{
		parent::beforeValidate();
		DenyIp::CheckPostIpState($this);
		return true;
	}
		
	/**
	 * 本位置可送餐的商铺的列表的Url地址
	 * @return string
	 */
	public function getShopListUrl($cid = ShopCategory::CATEGORY_FOOD)
	{
	    return aurl('shop/list', array('atid' => $this->id, 'cid'=>$cid));
	}
	
	public function getAbsoluteUrl()
	{
	    return $this->getShopListUrl();
	}
	
	/**
	 * 本位置可送餐的餐馆列表的Url地址
	 * @return string
	 */
    public function getFoodShopListUrl()
	{
	    return url('shop/list', array('atid' => $this->id, 'cid' => ShopCategory::CATEGORY_FOOD));
	}
	
	/**
	 * 本位置可送餐的蛋糕店列表的Url地址
	 * @return string
	 */
    public function getCakeShopListUrl()
	{
	    return url('shop/list', array('atid' => $this->id, 'cid' => ShopCategory::CATEGORY_CAKE));
	}
	
	/**
	 * 本位置可送餐的花店列表的Url地址
	 * @return string
	 */
    public function getFlowerShopListUrl()
	{
	    return url('shop/list', array('atid' => $this->id, 'cid' => ShopCategory::CATEGORY_FLOWER));
	}
	
	/**
	 * 获取用户最后查看的位置
	 * @return integer|array 用户最后查看的位置的ID或位置坐标数组array(lat, lon)
	 */
	public static function getLastVisit()
	{
	    $data = self::getSearchHistory();
	    return $data[0];
	}
	
	/**
	 * 获取用户最后查看的位置
	 * @return array 位置坐标数组array(lat, lon)
	 */
	public static function getLastCoordinate() {
		$data = self::getLastVisit();
	    if(!is_array($data)) {
	    	$location = Location::model()->findByPk($data);
	    	$data = array($location->map_x, $location->map_y);
	    }
	    return $data;
	}
	
	/**
	 * 获取用户使用过的地址记录
	 * @return array 用户查看过地址的ID合集
	 */
	public static function getSearchHistory()
	{
	    $cookie = app()->request->cookies[param('cookieCurrentLocation')];
	    if (null === $cookie) return array();
	    $location = explode(self::DELIMITER_SEARCH_HISTORY, $cookie->value);
	    foreach ($location as $k => $v) {
	        $pointer = explode(self::DELIMITER_SEARCH_LAT_LON, $v);
	        if (count($pointer) == 2) $location[$k] = $pointer;
	    }
	    
	    return $location;
	}
	
	/**
	 * 增加用户查看记录，保存在cookie中
	 * @param integer|array $atid 位置ID或array(lat, lon)数组
	 */
	public static function addSearchHistory($atid)
	{
	    $data = self::getSearchHistory();
	    $key = array_search($atid, $data);
	    if ($key === 0) return true;
	    if ($key > 0) unset($data[$key]);
	    
	    array_unshift($data, $atid);
	    foreach ($data as $k => $v)
	        if (is_array($v)) $data[$k] = implode(self::DELIMITER_SEARCH_LAT_LON, $v);
	    
	    $str = implode(self::DELIMITER_SEARCH_HISTORY, $data);
	    $cookie = new CHttpCookie(param('cookieCurrentLocation'), $str);
	    $cookie->expire = $_SERVER['REQUEST_TIME'] + 24*60*60*365;
	    $cookie->path = param('cookiePath');
	    $cookie->domain = param('cookieDomain');
	    app()->request->cookies[param('cookieCurrentLocation')] = $cookie;
	}
	
	public static function getSearchHistoryData($count = 0)
	{
	    $history = Location::getSearchHistory();
	    if (empty($history)) return null;
	    
	    foreach ($history as $k => $v)
	        if (is_array($v)) unset($history[$k]);
	        
	    $criteria = new CDbCriteria();
	    
	    if ($count)
	        $history = array_slice($history, 0, $count);
	    
	    $criteria->addInCondition('id', $history);
	    $data = self::model()->findAll($criteria);
	    
	    foreach ($history as $k => $v) {
	        foreach ($data as $vv) {
	            if ($v == $vv->id) $history[$k] = $vv;
	        }
	    }
	    
	    return $history;
	}
	
	public static function getSearchHotData($count = 3)
	{
		$criteria = new CDbCriteria();
		$criteria->limit = $count;
		$criteria->order = 'use_nums desc';
		$data = Location::model()->findAll($criteria);
		return $data;
	}
	
	public static function getSearchHotNameLinkHtml($count = 3)
	{
		$k = 'getSearchHotNameLinkHtml' . $count;
		$html = app()->cache->get($k);
		if($html) {
			return $html;
		}
		$data = self::getSearchHotData($count);
		$html = '';
		foreach ($data as $location) {
			$html .= $location->nameLinkHtml . '&nbsp;&nbsp;';
		}
		app()->cache->set($k, $html, 3600);
		return $html;
	}
	
	public function getNameLinkHtml()
	{
	    return l($this->name, $this->shopListUrl, array('title'=>$this->name, 'target'=>'_top'));
	}
	
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	
	public function afterSearch()
	{
		return self::addUseNums($this->id);
	}
	
	/**
	 * 增加使用次数
	 */
	static function addUseNums($id, $nums=1)
	{
		$counters = array('use_nums' => $nums);
	    Location::model()->updateCounters($counters, 'id = ' . $id);
	}
	
    /**
     * 将汉字转换成拼音，只保留汉字及数字，其它字符过滤掉，拼音之间用-连接
     * @param string $str 待转换字符串
     * @return string 拼音字符串
     */
    public static function makePinyin($str, $separate = '')
    {
        if (empty($str)) return false;
        if(preg_match("/^\w+$/i", $str)) {
        	return $str;
        }
        static $pinyins = null;
        $pinyins = (null === $pinyins) ? require('PinyinArray.php') : $pinyins;
        
	    $len = mb_strlen($str);
	    for ($i=0; $i<$len; $i++) {
	        $word = mb_substr($str, $i, 1, app()->charset);
	        if (array_key_exists($word, $pinyins)) {
	            $pinyin[] = $pinyins[$word];
	        }
	    }
	    return join($pinyin, $separate);
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