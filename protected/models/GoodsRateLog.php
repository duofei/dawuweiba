<?php

/**
 * This is the model class for table "{{GoodsRateLog}}".
 *
 * The followings are the available columns in table '{{GoodsRateLog}}':
 * @property integer $id
 * @property integer $ordergoods_id
 * @property integer $goods_id
 * @property integer $user_id
 * @property integer $shop_id
 * @property integer $create_time
 * @property string $create_ip
 * @property string $content
 * @property integer $mark
 */
class GoodsRateLog extends CActiveRecord
{
	
	const STARS_1 = 1;
	const STARS_2 = 2;
    const STARS_3 = 3;
    const STARS_4 = 4;
    const STARS_5 = 5;

    public static $stars = array(
    	self::STARS_1 => '差评',
        self::STARS_2 => '差点意思',
        self::STARS_3 => '一般般',
        self::STARS_4 => '有点滋味',
        self::STARS_5 => '我的最爱',
    );
    
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return GoodsRateLog the static model class
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
		return '{{GoodsRateLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, user_id, shop_id, mark, ordergoods_id', 'required'),
			array('mark, goods_id, user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			array('content', 'length', 'max'=>255),
			array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, goods_id, user_id, shop_id, create_time, create_ip, content, ordergoods_id, mark', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'orderGoods' => array(self::BELONGS_TO, 'OrderGoods', 'ordergoods_id'),
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
			'goods_id' => '商品',
			'user_id' => '用户',
			'shop_id' => '商铺',
			'ordergoods_id' => '订单商品',
			'create_time' => '添加时间',
			'create_ip' => '添加',
			'content' => '日志',
			'mark' => '分值',
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
		
		$criteria->compare('ordergoods_id',$this->ordergoods_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('user_id',$this->user_id,true);
		
		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('mark',$this->mark);

		return new CActiveDataProvider('GoodsRateLog', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			// 增加Goods口味评分
	        $counters = array('rate_nums' => 1, 'rate'=>$this->mark);
	        Goods::model()->updateCounters($counters, 'id = ' . $this->goods_id);
	       	//增加Shop口味评分
	       	$counters = array('taste_mark_nums' => 1, 'taste_mark'=>$this->mark);
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop->id);
	       	
	       	$mark = ($this->goods->rate + $this->mark) / ($this->goods->rate_nums + 1);
	       	$this->goods->rate_avg = $mark;
	       	$this->goods->update();
	       	$mark = ($this->goods->shop->taste_mark + $this->mark) / ($this->goods->shop->taste_mark_nums + 1);
	       	$this->goods->shop->taste_avg = $mark;
	       	$this->goods->shop->update();
	    }
	    return true;
	}
	
	/**
	 * 获取星值评论
	 */
	public function getStarText()
	{
		return self::$stars[$this->mark];
	}
	public function getRateStarWidth()
	{
	    return (int)($this->mark * 50 / 5);
	}
	
	/**
	 * 获取评论内容
	 */
	public function getContentText()
	{
		return $this->content ? h($this->content) : $this->starText;
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