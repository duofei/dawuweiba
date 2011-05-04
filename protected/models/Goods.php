<?php

/**
 * This is the model class for table "{{Goods}}".
 *
 * The followings are the available columns in table '{{Goods}}':
 * @property integer $id
 * @property string $name
 * @property integer $shop_id
 * @property integer $favorite_nums
 * @property integer $order_nums
 * @property integer $rate_nums
 * @property integer $rate
 * @property integer $comment_nums
 * @property integer $is_new
 * @property integer $is_tuan
 * @property string $pic
 * @property integer $state
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property float $rate_avg
 * @property integer $orderid
 * @property integer $is_carry
 */
class Goods extends CActiveRecord
{
	/**
 	*  商品状态
 	*/
	const STATE_SELL = 1;
    const STATE_NOSELL = 2;
    const STATE_SELLOUT = 3;
    
	public static $states = array(
		self::STATE_SELL => '上架',
		self::STATE_NOSELL => '下架',
		self::STATE_SELLOUT => '售完'
	);
	
	public static $goodsTbl = array(
	    ShopCategory::CATEGORY_FOOD => 'foodGoods',
	    ShopCategory::CATEGORY_CAKE => 'cakeGoods',
	    ShopCategory::CATEGORY_FLOWER => 'flowerGoods',
	);
	/**
	 * Returns the static model of the specified AR class.
	 * @return Goods the static model class
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
		return '{{Goods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, shop_id', 'required'),
			array('is_new, is_tuan, state, shop_id, favorite_nums, order_nums, rate_nums, rate, comment_nums, create_time, update_time, orderid, is_carry', 'numerical', 'integerOnly'=>true),
			array('rate_avg', 'numerical', 'max'=>9.9, 'min'=>0),
			array('name', 'length', 'max'=>100),
			array('pic', 'length', 'max'=>255),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, shop_id, favorite_nums, order_nums, rate_nums, rate, comment_nums, is_new, is_tuan, pic, state, create_time, create_ip, update_time, update_ip, orderid, is_carry', 'safe', 'on'=>'search'),
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
			'cakeGoods' => array(self::HAS_ONE, 'CakeGoods', 'goods_id'),
			'foodGoods' => array(self::HAS_ONE, 'FoodGoods', 'goods_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'goodsRateLogs' => array(self::HAS_MANY, 'GoodsRateLog', 'goods_id'),
		    'goodsRateCount' => array(self::STAT, 'GoodsRateLog', 'goods_id'),
			'tags' => array(self::MANY_MANY, 'Tag', '{{GoodsTag}}(goods_id, tag_id)'),
			'orderGoods' => array(self::HAS_MANY, 'OrderGoods', 'goods_id'),
			'otherGoods' => array(self::HAS_ONE, 'OtherGoods', 'goods_id'),
			'userGoodsFavorites' => array(self::HAS_MANY, 'UserGoodsFavorite', 'goods_id'),
			'dayList' => array(self::HAS_MANY, 'DayList', 'goods_id'),
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
			'name' => '名称',
			'shop_id' => '商铺',
			'favorite_nums' => '收藏次数',
			'order_nums' => '订单数量',
			'rate_nums' => '评分次数',
			'rate' => '评分值',
			'rate_avg' => '评分平均值',
			'comment_nums' => '评论条数',
			'is_new' => '是否新品',
			'is_tuan' => '是否团购',
			'pic' => '图片',
			'state' => '状态',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'update_time' => '修改时间',
			'update_ip' => '修改IP',
			'orderid' => '商品排序',
			'is_carry' => '自提'
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('favorite_nums',$this->favorite_nums,true);

		$criteria->compare('order_nums',$this->order_nums,true);

		$criteria->compare('rate_nums',$this->rate_nums,true);

		$criteria->compare('rate',$this->rate,true);
		
		$criteria->compare('rate_avg',$this->rate_avg,true);

		$criteria->compare('comment_nums',$this->comment_nums,true);

		$criteria->compare('is_new',$this->is_new);

		$criteria->compare('is_tuan',$this->is_tuan);

		$criteria->compare('pic',$this->pic,true);

		$criteria->compare('state',$this->state);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);
		
		$criteria->compare('orderid',$this->orderid,true);
		
		$criteria->compare('is_carry',$this->is_carry,true);

		return new CActiveDataProvider('Goods', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 获取商品状态
	 */
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
	        $counters = array('goods_nums' => 1);
	        Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	    }
	    return true;
	}
	
	protected function afterDelete()
	{
		parent::afterDelete();
		$counters = array('goods_nums' => -1);
	    Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	    return true;
	}
	
	/**
	 * 获取商品口味评分平均值
	 */
	public function getRateAverageMark()
	{
		if ($this->rate_nums == 0) return 3.5;
		return round($this->rate/$this->rate_nums, 1);
	}
	
	public function getRateStarWidth()
	{
	    return (int)($this->rateAverageMark * 50 / 5);
	}
	
	
	public function getBuyBtn($week=null, $cakepriceid=null, $jump=null)
	{
		if($this->shop->isOpening) {
			//$imageBuyGray = CHtml::image(resBu('images/pixel.gif'), '购买', array('class'=>'bg-pic buy-gray'));
			$imageBuy = CHtml::image(resBu('images/pixel.gif'), '购买', array('class'=>'bg-pic buy'));
			if($jump && $this->shop->business_state == Shop::BUSINESS_STATE_OPEN) {
				return l($imageBuy, url('goods/show', array('goodsid'=>$this->id)), array('title'=>'购买'));
			}
			if($this->shop->is_dailymenu == Shop::DAILYMENU_SUPPORT && $week && date('N') != $week) {
				return '&nbsp;'; //l($imageBuyGray, 'javascript:void(0);', array('title'=>'购买'));
			}
			if($this->shop->business_state == Shop::BUSINESS_STATE_OPEN)
				return l($imageBuy, url('cart/create', array('goodsid'=>$this->id)), array('title'=>'购买', 'class'=>'btn-buy', 'cakepriceid'=>$cakepriceid));
			elseif($this->shop->business_state == Shop::BUSINESS_STATE_CLOSE)
				return '<div class="lh20px cgray">' . Shop::$business_states[Shop::BUSINESS_STATE_CLOSE] . '</div>';
			elseif($this->shop->business_state == Shop::BUSINESS_STATE_SUSPEND)
				return '<div class="lh20px cgray">' . Shop::$business_states[Shop::BUSINESS_STATE_SUSPEND] . '</div>';
		} else {
			return '&nbsp;';
		}
	}
	
	public function getGroupBtn($week=null, $cakepriceid=null, $jump=null)
	{
		//$imageBuyGray = CHtml::image(resBu('images/pixel.gif'), '购买', array('class'=>'bg-pic buy-gray'));
		$imageBuy = CHtml::image(resBu('images/pixel.gif'), '团购', array('class'=>'bg-pic buy-group'));
		if($jump && $this->shop->business_state == Shop::BUSINESS_STATE_OPEN) {
			return l($imageBuy, url('goods/show', array('goodsid'=>$this->id)), array('title'=>'团购'));
		}
		if($this->shop->is_dailymenu == Shop::DAILYMENU_SUPPORT && $week && date('N') != $week) {
			return '&nbsp;'; //l($imageBuyGray, 'javascript:void(0);', array('title'=>'购买'));
		}
		if($this->shop->business_state == Shop::BUSINESS_STATE_OPEN)
			return l($imageBuy, url('cart/create', array('goodsid'=>$this->id, 'is_group'=>STATE_ENABLED)), array('title'=>'团购', 'class'=>'btn-buy', 'cakepriceid'=>$cakepriceid));
		elseif($this->shop->business_state == Shop::BUSINESS_STATE_CLOSE)
			return '<div class="lh20px cgray">' . Shop::$business_states[Shop::BUSINESS_STATE_CLOSE] . '</div>';
		elseif($this->shop->business_state == Shop::BUSINESS_STATE_SUSPEND)
			return '<div class="lh20px cgray">' . Shop::$business_states[Shop::BUSINESS_STATE_SUSPEND] . '</div>';
	}
	
	public function getPicUrl()
	{
	    if ($this->pic)
		    return sbu($this->pic);
		else
		    return null;
	}
	
	/**
	 * 获取商品缩略图
	 */
	public function getPicHtml()
	{
		return CHtml::image($this->picUrl,$this->name, array('class'=>'goods-pic'));
	}
	
	/**
	 * 获取商品缩略图链接
	 */
	public function getPicLinkHtml()
	{
		return l($this->picHtml, $this->absoluteUrl, array('target'=>'_blank', 'title'=>$this->name, 'class'=>'goods-pic-link'));
	}
	
	/**
	 * 获取商品名称链接
	 */
	public function getNameLinkHtml($len = 0)
	{
		return l($this->getShortName($len), $this->absoluteUrl, array('target'=>'_blank', 'class'=>'goods-name', 'title'=>$this->name));
	}
	
	/**
	 * 截取一定长度的名称
	 * @param integer $len 截取长度
	 */
	public function getShortName($len = 0)
	{
	    if (0 == $len) return $this->name;
	    return mb_strimwidth($this->name, 0, $len, '..');
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('goods/show', array('goodsid' => $this->id));
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('goods/show', array('goodsid' => $this->id));
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
	
	public function getIsNewIcon()
	{
	    if (!$this->is_new) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        '新品推荐',
	        array(
	            'title' => '新品推荐',
	            'class' => 'bg-icon is-new'
	        )
	    );
	}
	
	public function getGoodsIcons()
	{
	    switch ($this->shop->category_id) {
	        case ShopCategory::CATEGORY_FOOD:
	            if ($this->goodsModel->isSpicyIcon) $html .= $this->goodsModel->isSpicyIcon;
	            berak;
	        default:
	            break;
	    }
        if ($this->isNewIcon) $html .= $this->isNewIcon;
	    return $html;
	}
	
	public function getFullName()
	{
	    return $this->nameLinkHtml . '&nbsp;' . $this->goodsIcons;
	}
	
	public function getFavoriteHtml()
	{
	    $alt = '加入我的收藏夹';
	    return l(CHtml::image(resBu('images/pixel.gif'), $alt, array('title'=>$alt, 'class'=>'bg-icon goods-favorite')),
	        url('goods/favorite', array('goodsid'=>$this->id))
	    );
	}
	
	public function getMarketPrice()
	{
	    return $this->goodsModel->marketPrice;
	}
	
	public function getWmPrice()
	{
	    return $this->goodsModel->wmPrice;
	    
	}
	
    public function getGroupPrice()
	{
	    return $this->goodsModel->groupPrice;
	}
	
	public function getDesc()
	{
	    return $this->{self::$goodsTbl[$this->shop->category_id]}->desc;
	}
	
	public function getSpicyText()
	{
	    return $this->goodsModel->spicyText;
	}
	
	public function getTagsArray()
	{
	    if (empty($this->tags)) return array();
	    foreach ($this->tags as $v) $tags[$v->id] = $v->name;
	    return $tags;
	}
	
	public function getTagsText()
	{
	    return implode(', ', $this->tagsArray);
	}
	
	public function getTagsHtml()
	{
	    if (empty($this->tags)) return null;
	    foreach ($this->tags as $v) {
	        $tags[] = l($v->name, url('goods/search', array('kw'=>$v->name)));
	    }
	    return implode(', ', $tags);
	}
	
	/**
	 * 获取商品列表，按照分类、是不有图片，商品ID排序
	 * @param array
	 */
	public static function getSortGoods($goods)
	{
	    if (null == $goods) return null;
	    
	    $data = array();
	    foreach ($goods as $v) {
	        $data[$v->goodsModel->goodsCategory->orderid][$v->goodsModel->goodsCategory->name][] = $v;
	    }
	    krsort($data);
	    
	    $temp = array();
	    foreach ($data as $v) {
	    	$temp += $v;
	    }
	    
	    return $temp;
	}
	
	public function getGoodsModel()
	{
	    $cid = $this->shop->category_id;
	    if (key_exists($cid, self::$goodsTbl))
	        return $this->{self::$goodsTbl[$cid]};
	    else
	        throw new CException('系统错误', 500);
	}
	
	public static function getHotGoods(array $ids, $count = 8)
	{
	    /*
	     * 获取热卖美食
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addInCondition('shop_id', $ids);
	    $criteria->addColumnCondition(array('shop.business_state' => Shop::BUSINESS_STATE_OPEN));
	    $criteria->limit = (int)$count;
	    $criteria->order = 't.order_nums';
	    $goods = Goods::model()->with('shop', 'foodGoods')->findAll($criteria);
	    return $goods;
	}

}