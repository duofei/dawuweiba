<?php

/**
 * This is the model class for table "{{UserGoodsFavorite}}".
 *
 * The followings are the available columns in table '{{UserGoodsFavorite}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property float $goods_price
 * @property integer $create_time
 * @property string $create_ip
 */
class UserGoodsFavorite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserGoodsFavorite the static model class
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
		return '{{UserGoodsFavorite}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, goods_id, goods_name, goods_price', 'required'),
			array('user_id, goods_id, create_time', 'numerical', 'integerOnly'=>true),
			array('goods_name', 'length', 'max'=>50),
			array('goods_price', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, goods_id, goods_name, goods_price, create_time, create_ip', 'safe', 'on'=>'search'),
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => '用户',
			'goods_id' => '商品',
			'goods_name' => '商品名称',
			'goods_price' => '商品价格',
			'create_time' => '收藏时间',
			'create_ip' => 'Ip',
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

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);

		$criteria->compare('goods_name',$this->goods_name,true);

		$criteria->compare('goods_price',$this->goods_price,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('UserGoodsFavorite', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->isNewRecord) {
			// array('user_id','default','value'=>user()->id, 'setOnEmpty'=>false, 'on'=>'insert'),
			// 把rules里的更改到了这里
			if(intval($this->user_id) <= 0) {
				$this->user_id = user()->id;
			}
		}
		return true;
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
	       	$counters = array('favorite_nums' => 1);
	       	Goods::model()->updateCounters($counters, 'id = ' . $this->goods_id);
	    }
	    return true;
	}
	protected function afterDelete()
	{
		parent::afterDelete();
	    $counters = array('favorite_nums' => -1);
	    Goods::model()->updateCounters($counters, 'id = ' . $this->goods_id);
	    return true;
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
	 * 格式金额
	 */
	public function getMarketPriceText() 
	{
		return app()->format->number($this->goods_price);
	}
	
	/**
	 * 获取商品名称链接
	 */
	public function getNameLinkHtml()
	{
		return l($this->goods_name, url('goods/show', array('goodsid' => $this->goods_id)), array('target'=>'_blank', 'class'=>'goods-name'));
	}
	
}