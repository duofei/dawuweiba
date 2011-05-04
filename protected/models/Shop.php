<?php

/**
 * This is the model class for table "{{Shop}}".
 *
 * The followings are the available columns in table '{{Shop}}':
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
 * @property string $map_region2
 * @property string $map_region3
 * @property float $transport_amount
 * @property float $transport_amount2
 * @property float $transport_amount3
 * @property float $dispatching_amount
 * @property float $dispatching_amount2
 * @property float $dispatching_amount3
 * @property string $telphone
 * @property string $mobile
 * @property integer $service_mark_nums
 * @property integer $service_mark
 * @property float $service_avg
 * @property string $qq
 * @property integer $business_state
 * @property string $business_time
 * @property string $transport_time
 * @property string $transport_condition
 * @property string $transport_condition2
 * @property string $transport_condition3
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
 * @property integer $create_time
 * @property integer $create_ip
 * @property integer $order_nums
 * @property integer $undressed_order_nums
 * @property integer $goods_nums
 * @property integer $visit_nums
 * @property integer $favorite_nums
 * @property integer $comment_nums
 * @property integer $taste_mark_nums
 * @property integer $taste_mark
 * @property float $taste_avg
 * @property integer $buy_type
 * @property integer $pay_type
 * @property string $announcement
 * @property integer $coupon_nums
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $state
 * @property integer $is_sanitary_approve
 * @property integer $is_commercial_approve
 * @property integer $yewu_id
 * @property integer $printer_no
 * @property string $remark
 * @property string $nick
 */
class Shop extends CActiveRecord
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

    public static $business_states = array(
        self::BUSINESS_STATE_OPEN => '营业中',
        self::BUSINESS_STATE_SUSPEND => '休息中',
        self::BUSINESS_STATE_CLOSE => '关闭',
    );
    
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
    const BUYTYPE_PRINTER = 2;

	public static $buytype = array(
		self::BUYTYPE_TELPHONE => '电话订餐',
		self::BUYTYPE_NETWORK => '网络订餐',
		self::BUYTYPE_PRINTER => '网络打印订餐'
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

	const STATE_UNSETTLED = 0;
	const STATE_VERIFY = 1;
	const STATE_PSEUDO = 2;
	public static $states = array(
		self::STATE_UNSETTLED => '未处理',
		self::STATE_VERIFY => '通过核实',
		self::STATE_PSEUDO => '驳回',
	);
	
	/**
	 * 获取商铺状态
	 */
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Shop the static model class
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
		return '{{Shop}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, shop_name, category_id, district_id, telphone, owner_name, owner_card, address', 'required', 'on'=>'insert'),
			array('user_id, shop_name, category_id, district_id', 'required', 'on'=>'update'),
			array('user_id, shop_name', 'required', 'on'=>'adminPost'),
			array('user_id, category_id, district_id, create_time, update_time, order_nums, visit_nums, favorite_nums, comment_nums, taste_mark_nums, taste_mark, service_mark_nums, service_mark, business_state, is_group, is_muslim, is_sanitary_approve, is_commercial_approve, is_dailymenu, undressed_order_nums, goods_nums, buy_type, pay_type, coupon_nums, update_time, state, yewu_id, printer_no', 'numerical', 'integerOnly'=>true),
			array('map_x, map_y', 'numerical'),
			array('nick', 'unique'),
			array('shop_name', 'length', 'max'=>100),
			array('logo, address, business_time, transport_time, transport_condition, transport_condition2, transport_condition3, pay_account, commercial_instrument, sanitary_license, announcement, remark, nick', 'length', 'max'=>255),
			array('telphone, mobile', 'length', 'max'=>60),
			array('qq', 'length', 'max'=>20, 'min'=>5),
			array('owner_name', 'length', 'max'=>50),
			array('owner_card', 'length', 'min'=>15, 'max'=>18),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			array('desc, map_region, map_region2, map_region3', 'safe'),
			//array('user_id', 'default', 'value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			array('taste_avg, service_avg, reserve_hour', 'numerical', 'max'=>9.9, 'min'=>0),
			array('group_success_price, transport_amount, transport_amount2, transport_amount3, dispatching_amount, dispatching_amount2, dispatching_amount3', 'numerical', 'max'=>99999.99, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_name, user_id, category_id, district_id, logo, desc, address, map_x, map_y, map_region, map_region2, map_region3, transport_amount, transport_amount2, transport_amount3, dispatching_amount, dispatching_amount2, dispatching_amount3, telphone, mobile, service_mark_nums, service_mark, qq, business_state, business_time, transport_time, transport_condition, transport_condition2, transport_condition3, pay_account, is_group, reserve_hour, is_muslim, is_sanitary_approve,is_commercial_approve, is_dailymenu, commercial_instrument, sanitary_license, owner_name, owner_card, create_time, update_time, create_ip, update_ip, order_nums, undressed_order_nums, goods_nums, visit_nums, favorite_nums, comment_nums, taste_mark_nums, taste_mark, buy_type, pay_type, announcement, coupon_nums, state, group_success_price, yewu_id, printer_no, remark, nick', 'safe', 'on'=>'search'),
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
			'deliveryMen' => array(self::HAS_MANY, 'DeliveryMan', 'shop_id'),
			'goods' => array(self::HAS_MANY, 'Goods', 'shop_id',
		        'order' => 'goods.pic desc, goods.id desc',
		    ),
			'goodsCategories' => array(self::HAS_MANY, 'GoodsCategory', 'shop_id',
		    	'order' => 'goodsCategories.orderid desc',
		    ),
			'orders' => array(self::HAS_MANY, 'Order', 'shop_id'),
			'promotions' => array(self::HAS_MANY, 'Promotion', 'shop_id'),
		    'recommendPromotions' => array(self::HAS_MANY, 'Promotion', 'shop_id',
		        'condition' => 'recommend = ' . STATE_ENABLED,
		    ),
			'category' => array(self::BELONGS_TO, 'ShopCategory', 'category_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'shopComments' => array(self::HAS_MANY, 'ShopComment', 'shop_id'),
		    'goodsRateLog' => array(self::HAS_MANY, 'GoodsRateLog', 'shop_id'),
		    'shopCommonReply' => array(self::HAS_MANY, 'shopCommonReply', 'shop_id'),
			'shopCreditLogs' => array(self::HAS_MANY, 'ShopCreditLog', 'shop_id'),
			'tags' => array(self::MANY_MANY, 'Tag', '{{ShopTag}}(shop_id, tag_id)'),
			'userShopFavorites' => array(self::HAS_MANY, 'UserShopFavorite', 'shop_id'),
		    'district' => array(self::BELONGS_TO, 'District', 'district_id'),
		    'yewuyuan' => array(self::BELONGS_TO, 'User', 'yewu_id'),
		    'printer' => array(self::BELONGS_TO, 'Printer', 'printer_no'),
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
			'map_region2' => '地图标注范围',
			'map_region3' => '地图标注范围',
			'transport_amount' => '起送价',
			'transport_amount2' => '起送价',
			'transport_amount3' => '起送价',
			'dispatching_amount' => '送餐费',
			'dispatching_amount2' => '送餐费',
			'dispatching_amount3' => '送餐费',
			'telphone' => '联系电话&订餐电话',
			'mobile' => '店主手机',
			'service_mark_nums' => '评分次数',
			'service_mark' => '总评分分数',
			'service_avg' => '服务平均分',
			'qq' => 'Qq',
			'business_state' => '营业状态',
			'business_time' => '营业时间',
			'transport_time' => '送餐时间',
			'transport_condition' => '起送条件',
			'transport_condition2' => '起送条件',
			'transport_condition3' => '起送条件',
			'pay_account' => '支付账号',
			'is_group' => '是否支持同楼订餐',
			'group_success_price' => '同楼订餐成功金额',
			'reserve_hour' => '预订提前时间',
			'is_muslim' => '是否清真',
			'is_sanitary_approve' => '是否通过卫生许可证认证',
			'is_commercial_approve' => '是否通过营业许可证认证',
			'is_dailymenu' => '是否支持每日菜单',
			'commercial_instrument' => '营业执照',
			'sanitary_license' => '卫生许可证',
			'owner_name' => '店主姓名',
			'owner_card' => '店主身份证号',
			'create_time' => '创建时间',
			'create_ip' => 'Ip',
			'order_nums' => '订单总数',
			'undressed_order_nums' => '未加工订单数',
			'goods_nums' => '商品数量',
			'visit_nums' => '浏览次数',
			'favorite_nums' => '收藏次数',
			'comment_nums' => '评论次数',
			'taste_mark_nums' => '口味总评分次数',
			'taste_mark' => '口味评分',
			'taste_avg' => '口味评平均分',
			'buy_type' => '订餐方式',
			'pay_type' => '支付方式',
			'announcement' => '商铺公告',
			'coupon_nums' => '优惠券 数',
			'update_time' => '更新时间',
			'update_ip' => 'Ip',
			'state' => '审核',
			'yewu_id' => '业务员',
			'printer_no' => '打印机特征码',
			'remark' => '商铺管理备注',
			'nick' => '二级域名昵称',
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
		$criteria->compare('map_region2',$this->map_region2,true);
		$criteria->compare('map_region3',$this->map_region3,true);
		
		$criteria->compare('transport_amount',$this->transport_amount,true);
		$criteria->compare('transport_amount2',$this->transport_amount2,true);
		$criteria->compare('transport_amount3',$this->transport_amount3,true);
		
		$criteria->compare('dispatching_amount',$this->dispatching_amount,true);
		$criteria->compare('dispatching_amount2',$this->dispatching_amount2,true);
		$criteria->compare('dispatching_amount3',$this->dispatching_amount3,true);

		$criteria->compare('telphone',$this->telphone,true);

		$criteria->compare('mobile',$this->mobile,true);

		$criteria->compare('service_mark_nums',$this->service_mark_nums);

		$criteria->compare('service_mark',$this->service_mark);
		
		$criteria->compare('service_avg',$this->service_avg);

		$criteria->compare('qq',$this->qq,true);

		$criteria->compare('business_state',$this->business_state);

		$criteria->compare('business_time',$this->business_time,true);
		
		$criteria->compare('transport_time',$this->transport_time,true);

		$criteria->compare('transport_condition',$this->transport_condition,true);
		$criteria->compare('transport_condition2',$this->transport_condition,true);
		$criteria->compare('transport_condition3',$this->transport_condition,true);

		$criteria->compare('pay_account',$this->pay_account,true);

		$criteria->compare('is_group',$this->is_group);
		
		$criteria->compare('group_success_price',$this->group_success_price);

		$criteria->compare('reserve_hour',$this->reserve_hour);

		$criteria->compare('is_muslim',$this->is_muslim);

		$criteria->compare('is_sanitary_approve',$this->is_sanitary_approve);
		
		$criteria->compare('is_commercial_approve',$this->is_commercial_approve);
		
		$criteria->compare('is_dailymenu',$this->is_dailymenu);

		$criteria->compare('commercial_instrument',$this->commercial_instrument,true);

		$criteria->compare('sanitary_license',$this->sanitary_license,true);

		$criteria->compare('owner_name',$this->owner_name,true);

		$criteria->compare('owner_card',$this->owner_card,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('order_nums',$this->order_nums,true);

		$criteria->compare('undressed_order_nums',$this->undressed_order_nums);

		$criteria->compare('goods_nums',$this->goods_nums);

		$criteria->compare('visit_nums',$this->visit_nums,true);

		$criteria->compare('favorite_nums',$this->favorite_nums,true);

		$criteria->compare('comment_nums',$this->comment_nums,true);

		$criteria->compare('taste_mark_nums',$this->taste_mark_nums,true);

		$criteria->compare('taste_mark',$this->taste_mark,true);
		
		$criteria->compare('taste_avg',$this->taste_avg,true);

		$criteria->compare('buy_type',$this->buy_type);

		$criteria->compare('pay_type',$this->pay_type);

		$criteria->compare('announcement',$this->announcement,true);

		$criteria->compare('coupon_nums',$this->coupon_nums);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);
		
		$criteria->compare('state',$this->state,true);
		$criteria->compare('yewu_id',$this->yewu_id,true);
		$criteria->compare('printer_no',$this->printer_no,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('nick',$this->nick,true);

		return new CActiveDataProvider('Shop', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->isNewRecord) {
			//array('user_id', 'default', 'value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// 把rules里的更改到了这里
			if(!$this->user_id) {
				$this->user_id = user()->id;
			}
		}
		return true;
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
	 * 获取营业状态
	 */
	public function getBusinessStateText()
	{
		return self::$business_states[$this->business_state];
	}
	
	/**
	 * 是否支持团购
	 */
	public function getGroupText()
	{
		return self::$groups[$this->is_group];
	}
	
	/**
	 * 是否清真
	 */
	public function getMuslimText()
	{
		return self::$muslim[$this->is_muslim];
	}
	
	/**
	 * 是否通过认证
	 */
	public function getSanitaryApproveText()
	{
		return self::$approve[$this->is_sanitary_approve];
	}
	
	public function getCommercialApproveText()
	{
		return self::$approve[$this->is_commercial_approve];
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
	 * 获取商铺服务评分平均值
	 */
	public function getServiceAverageMark()
	{
		if ($this->service_mark_nums == 0) return 3.5;
		
		$average = $this->service_mark / $this->service_mark_nums;
		if ($average <= 0.5) return 0;
		
		return round(($average - 0.5) * 10, 1);
	}
	
	/**
	 * 获取商铺服务星星的宽度
	 */
	public function getServiceStarWidth()
	{
	    return (int)($this->serviceAverageMark * 70 / 5);
	}
	
	/**
	 * 获取商铺口味评分平均值
	 */
	public function getTasteAverageMark()
	{
	    if (0 == $this->taste_mark_nums) return 3.5;
	    return round($this->taste_mark / $this->taste_mark_nums, 1);
	}
	
	/**
	 * 获取商铺口味星星的宽度
	 */
	public function getTasteStarWidth()
	{
	    return (int)($this->tasteAverageMark * 70 / 5);
	}
	
	
	/**
	 * 获取商铺名称链接
	 */
	public function getNameLinkHtml($len = 0, $target='_self')
	{
		return l($this->getShortName($len), $this->absoluteUrl, array('class'=>'shop-name', 'title'=>$this->shop_name, 'target'=>$target));
	}
	
	/**
	 * 商铺缩略图url，如果不存在则返回默认图片url
	 */
	public function getLogoUrl()
	{
	    if (empty($this->logo)) return resBu(param('defaultShop'));
	    return sbu($this->logo);
	}
	
	/**
	 * 获取商铺LOGO图片
	 */
	public function getLogoHtml()
	{
		return CHtml::image($this->logoUrl, $this->shop_name, array('class'=>'shop-logo', 'alt'=>$this->shop_name, 'title'=>$this->shop_name));
	}
	
	/**
	 * 获取商铺LOGO链接
	 */
	public function getLogoLinkHtml()
	{
		return l($this->logoHtml, $this->absoluteUrl, array(
			'alt'=>$this->shop_name,
			'title'=>$this->shop_name . ',' . $this->businessStateText,
			'class'=>'shop-logo-link
		'));
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
	
	/**
	 * 获取当前商铺能够配送的最小范围
	 */
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
	
	public function getMapRegion2()
	{
	    if ($this->map_region2) {
	        $data = explode(self::SEPARATOR_REGION_POINT, $this->map_region2);
	        foreach ($data as $v) {
	            $points[] = explode(self::SEPARATOR_REGION_LATLON, $v);
	        }
	        return $points;
	    }
	    return null;
	}
	
	public function getMapRegion3()
	{
	    if ($this->map_region3) {
	        $data = explode(self::SEPARATOR_REGION_POINT, $this->map_region3);
	        foreach ($data as $v) {
	            $points[] = explode(self::SEPARATOR_REGION_LATLON, $v);
	        }
	        return $points;
	    }
	    return null;
	}
	
	/**
	 * 获取当前商铺能够配送的最大范围
	 */
	public function getMaxMapRegion()
	{
		$mapRegion = null;
		if($this->map_region3) {
			$mapRegion = $this->map_region3;
		} elseif ($this->map_region2) {
			$mapRegion = $this->map_region2;
		} else {
			$mapRegion = $this->map_region;
		}
	    if ($mapRegion) {
	        $data = explode(self::SEPARATOR_REGION_POINT, $mapRegion);
	        foreach ($data as $v) {
	            $points[] = explode(self::SEPARATOR_REGION_LATLON, $v);
	        }
	        return $points;
	    }
	    return null;
	}
	
	/**
	 * 获取当前配送范围内的配送条件
	 */
	public function getMatchTransportCondition()
	{
		$coordinate = Location::getLastCoordinate();
    	$lat = $coordinate[0];
    	$lon = $coordinate[1];
    	$html = '';
    	if($this->mapRegion && CdcBetaTools::pointInPolygon($this->mapRegion, $lat, $lon)) {
    		if($this->transport_amount > 0) {
    			$html = '最低起送价' . $this->transport_amount . '元';
    			if($this->dispatching_amount > 0) {
    				$html .= '，送餐费' . $this->dispatching_amount . '元';
    			}
    			return $html;
    		}
    		if($this->transport_condition) {
    			return $this->transport_condition;
    		}
    	}
		if($this->mapRegion2 && CdcBetaTools::pointInPolygon($this->mapRegion2, $lat, $lon)) {
    		if($this->transport_amount2 > 0) {
    			$html = '最低起送价' . $this->transport_amount2 . '元';
    			if($this->dispatching_amount2 > 0) {
    				$html .= '，送餐费' . $this->dispatching_amount2 . '元';
    			}
	    		return $html;
    		}
			if($this->transport_condition2) {
    			return $this->transport_condition2;
    		}
    	}
		if($this->mapRegion3 && CdcBetaTools::pointInPolygon($this->mapRegion3, $lat, $lon)) {
    		if($this->transport_amount3 > 0) {
    			$html = '最低起送价' . $this->transport_amount3 . '元';
    			if($this->dispatching_amount3 > 0) {
    				$html .= '，送餐费' . $this->dispatching_amount3 . '元';
    			}
	    		return $html;
    		}
			if($this->transport_condition3) {
    			return $this->transport_condition3;
    		}
    	}
    	return null;
	}
	
	/**
	 * 获取当前配送范围内的最低起送价
	 */
	public function getMatchTransportAmount()
	{
		$coordinate = Location::getLastCoordinate();
    	$lat = $coordinate[0];
    	$lon = $coordinate[1];
    	if($this->mapRegion && CdcBetaTools::pointInPolygon($this->mapRegion, $lat, $lon)) {
    		return $this->transport_amount + 0;
    	}
		if($this->mapRegion2 && CdcBetaTools::pointInPolygon($this->mapRegion2, $lat, $lon)) {
    		return $this->transport_amount2 + 0;
    	}
		if($this->mapRegion3 && CdcBetaTools::pointInPolygon($this->mapRegion3, $lat, $lon)) {
    		return $this->transport_amount3 + 0;
    	}
    	return null;
	}
	
	/**
	 * 获取当前配送范围内的配送费
	 */
	public function getMatchDispatchingAmount()
	{
		$coordinate = Location::getLastCoordinate();
    	$lat = $coordinate[0];
    	$lon = $coordinate[1];
    	if($this->mapRegion && CdcBetaTools::pointInPolygon($this->mapRegion, $lat, $lon)) {
    		return $this->dispatching_amount + 0;
    	}
		if($this->mapRegion2 && CdcBetaTools::pointInPolygon($this->mapRegion2, $lat, $lon)) {
    		return $this->dispatching_amount2 + 0;
    	}
		if($this->mapRegion3 && CdcBetaTools::pointInPolygon($this->mapRegion3, $lat, $lon)) {
    		return $this->dispatching_amount3 + 0;
    	}
    	return null;
	}
	
	/**
	 * 获取一个地点可以送餐的商家列表
	 * @param integer|array $at 如果是整数，即是LocationId
	 * 		如果是数组，即是坐标数组array(lat, lon)
	 * @param integer $cid 分类ID，美食、蛋糕、鲜花，默认为0
	 * @param integer $cityId 如果$at为坐标的话，最好指定$cityId
	 */
	public static function getLocationShopList($at, $cid = 0, $criteria = null, $cityId = null)
	{
	    if (is_int($at)) {
	        $location = Location::model()->findByPk($at);
	        $at = array($location->map_x, $location->map_y);
	        $cityId = $location->city_id;
	    } elseif ($at instanceof Location) {
	        $cityId = $at->city_id;
	        $at = array($at->map_x, $at->map_y);
	    } elseif (is_array($at)) ;
	    else
	        throw new CException('$at参数错误', 0);
	    
	    $criteria = $criteria ? $criteria : new CDbCriteria();
	    $criteria->addColumnCondition(array('state'=>STATE_ENABLED));
	    if ($cid) {
	        $criteria->addColumnCondition(array('category_id' => $cid));
	    }
	    if (null !== $cityId) {
	        $criteria->addCondition("district.city_id = $cityId");
	        $criteria->with[] = 'district';
	    }
	    $criteria->addCondition('map_region is not null');
	    $sort = new CSort('Shop');
	    $sort->defaultOrder = 't.business_state asc';
	    $sort->applyOrder($criteria);
	    $shop = self::model()->findAll($criteria);
	    $shops1 = array();
	    $shops2 = array();
	    $shops3 = array();
	    $shops4 = array();
	    foreach ($shop as $v) {
	        if (null === $v->maxMapRegion) continue;
	        if (CdcBetaTools::pointInPolygon($v->maxMapRegion, $at[0], $at[1])) {
	        	if($v->buy_type == Shop::BUYTYPE_PRINTER) {
		        	if($v->getDistance($at) > 1000) {
		            	$shops2[] = $v;
		        	} else {
		        		$shops1[] = $v;
		        	}
	        	} else {
	        		if($v->getDistance($at) > 1000) {
		            	$shops4[] = $v;
		        	} else {
		        		$shops3[] = $v;
		        	}
	        	}
	        }
	    }
	    $shops = array_merge($shops1, $shops2, $shops3, $shops4);
	    $data = array('shops'=>$shops, 'sort'=>$sort);
	    return $data;
	}
	
	/**
	 * 获取商铺列表页过滤条件
	 * @param string
	 */
	public function getFilterTags()
	{
	    $tags[] = $this->businessStateText;
	    $tags[] = $this->muslimText;
	    foreach ((array)$this->tags as $v) $tags[] = $v->name;
	    return implode(',', (array)$tags);
	}
	
	/**
	 * 获取一个商铺的标签列表
	 * @param array
	 */
	public function getTagsArray()
	{
	    if (null === $this->tags) return null;
	    foreach ((array)$this->tags as $v) $data[$v->id] = $v->name;
	    return $data;
	}
	
	/**
	 * 获取一个商铺的标签列表
	 * @param string
	 */
    public function getTagsText()
	{
	    if (null === $this->tagsArray) return null;
	    return implode(', ', $this->tagsArray);
	}
	
	/**
	 * 生成是否清真的小图标
	 * @param string HTML COde
	 */
	public function getIsMuslimIcon()
	{
	    if (STATE_ENABLED != $this->is_muslim) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        self::$muslim[$this->is_muslim],
	        array(
	            'title' => self::$muslim[$this->is_muslim],
	            'class' => 'bg-icon absolute is-muslim'
	        )
	    );
	}
	
	/**
	 * 是否是新加入的商铺的小图标
	 * @param string HTML Code
	 */
	public function getIsNewIcon()
	{
	    $time = (int)$_SERVER['REQUEST_TIME'] - (int)$this->create_time;
	    if ($time > 7*24*60*60) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        '最新加入',
	        array(
	            'title' => '最新加入',
	            'class' => 'bg-icon is-new'
	        )
	    );
	}
	
	/**
	 * 是否是签约商铺的小图标
	 * @param string HTML Code
	 */
	public function getIsSignerIcon()
	{
	   	if($this->buy_type == Shop::BUYTYPE_PRINTER) {
		 	return CHtml::image(resBu('images/pixel.gif'),
		        '签约商铺',
		        array(
		            'title' => '签约商铺',
		            'class' => 'bg-icon is-signer'
		        )
		    );
	   	}
	   	return null;
	}
	
	/**
	 * 是否通过卫生许可证审核的小图标
	 * @param string HTML Code
	 */
	public function getIsSanitaryIcon()
	{
	    if (STATE_ENABLED != $this->is_sanitary_approve) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        self::$sanitary[$this->is_sanitary_approve],
	        array(
	            'title' => self::$sanitary[$this->is_sanitary_approve],
	            'class' => 'bg-icon is-sanitary'
	        )
	    );
	}
	
	/**
	 * 是否通过营业许可证审核的小图标
	 * @param string HTML Code
	 */
	public function getIsCommercialIcon()
	{
	    if (STATE_ENABLED != $this->is_commercial_approve) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        self::$commercial[$this->is_commercial_approve],
	        array(
	            'title' => self::$commercial[$this->is_commercial_approve],
	            'class' => 'bg-icon is-commercial'
	        )
	    );
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
	
	/**
	 * 获取商家分类数量
	 */
	public function getShopCount($cityid)
	{
		$district = array_keys(District::getDistrictArray($cityid));
		$criteria = new CDbCriteria();
		$criteria->addInCondition('t.district_id', $district);
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
		$array = array();
		// 简餐
		$array['jc'] = Shop::model()->with(array('tags'=>array('condition'=>"tags.name!='家宴'")))->count($criteria);
		// 家宴
		$array['jy'] = Shop::model()->with(array('tags'=>array('condition'=>"tags.name='家宴'")))->count($criteria);
		// 蛋糕
		$cakeCriteria = new CDbCriteria();
		$cakeCriteria->mergeWith($criteria);
		$cakeCriteria->addCondition("t.category_id = " . ShopCategory::CATEGORY_CAKE);
		$array['cake'] = Shop::model()->count($cakeCriteria);
		
		return $array;
	}
	
	/**
	 * 获取蛋糕店数量
	 * @param integer $cityId
	 * @return integer 蛋糕店数量
	 */
	public function getCakeShopCount($cityId=0)
	{
		$cityId = intval($cityId);
		$criteria = new CDbCriteria();
	    if($cityId) {
			$district = array_keys(District::getDistrictArray($cityId));
			$criteria->addInCondition('t.district_id', $district);
	    }
		$criteria->addColumnCondition(array('t.state'=>STATE_ENABLED));
		$criteria->addCondition("t.category_id = " . ShopCategory::CATEGORY_CAKE);
		return self::model()->count($criteria);
	}
	
	/**
	 * 获取餐厅数量
	 * @param integer $cityId
	 * @return integer 餐厅数量
	 */
	public function getFoodShopCount($cityId=0)
	{
		$cityId = intval($cityId);
		$criteria = new CDbCriteria();
	    if($cityId) {
			$district = array_keys(District::getDistrictArray($cityId));
			$criteria->addInCondition('t.district_id', $district);
	    }
		$criteria->addColumnCondition(array('t.state'=>STATE_ENABLED));
		$criteria->addCondition("t.category_id = " . ShopCategory::CATEGORY_FOOD);
		return self::model()->count($criteria);
	}
	
	/**
	 * 获取该商铺支持的楼宇
	 */
	public static function getSupportingBuilding($shopId, $cityId)
	{
		$shop_id = intval($shopId);
		$shop = self::model()->findByPk($shop_id);
		if(!$shop) return false;
		$points = $shop->MapRegion;
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$cityId, 'type'=>Location::TYPE_OFFICE, 'state'=>STATE_ENABLED));
		$buildings = Location::model()->findAll($criteria);
		if(!$buildings) return false;
		$array = array();
		foreach ($buildings as $b) {
			if(CdcBetaTools::pointInPolygon($points, $b->map_x, $b->map_y)) {
				$array[] = $b;
			}
		}
		if($array) {
			return $array;
		} else {
			return false;
		}
	}
	
	public static function getShopBuildingGrouponAmount($shopId, $buildingId, $date = null)
	{
	    $buildingId = (int)$buildingId;
	    if (empty($buildingId))
	        throw new CException('参数不正确', 0);
	        
        if (null === $date) {
            $times = explode(':', param('grouponEndTime'));
            $date = ((int)idate('H') < (int)$times[0]) ? date('Y-m-d') : date('Y-m-d', strtotime('+1 day'));
        }
        
        $criteria = new CDbCriteria();
        $criteria->select = "sum(t.goods_price) amount";
        $criteria->addCondition('building_id = ' . $buildingId);
        $endTime = strtotime($date . ' ' . param('grouponEndTime'));
        $startTime = $endTime - 24*60*60;
        $criteria->addCondition("create_time > $startTime and create_time < $endTime");
        $criteria->addCondition('shop_id = ' . $shopId);
        $sql = "select sum(amount) allamount from wm_Order where " . $criteria->condition;
	    $command = app()->db->createCommand($sql);
	    $result = $command->queryScalar();
	    return (float)$result;
	}
	
	public function getBuildingGrouponAmount($buildingId, $date = null)
	{
	    return self::getShopBuildingGrouponAmount($this->id, $buildingId, $date);
	}
	
	/**
	 * 返回当前商铺是否营业中
	 */
	public function getIsOpening()
	{
	    if ($this->business_state != Shop::BUSINESS_STATE_OPEN) return false;
	    
	    // 如果打印机状态不正常也显示不在线
	    if ($this->printer && ($this->printer->state != Printer::STATE_ONLINE)) return false;
	    
	    $date = explode('-', $this->business_time);
	    // 如果营业时间格式不对，返回营业中
	    if (2 != count($date)) return true;
	    
	    $begin = $date[0];
	    $end = $date[1];
	    $time = $_SERVER['REQUEST_TIME'];
	    return (strtotime($begin) < $time && $time < strtotime($end));
	}
	
    public function getMenuBtnHtml($label = '在线菜单')
    {
        $color = $this->isOpening ? 'cred' : 'cgray';
        $btnColor = $this->isOpening ? 'button-yellow' : 'button-gray';
        echo l(
        	'<span class="' . $color . '">' . $label . '</span>',
            $this->absoluteUrl,
            array('title'=>$this->shop_name . ',' . $this->isOpeningText, 'class'=>$btnColor)
        );
    }
    public function getIsOpeningText()
    {
    	if($this->IsOpening) {
    		return Shop::$business_states[Shop::BUSINESS_STATE_OPEN];
    	} else {
    		return Shop::$business_states[Shop::BUSINESS_STATE_SUSPEND];
    	}
    }
    public function getIsOpeningHtml()
    {
    	if($this->IsOpening) {
    		return '<span class="bg-icon business-state1 pa-l20px ma-l5px">' . $this->isOpeningText . '</span>';
    	} else {
    		return '<span class="bg-icon business-state2 pa-l20px ma-l5px">' . $this->isOpeningText . '</span>';
    	}
    }
    
    /**
     * 获取商铺距用户地点的距离
     */
	public function getDistance($at = null)
	{
		if($at === null)
			$at = Location::getLastCoordinate();
		
		if($at && $this->map_x && $this->map_y) {
			$distance = CdcBetaTools::distanceBetweenPoints(array('lat'=>$at[0], 'lon'=>$at[1]), array('lat'=>$this->map_x,'lon'=>$this->map_y));
			return $distance;
		} else {
			return null;
		}
	}
	public function getDistanceText()
	{
		if($this->distance) {
			if($this->distance > 1000) {
				return round($this->distance/1000, 1) . '公里';
			} elseif($this->distance < 200) {
				return '少于200米';
			} else {
				return $this->distance . '米';
			}
		} else {
			return null;
		}
	}
}