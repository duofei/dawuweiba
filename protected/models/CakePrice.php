<?php

/**
 * This is the model class for table "{{CakePrice}}".
 *
 * The followings are the available columns in table '{{CakePrice}}':
 * @property integer $id
 * @property integer $goods_id
 * @property integer $size
 * @property float $market_price
 * @property float $wm_price
 * @property string $desc
 */
class CakePrice extends CActiveRecord
{
	/*
     * 蛋糕尺寸
     */
   
    public static $cakesizeexplanation = array(
        8 => '8寸（2-3人食用）',
        10 => '10寸（4-6人食用）',
        12 => '12寸（6-9人食用）',
        14 => '14寸（9-12人食用）',
        16 => '16寸（12-15人食用）',
        18 => '18寸（15-18人食用）',
        20 => '20寸（中型聚会）',
    );

	/**
	 * Returns the static model of the specified AR class.
	 * @return CakePrice the static model class
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
		return '{{CakePrice}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, size, market_price, wm_price', 'required'),
			array('goods_id, size', 'numerical', 'integerOnly'=>true),
			array('market_price, wm_price', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('desc', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, goods_id, size, market_price, wm_price, desc', 'safe', 'on'=>'search'),
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
			'goods' => array(self::BELONGS_TO, 'CakeGoods', 'goods_id'),
			'carts' => array(self::HAS_MANY, 'Cart', 'cakeprice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'goods_id' => '商品',
			'size' => '尺寸',
			'market_price' => '门店价',
			'wm_price' => '外卖价',
			'desc' => '描述',
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

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('size',$this->size);

		$criteria->compare('market_price',$this->market_price,true);

		$criteria->compare('wm_price',$this->wm_price,true);

		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider('CakePrice', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 格式化市场价
	 */
	public function getMarketPrice() 
	{
	    if ((int)$this->market_price == ($this->market_price + 0)) return (int)$this->market_price;
		return app()->format->number($this->market_price);
	}
	
	/**
	 * 格式化外卖价
	 */
	public function getWmPrice()
	{
	    if ((int)$this->wm_price == ($this->wm_price + 0)) return (int)$this->wm_price;
		return app()->format->number($this->wm_price);
	}
	
	/**
	 * 蛋糕尺寸
	 */
	public function getSizeText()
	{
		return $this->size . '寸';
	}
	
	/**
	 * 蛋糕尺寸说明 
	 */
	public function getSizeExplanation()
	{
		return self::$cakesizeexplanation[$this->size] ? self::$cakesizeexplanation[$this->size] : $this->sizeText;
	}
}