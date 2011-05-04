<?php

/**
 * This is the model class for table "{{CakeGoods}}".
 *
 * The followings are the available columns in table '{{CakeGoods}}':
 * @property integer $goods_id
 * @property integer $category_id
 * @property integer $shape_id
 * @property string $label
 * @property string $buy_advice
 * @property string $stuff
 * @property string $pack
 * @property string $taste
 * @property string $fresh_condition
 * @property integer $saccharinity
 * @property integer $is_sugar
 * @property integer $is_cake_blessing
 * @property integer $is_card_blessing
 * @property string $big_pic
 * @property string $small_pic
 * @property float $market_price
 * @property float $wm_price
 */
class CakeGoods extends CActiveRecord
{
	/**
     * 祝福语连接符
     */
    const SEPARATOR_BLESSING = '||';
    
	/**
     * 蛋糕商品类别：蛋糕1，月饼2，面包3，西点4，茶点5
     */
    const CATEGROY_CAKE = 1;
    const CATEGROY_MOONCAKE = 2;
    const CATEGROY_BREAD = 3;
    const CATEGROY_WESTPASTA = 4;
    const CATEGROY_REFRESHMENT = 5;
    
    public static $categorys = array(
        self::CATEGROY_CAKE => '蛋糕',
        self::CATEGROY_MOONCAKE => '月饼',
        self::CATEGROY_BREAD => '面包',
        self::CATEGROY_WESTPASTA => '西点',
        self::CATEGROY_REFRESHMENT => '茶点',
    );
	
    /**
 	*  蛋糕造型分类
 	*/
    const SHAPE_ROUND = 1;
    const SHAPE_HEART = 2;
    const SHAPE_SQUARE = 3;
    const SHAPE_MULTIPLE = 4;
    
	public static $shapes = array(
		self::SHAPE_ROUND	=> '圆形蛋糕',
		self::SHAPE_HEART	=> '心型蛋糕',
		self::SHAPE_SQUARE => '方形蛋糕',
		self::SHAPE_MULTIPLE => '多层蛋糕',  
	);
	
	/**
 	*  是否无糖
 	*/
	const SUGAR_FREE = 0;
    const SUGAR_LESS = 1;
    const SUGAR_HIGH = 2;
    
	public static $sugars = array(
		self::SUGAR_FREE => '无糖',
		self::SUGAR_LESS => '低糖',
		self::SUGAR_HIGH => '高糖'
	);
		
	/**
 	*  甜度
 	*/
	const SACCHARINITY_FREE = 0;
    const SACCHARINITY_LESS = 1;
    const SACCHARINITY_HIGH = 2;
    
	public static $saccharinitys = array(
		self::SACCHARINITY_FREE => '不甜',
		self::SACCHARINITY_LESS => '微甜',
		self::SACCHARINITY_HIGH => '很甜'
	);
		
	/**
 	*  甜度
 	*/
	const CARD_DISABLED = 0;
    const CARD_ENABLED = 1;
    
	public static $card_blessings = array(
		self::CARD_DISABLED => '否',
		self::CARD_ENABLED => '是',
	);
	
	/**
	 * 是否允许写蛋糕祝福语
	 */
	public static $cake_blessing = array(
		STATE_ENABLED => '有',
		STATE_DISABLED => '无'
	);
	
	/**
	 * 是否允许写贺卡祝福语
	 */
	public static $card_blessing = array(
		STATE_ENABLED => '有',
		STATE_DISABLED => '无'
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CakeGoods the static model class
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
		return '{{CakeGoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, category_id, shape_id', 'required'),
			array('goods_id', 'unique'),
			array('goods_id, category_id, shape_id, saccharinity, is_sugar, is_cake_blessing, is_card_blessing', 'numerical', 'integerOnly'=>true),
			array('label, buy_advice, stuff, pack, taste, fresh_condition, big_pic, small_pic', 'length', 'max'=>255),
			array('market_price, wm_price', 'numerical', 'max'=>99999.99, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('goods_id, category_id, shape_id, label, buy_advice, stuff, pack, taste, fresh_condition, saccharinity, is_sugar, is_cake_blessing, is_card_blessing, big_pic, small_pic, market_price, wm_price', 'safe', 'on'=>'search'),
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
			'cakePrices' => array(self::HAS_MANY, 'CakePrice', 'goods_id', 'order'=>'size asc'),
			'Purposes' => array(self::MANY_MANY, 'Purpose', '{{CakePurpose}}(goods_id, purpose_id)'),
			'Varietys' => array(self::MANY_MANY, 'Variety', '{{CakeVariety}}(goods_id, variety_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'goods_id' => '商品',
			'category_id' => '蛋糕分类',
			'shape_id' => '蛋糕形状',
			'label' => '蛋糕小语',
			'buy_advice' => '购买建议',
			'stuff' => '材料',
			'pack' => '包装',
			'taste' => '口味',
			'fresh_condition' => '保鲜条件',
			'saccharinity' => '甜度',
			'is_sugar' => '是否无糖',
			'is_cake_blessing' => '蛋糕祝福语',
			'is_card_blessing' => '贺卡祝福语',
			'big_pic' => '全景图',
			'small_pic' => '切面图',
			'market_price' => '市场价', 
			'wm_price' => '外卖价',
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

		$criteria->compare('shape_id',$this->shape_id,true);

		$criteria->compare('label',$this->label,true);

		$criteria->compare('buy_advice',$this->buy_advice,true);

		$criteria->compare('stuff',$this->stuff,true);

		$criteria->compare('pack',$this->pack,true);

		$criteria->compare('taste',$this->taste,true);

		$criteria->compare('fresh_condition',$this->fresh_condition,true);

		$criteria->compare('saccharinity',$this->saccharinity);

		$criteria->compare('is_sugar',$this->is_sugar);

		$criteria->compare('is_cake_blessing',$this->is_cake_blessing);

		$criteria->compare('is_card_blessing',$this->is_card_blessing);

		$criteria->compare('big_pic',$this->big_pic,true);

		$criteria->compare('small_pic',$this->small_pic,true);
		
		$criteria->compare('market_price',$this->market_price,true);
		
		$criteria->compare('wm_price',$this->wm_price,true);

		return new CActiveDataProvider('CakeGoods', array(
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
	 * 获取蛋糕全景图片
	 */
	public function getBigPicHtml() 
	{
		return CHtml::image(sbu($this->big_pic),'',array('class'=>'cake-goods-big-pic'));
	}
	
	/**
	 * 获取蛋糕切面图片
	 */
	public function getSmallPicHtml()
	{
		return CHtml::image(sbu($this->small_pic),'',array('class'=>'cake-goods-small-pic'));
	}
	
    /**
     * 获取分类名称
     */
	public function getCategoryText()
	{
	    return self::$categorys[$this->category_id];
	}
	
    /**
     * 获取造型分类名称
     */
	public function getShapeText()
	{
	    return self::$shapes[$this->shape_id];
	}
	
	/**
	 * 获取是否无糖
	 */
	public function getSugarText()
	{
		return self::$sugars[$this->is_sugar];
	}
	
	/**
	 * 获取甜度
	 */
	public function getSaccharinitysText()
	{
		return self::$saccharinitys[$this->saccharinity];
	}
	
	/**
	 * 获取是否允许写蛋糕祝福语
	 */
	public function getCakeBlessingText() 
	{
		return self::$cake_blessing[$this->is_cake_blessing];
	}
	
	/**
	 * 获取是否允许写贺卡祝福语
	 */
	public function getCardBlessingText() 
	{
		return self::$card_blessing[$this->is_card_blessing];
	}
	
	public function getLabelText($len=0) 
	{
		if (0 == $len) return h($this->label);
	    return h(mb_strimwidth($this->label, 0, $len, '..'));
	}
}