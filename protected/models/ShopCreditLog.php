<?php

/**
 * This is the model class for table "{{ShopCreditLog}}".
 *
 * The followings are the available columns in table '{{ShopCreditLog}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $shop_id
 * @property integer $evaluate
 * @property string $comment
 * @property integer $create_time
 * @property string $create_ip
 */
class ShopCreditLog extends CActiveRecord
{
	/**
	 * 评价
	 */
	const EVALUATE_GOOD = 1;
    const EVALUATE_BAD = 0;
    
	public static $evaluates = array(
		self::EVALUATE_GOOD => '好评',
		self::EVALUATE_BAD => '差评'
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopCreditLog the static model class
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
		return '{{ShopCreditLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, shop_id, evaluate', 'required'),
			array('order_id, shop_id, create_time, evaluate', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, shop_id, evaluate, comment, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'order_id' => '订单',
			'shop_id' => '商铺',
			'evaluate' => '评价',
			'comment' => '商家评论',
			'create_time' => '评论时间',
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

		$criteria->compare('order_id',$this->order_id,true);

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('evaluate',$this->evaluate);

		$criteria->compare('comment',$this->comment,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('ShopCreditLog', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			$value = $this->evaluate ? 1 : 0;
			$counters = array('service_mark_nums' => 1, 'service_mark'=>$value);
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	       	$mark = ($this->shop->service_mark + $value) / ($this->shop->service_mark_nums + 1);
	       	$this->shop->service_avg = $mark;
	       	$this->shop->update();
	    }
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
     * 评价
     */
	public function getEvaluatesText()
	{
	    return self::$evaluates[$this->evaluate];
	}
}