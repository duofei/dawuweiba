<?php

/**
 * This is the model class for table "{{OrderLog}}".
 *
 * The followings are the available columns in table '{{OrderLog}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $type_id
 */
class OrderLog extends CActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderLog the static model class
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
		return '{{OrderLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, type_id', 'required'),
			array('type_id, order_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, create_time, create_ip, type_id', 'safe', 'on'=>'search'),
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
			'order_id' => '订单',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'type_id' => '类型',
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

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('type_id',$this->type_id);

		return new CActiveDataProvider('OrderLog', array(
			'criteria'=>$criteria,
		));
	}

	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			// 查询订单
			$order = Order::model()->findByPk($this->order_id);
			// 判断插入日志的type为已加工
			if(Order::STATUS_PROCESS == $this->type_id) {
				$counters = array('undressed_order_nums' => -1);
	       		Shop::model()->updateCounters($counters, 'id = ' . $order->shop_id);
	       		
	       		$order->status = Order::STATUS_PROCESS;
			}
			// 判断插入日志的type为已配送
			elseif (Order::STATUS_DELIVERING == $this->type_id) {
				$order->status = Order::STATUS_DELIVERING;
			}
			// 判断插入日志的type为已完成
			elseif (Order::STATUS_COMPLETE == $this->type_id) {
				// 查询该订单下的商品id
				$condition = new CDbCriteria;
				$condition->addCondition("order_id = ".$this->order_id);
				$condition->select = 'goods_id';
				$ordergoods = OrderGoods::model()->findAll($condition);
				$updateGoodsId = array();
				foreach($ordergoods as $value) {
					$updateGoodsId[] = $value->goods_id;
				}
				// 更新商品里的已完成订单数量
				$counters = array('order_nums' => 1);
				Goods::model()->updateCounters($counters, 'id in ( ' . implode($updateGoodsId, ',') . ')');
				$order->cancel_state = STATE_DISABLED;
				$order->status = Order::STATUS_COMPLETE;
			}
			// 判断插入日志的type为已取消
			elseif (Order::STATUS_CANCEL == $this->type_id) {
				// 判断订单状态为未处理
				if($order->status == Order::STATUS_UNDISPOSED) {
					$counters = array('undressed_order_nums' => -1);
	       			Shop::model()->updateCounters($counters, 'id = ' . $order->shop_id);
				}
				
				$order->status = Order::STATUS_CANCEL;
			}
			else {
				$order->status = $this->type_id;
			}
			$order->update();
	    }
	    return true;
	}
	
	/**
	 * 获取订单日志类型
	 */
	public function getStatusText()
	{
		return Order::$states[$this->type_id];
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