<?php

/**
 * This is the model class for table "{{Cart}}".
 *
 * The followings are the available columns in table '{{Cart}}':
 * @property integer $id
 * @property string $guest_id
 * @property integer $goods_id
 * @property integer $cakeprice_id
 * @property integer $goods_nums
 * @property string $goods_name
 * @property float $goods_price
 * @property float $group_price
 * @property integer $create_time
 * @property string $create_ip
 * @property string $remark
 * @property integer $is_group
 */
class Cart extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Cart the static model class
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
		return '{{Cart}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, goods_name, goods_price, goods_nums', 'required'),
			array('goods_id, cakeprice_id, create_time, goods_nums, is_group', 'numerical', 'integerOnly'=>true),
			array('goods_price, group_price', 'numerical', 'max'=>9999.9, 'min'=>0),
			array('guest_id', 'length', 'is'=>32),
			array('goods_name', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('remark','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, guest_id, goods_id, cakeprice_id, goods_name, goods_price, create_time, create_ip, remark, group_price, is_group', 'safe', 'on'=>'search'),
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
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
			'cakeprice' => array(self::BELONGS_TO, 'CakePrice', 'cakeprice_id'),
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
			'guest_id' => 'CookieID',
			'goods_id' => '商品',
			'cakeprice_id' => '蛋糕价格',
			'goods_name' => '商品名称',
			'goods_price' => '商品价格',
			'group_price' => '同楼价格',
			'goods_nums' => '购买数量',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'remark' => '备注',
			'is_group' => '是否是同楼订餐',
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

		$criteria->compare('guest_id',$this->guest_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('cakeprice_id',$this->cakeprice_id,true);

		$criteria->compare('goods_name',$this->goods_name,true);

		$criteria->compare('goods_price',$this->goods_price,true);
		
		$criteria->compare('group_price',$this->group_price,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		$criteria->compare('goods_nums',$this->goods_nums,true);
		
		$criteria->compare('remark',$this->remark,true);
		
		$criteria->compare('is_group',$this->is_group,true);

		return new CActiveDataProvider('Cart', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->isNewRecord) {
			// array('guest_id','default','value'=>CdcBetaTools::getSiteToken(), 'setOnEmpty'=>false, 'on'=>'insert'),
			// 把rules里的更改到了这里
			if(strlen($this->guest_id) != 32) {
				$this->guest_id = CdcBetaTools::getSiteToken();
			}
		}
		return true;
	}
	
	/**
	 * 获取当前用户购物车里的一个商品
	 * @param $goods_id 购物车里的商品id
	 * @return object 商品信息的对象
	 */
	public static function getGoodsInfo($goods_id, $token = null)
	{
		$condition = new CDbCriteria();
		$token = (null === $token) ? CdcBetaTools::getSiteToken() : $token;
		$condition->addColumnCondition(array('guest_id' => $token));
		$condition->addColumnCondition(array('goods_id' => $goods_id));
		return self::model()->find($condition);
	}
	
	/**
	 * 获取当前用户购物车里所有的商品
	 * @param string $token
	 * @return object
	 */
	public static function getGoodsList($token = null)
	{
	    //static $data = null;
	    //if (null !== $data) return $data;
	    
		$condition = new CDbCriteria();
		$condition->order = 'create_time desc';
		$token = (null === $token) ? CdcBetaTools::getSiteToken() : $token;
		$condition->addColumnCondition(array('guest_id' => $token));
		$data = self::model()->findAll($condition);
		return $data;
	}
	
	/**
	 * 获取购物车中的商品数量
	 * @param string $token
	 */
	public static function getGoodsCount($token = null)
	{
		$condition = new CDbCriteria();
		$token = (null === $token) ? CdcBetaTools::getSiteToken() : $token;
		$condition->addColumnCondition(array('guest_id' => $token));
		return self::model()->count($condition);
	}

	/**
	 * 获取当前购物车里商品总价
	 */
	public static function getGoodsAmount($token = null)
	{
		$criteria = new CDbCriteria();
		$token = (null === $token) ? CdcBetaTools::getSiteToken() : $token;
		$criteria->addColumnCondition(array('guest_id' => $token));
		$data = self::model()->findAll($criteria);
		$amount = 0;
		foreach((array)$data as $v) {
			$amount += $v->goods_price * $v->goods_nums;
		}
		return $amount;
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('cart/show');
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('cart/show');
	}
	
	/**
	 * 获取商品链接
	 */
	public function getGoodsNameLinkHtml()
	{
		return l($this->goods_name, url('goods/show', array('goodsid' => $this->goods_id)), array('target'=>'_blank', 'class'=>'godos-name'));
	}
	
	/**
	 * 格式化收藏商品价格
	 */
	public function getGoodsPrice()
	{
		if ((int)$this->goods_price == ($this->goods_price + 0)) return (int)$this->goods_price;
		return app()->format->number($this->goods_price);
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

	public static function clearCart($token = null)
	{
	    $token = (null === $token) ? CdcBetaTools::getSiteToken() : $token;
	    return Cart::model()->deleteAllByAttributes(array('guest_id' => $token));
	}
	
	public function getRemarkArray()
	{
		if($this->remark) {
			return explode(CakeGoods::SEPARATOR_BLESSING, $this->remark);
		}
		return null;
	}
}

