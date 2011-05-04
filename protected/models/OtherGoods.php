<?php

/**
 * This is the model class for table "{{OtherGoods}}".
 *
 * The followings are the available columns in table '{{OtherGoods}}':
 * @property integer $goods_id
 * @property float $market_price
 * @property float $wm_price
 * @property string $big_pic
 * @property string $desc
 */
class OtherGoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OtherGoods the static model class
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
		return '{{OtherGoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('market_price', 'wm_price', 'required'),
			array('goods_id, desc', 'required'),
			array('goods_id', 'numerical', 'integerOnly'=>true),
			array('market_price, wm_price', 'number', 'max'=>99999.99, 'min'=>0),
			array('big_pic', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('goods_id, price, big_pic, desc', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'goods_id' => '商品',
			'market_price' => '市场价格',
			'wm_price' => '外卖价格',
			'big_pic' => '图片',
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

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('market_price',$this->market_price,true);
		
		$criteria->compare('wm_price',$this->wm_price,true);

		$criteria->compare('big_pic',$this->big_pic,true);

		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider('OtherGoods', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterDelete()
	{
		parent::afterDelete();
	    $goods = Goods::model()->findByPk($this->goods_id);
		$goods->delete();
	    return true;
	}
	
	/**
	 * 格式化市场价
	 */
	public function getMarketPrice() 
	{
	    if ((int)$this->market_price == ($this->wm_price + 0)) return (int)$this->market_price;
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
}