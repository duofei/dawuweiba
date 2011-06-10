<?php

/**
 * This is the model class for table "{{ShopInside}}".
 *
 * The followings are the available columns in table '{{ShopInside}}':
 * @property integer $id
 * @property string $shop_name
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $district_id
 * @property string $logo
 * @property string $desc
 * @property string $address
 * @property double $map_x
 * @property double $map_y
 * @property string $map_region
 * @property string $telphone
 * @property string $mobile
 * @property string $qq
 * @property string $business_time
 * @property string $transport_time
 * @property string $transport_condition
 * @property string $pay_account
 * @property integer $is_group
 * @property float $group_success_price
 * @property integer $reserve_hour
 * @property integer $is_muslim
 * @property integer $is_approve
 * @property string $commercial_instrument
 * @property string $sanitary_license
 * @property string $owner_name
 * @property string $owner_card
 * @property integer $buy_type
 * @property integer $pay_type
 * @property string $announcement
 * @property integer $is_sanitary_approve
 * @property integer $is_commercial_approve
 * @property integer $create_time
 * @property integer $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $state

 */
class ShopInside extends CActiveRecord
{
	/**
	 * 每日菜单
	 */
	const DAILYMENU_NONSUPPORT = 0;
	const DAILYMENU_SUPPORT = 1;
	
	/**
	 * 营业状态
	 */
    const BUSINESS_STATE_CLOSE = 0;
    const BUSINESS_STATE_OPEN = 1;
    const BUSINESS_STATE_SUSPEND = 2;
    
    /**
     * 送餐范围坐标连接符
     */
    const SEPARATOR_REGION_POINT = '|';
    const SEPARATOR_REGION_LATLON = ',';

    
	/**
	 * 是否支持团购
	 */
	public static $groups = array(
		STATE_ENABLED => '支持团购',
		STATE_DISABLED => '不支持团购'
	);

	/**
	 * 是否清真
	 * @var array
	 */
	public static $muslim = array(
		STATE_ENABLED => '清真',
		STATE_DISABLED => '正常'
	);

	/**
	 * 是否通过卫生许可证审核
	 * @var array
	 */
	public static $sanitary = array(
	    STATE_ENABLED => '该店铺已经通过卫生许可证审核',
	    STATE_DISABLED => '该店铺未通过卫生许可证审核'
	);
	
		/**
	 * 是否通过营业执照审核
	 * @var array
	 */
	public static $commercial = array(
	    STATE_ENABLED => '该店铺已经通过营业执照审核',
	    STATE_DISABLED => '该店铺未通过营业执照审核'
	);
	
	
	/**
	 * 是否通过认证
	 */
	public static $approve = array(
		STATE_ENABLED => '已认证',
		STATE_DISABLED => '未认证'
	);

	/**
	 * 订餐方式
	 */
	const BUYTYPE_TELPHONE = 0;
    const BUYTYPE_NETWORK = 1;
    //const BUYTYPE_SMS = 2;

	public static $buytype = array(
		self::BUYTYPE_TELPHONE => '电话订餐',
		self::BUYTYPE_NETWORK => '网络订餐',
		//self::BUYTYPE_SMS => '短信订餐'
	);
	
	/**
	 * 支付方式
	 */
	const PAYTYPE_COD = 0;
    const PAYTYPE_ONLINE = 1;

	public static $paytype = array(
		self::PAYTYPE_COD => '货到付款',
		self::PAYTYPE_ONLINE => '在线支付'
	);

	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopInside the static model class
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
		return '{{ShopInside}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('user_id, shop_name, category_id, district_id, telphone, owner_name, owner_card, address', 'required', 'on'=>'insert'),
			//array('user_id, shop_name, category_id, district_id', 'required', 'on'=>'update'),
			array('user_id, category_id, district_id, create_time, update_time, is_group, is_muslim, is_sanitary_approve, is_commercial_approve, buy_type, pay_type, update_time, state', 'numerical', 'integerOnly'=>true),
			array('map_x, map_y', 'numerical'),
			array('shop_name', 'length', 'max'=>100),
			array('logo, address, business_time, transport_time, transport_condition, pay_account, commercial_instrument, sanitary_license, announcement', 'length', 'max'=>255),
			array('telphone, mobile', 'length', 'max'=>60),
			array('qq', 'length', 'max'=>20, 'min'=>5),
			array('owner_name', 'length', 'max'=>50),
			array('owner_card', 'length', 'min'=>15, 'max'=>18),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			array('desc, map_region', 'safe'),
			array('user_id', 'default', 'value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			array('reserve_hour', 'numerical', 'max'=>9.9, 'min'=>0),
			array('group_success_price', 'numerical', 'max'=>999999.99, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_name, user_id, category_id, district_id, logo, desc, address, map_x, map_y, map_region, telphone, mobile, qq, business_time, transport_time, transport_condition, pay_account, is_group, reserve_hour, is_muslim, is_sanitary_approve,is_commercial_approve, commercial_instrument, sanitary_license, owner_name, owner_card, create_time, update_time, create_ip, update_ip, buy_type, pay_type, announcement, state, group_success_price', 'safe', 'on'=>'search'),
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'ShopCategory', 'category_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'tags' => array(self::MANY_MANY, 'Tag', '{{ShopTag}}(shop_id, tag_id)'),
		    'district' => array(self::BELONGS_TO, 'District', 'district_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'shop_name' => '店铺名称',
			'user_id' => '用户',
			'category_id' => '商铺分类',
			'district_id' => '行政区域',
			'logo' => '商铺Logo',
			'desc' => '商铺简介',
			'address' => '详细地址',
			'map_x' => '地图坐标x',
			'map_y' => '地图坐标y',
			'map_region' => '地图标注范围',
			'telphone' => '联系电话&订餐电话',
			'mobile' => '店主手机',
			'qq' => 'Qq',
			'business_time' => '营业时间',
			'transport_time' => '送餐时间',
			'transport_condition' => '起送条件',
			'pay_account' => '支付账号',
			'is_group' => '是否支持同楼订餐',
			'group_success_price' => '同楼订餐成功金额',
			'reserve_hour' => '预订提前时间',
			'is_muslim' => '是否清真',
			'is_sanitary_approve' => '是否通过卫生许可证认证',
			'is_commercial_approve' => '是否通过营业许可证认证',
			'commercial_instrument' => '营业执照',
			'sanitary_license' => '卫生许可证',
			'owner_name' => '店主姓名',
			'owner_card' => '店主身份证号',
			'create_time' => '创建时间',
			'create_ip' => 'Ip',
			'buy_type' => '订餐方式',
			'pay_type' => '支付方式',
			'announcement' => '商铺公告',
			'update_time' => '更新时间',
			'update_ip' => 'Ip',
			'state' => '审核',
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

		$criteria->compare('shop_name',$this->shop_name,true);

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('category_id',$this->category_id,true);

		$criteria->compare('district_id',$this->district_id,true);

		$criteria->compare('logo',$this->logo,true);

		$criteria->compare('desc',$this->desc,true);

		$criteria->compare('address',$this->address,true);

		$criteria->compare('map_x',$this->map_x);

		$criteria->compare('map_y',$this->map_y);

		$criteria->compare('map_region',$this->map_region,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('qq',$this->qq,true);

		$criteria->compare('business_time',$this->business_time,true);
		
		$criteria->compare('transport_time',$this->transport_time,true);

		$criteria->compare('transport_condition',$this->transport_condition,true);

		$criteria->compare('pay_account',$this->pay_account,true);

		$criteria->compare('is_group',$this->is_group);
		
		$criteria->compare('group_success_price',$this->group_success_price);

		$criteria->compare('reserve_hour',$this->reserve_hour);

		$criteria->compare('is_muslim',$this->is_muslim);

		$criteria->compare('is_sanitary_approve',$this->is_sanitary_approve);
		
		$criteria->compare('is_commercial_approve',$this->is_commercial_approve);
		
		$criteria->compare('commercial_instrument',$this->commercial_instrument,true);

		$criteria->compare('sanitary_license',$this->sanitary_license,true);

		$criteria->compare('owner_name',$this->owner_name,true);

		$criteria->compare('owner_card',$this->owner_card,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('buy_type',$this->buy_type);

		$criteria->compare('pay_type',$this->pay_type);

		$criteria->compare('announcement',$this->announcement,true);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);
		
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('ShopInside', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('shop/show', array('shopid' => $this->id));
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('shop/show', array('shopid' => $this->id));
	}
	
	public function getShortName($len = 0)
	{
	    if (0 == $len) return $this->shop_name;
	    return mb_strimwidth($this->shop_name, 0, $len, '..');
	}
	
	
	/**
	 * 获取订餐方式
	 */
	public function getBuyTypeText()
	{
		return self::$buytype[$this->buy_type];
	}
	
	/**
	 * 获取支付方式
	 */
	public function getPayTypeText()
	{
		return self::$paytype[$this->pay_type];
	}
	
	public function getMapPosition()
	{
	    if ($this->map_x && $this->map_y)
	        return $this->map_x . ',' . $this->map_y;
	    else
	        return null;
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
	
	public function getMapRegion()
	{
	    if ($this->map_region) {
	        $data = explode(self::SEPARATOR_REGION_POINT, $this->map_region);
	        foreach ($data as $v) {
	            $points[] = explode(self::SEPARATOR_REGION_LATLON, $v);
	        }
	        return $points;
	    }
	    return null;
	}
	
	/**
	 * 获取商铺公告
	 * @param string
	 */
	public function getAnnouncementText()
	{
	    if ($this->announcement) return h($this->announcement);
	    return '该商铺暂无公告';
	}

	/**
	 * 获取店铺分类
	 */
	public function getCategoryText()
	{
		return ShopCategory::$categorys[$this->category_id];
	}

}