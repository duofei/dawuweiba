<?php

/**
 * This is the model class for table "{{OrderGoods}}".
 *
 * The followings are the available columns in table '{{OrderGoods}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property float $goods_price
 * @property float $group_price
 * @property integer $goods_nums
 * @property float $goods_amount
 * @property float $group_amount
 * @property string $remark
 */
class OrderGoods extends CActiveRecord
{
	public $token = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderGoods the static model class
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
		return '{{OrderGoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, order_id','required'),
			array('goods_nums, order_id, goods_id', 'numerical', 'integerOnly'=>true),
			array('goods_name', 'length', 'max'=>255),
			array('goods_price, goods_amount, group_price, group_amount', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('remark', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, goods_id, goods_name, goods_price, goods_nums, goods_amount, group_price, group_amount, remark', 'safe', 'on'=>'search'),
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
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'goodsRateLog' => array(self::HAS_ONE, 'GoodsRateLog', 'ordergoods_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => '订单',
			'goods_id' => '商品',
			'goods_name' => '商品名称',
			'goods_price' => '商品价格',
			'goods_nums' => '购买数量',
			'goods_amount' => '总价',
			'group_price' => '同楼价格',
			'group_amount' => '同楼总价',
			'remark' => '备注',
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

		$criteria->compare('order_id',$this->order_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('goods_name',$this->goods_name,true);

		$criteria->compare('goods_price',$this->goods_price,true);

		$criteria->compare('goods_nums',$this->goods_nums);

		$criteria->compare('group_price',$this->group_price,true);
		
		$criteria->compare('goods_amount',$this->goods_amount,true);
		
		$criteria->compare('group_amount',$this->group_amount,true);

		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider('OrderGoods', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		$goodsinfo = Cart::getGoodsInfo($this->goods_id, $this->token);
		$this->goods_name = $goodsinfo->goods_name;
		$this->goods_nums = (int)$goodsinfo->goods_nums;
		$this->goods_price = $goodsinfo->goods_price;
		$this->group_price = $goodsinfo->group_price;
		$this->goods_amount = $this->goods_nums * $this->goods_price;
		$this->group_amount = $this->goods_nums * $this->group_price;
		$this->remark = $goodsinfo->remark;
		return true;
	}
	
	/**
	 * 格式化商品价格
	 */
	public function getGoodsPrice()
	{
	    if ((int)$this->goods_price == ($this->goods_price + 0)) return (int)$this->goods_price;
		return app()->format->number($this->goods_price);
	}
	
	/**
	 * 格式化总价格
	 */
	public function getGoodsAmount()
	{
		if ((int)$this->goods_amount == ($this->goods_amount + 0)) return (int)$this->goods_amount;
		return app()->format->number($this->goods_amount);
	}
	
	/**
	 * 获取商品链接
	 */
	public function getGoodsNameLinkHtml()
	{
		return l($this->goods_name, url('goods/show', array('goodsid' => $this->goods_id)), array('target'=>'_blank', 'class'=>'godos-name'));
	}
}