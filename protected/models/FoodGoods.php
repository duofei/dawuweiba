<?php

/**
 * This is the model class for table "{{FoodGoods}}".
 *
 * The followings are the available columns in table '{{FoodGoods}}':
 * @property integer $goods_id
 * @property integer $category_id
 * @property float $market_price
 * @property float $wm_price
 * @property float $group_price
 * @property integer $is_spicy
 * @property string $desc
 */
class FoodGoods extends CActiveRecord
{
	/**
	 * 辣不辣
	 */
	public static $spicys = array(
		STATE_ENABLED => '辣',
		STATE_DISABLED => '不辣'
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return FoodGoods the static model class
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
		return '{{FoodGoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, category_id, wm_price', 'required'),
			array('goods_id, is_spicy, category_id', 'numerical', 'integerOnly'=>true),
			array('market_price, wm_price, group_price', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('goods_id, category_id, market_price, wm_price, group_price, is_spicy, desc', 'safe', 'on'=>'search'),
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
			'goodsCategory' => array(self::BELONGS_TO, 'GoodsCategory', 'category_id'),
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
			'category_id' => '商品分类',
			'market_price' => '门市价',
			'wm_price' => '外卖价',
			'group_price' => '团购价',
			'is_spicy' => '辣不辣',
			'desc' => '商品描述',
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

		$criteria->compare('category_id',$this->category_id,true);

		$criteria->compare('market_price',$this->market_price,true);

		$criteria->compare('wm_price',$this->wm_price,true);

		$criteria->compare('group_price',$this->group_price,true);

		$criteria->compare('is_spicy',$this->is_spicy);

		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider('FoodGoods', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
	        $counters = array('goods_nums' => 1);
	        GoodsCategory::model()->updateCounters($counters, 'id = ' . $this->category_id);
	    }
	    return true;
	}
	
	protected function afterDelete()
	{
		parent::afterDelete();
		$counters = array('goods_nums' => -1);
	    GoodsCategory::model()->updateCounters($counters, 'id = ' . $this->category_id);
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
	
	/**
	 * 格式化团购价
	 */
	public function getGroupPrice()
	{
	    if ((int)$this->group_price == ($this->group_price + 0)) return (int)$this->group_price;
		return app()->format->number($this->group_price);
	}
	
	/**
	 * 获取辣不辣
	 */
	public function	getSpicyText()
	{
		return self::$spicys[$this->is_spicy];
	}
	
	
	public function getIsSpicyIcon()
	{
	    if (STATE_ENABLED != $this->is_spicy) return null;
	    return CHtml::image(resBu('images/pixel.gif'),
	        $this->spicyText,
	        array(
	            'title' => $this->spicyText,
	            'class' => 'bg-icon is-spicy'
	        )
	    );
	}
}