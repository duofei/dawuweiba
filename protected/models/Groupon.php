<?php

/**
 * This is the model class for table "{{Groupon}}".
 *
 * The followings are the available columns in table '{{Groupon}}':
 * @property integer $id
 * @property string $date
 * @property integer $shop_id
 * @property integer $location_id
 * @property integer $order_nums
 * @property float $group_amount
 * @property float $amount
 * @property float $shop_group_price
 */
class Groupon extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Groupon the static model class
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
		return '{{Groupon}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, location_id, date','required'),
			array('shop_id, location_id, order_nums', 'numerical', 'integerOnly'=>true),
			array('date', 'length', 'is'=>10),
			array('group_amount, amount, shop_group_price', 'numerical', 'max'=>99999.99, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, location_id, date, order_nums, group_amount, amount', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => '日期',
			'shop_id' => '商铺',
			'location_id' => '楼宇',
			'order_nums' => '订单数量',
			'group_amount' => '已达成同楼订餐金额',
			'amount' => '已达成正常订餐金额',
			'shop_group_price' => '商铺设置的同楼订餐金额',
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

		$criteria->compare('date',$this->date,true);

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('location_id',$this->location_id,true);

		$criteria->compare('order_nums',$this->order_nums,true);

		$criteria->compare('group_amount',$this->group_amount);

		$criteria->compare('amount',$this->amount,true);
		
		$criteria->compare('shop_group_price',$this->shop_group_price,true);

		return new CActiveDataProvider('Groupon', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 增加同楼订餐
	 */
	public static function addOrder($order)
	{
		// 如果不是一条新的order或没有building_id直接返回false
		if($order->groupon_id || !$order->building_id) {
			return false;
		}
		$date = self::getMkDate();
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('date'=>$date, 'shop_id'=>$order->shop_id, 'location_id'=>$order->building_id));
		$groupon = self::model()->find($criteria);
		// 判断是否已存在当天的记录
		if(null === $groupon) {
			$groupon = new self();
			$groupon->date = $date;
			$groupon->shop_id = $order->shop_id;
			$groupon->location_id = $order->building_id;
			$groupon->order_nums = 1;
			$groupon->group_amount = $order->group_amount;
			$groupon->amount = $order->amount;
			$groupon->shop_group_price = $order->shop->group_success_price;
		} else {
			$groupon->order_nums++;
			$groupon->group_amount += $order->group_amount;
			$groupon->amount += $order->amount;
		}
		if($groupon->save()) {
			// 保存成功，改更order里groupon_id的值
			$order->groupon_id = $groupon->id;
			if($order->save())
				return $groupon;
		}
		return false;
	}

	/**
	 * 减少同楼订餐
	 */
	public static function loseOrder($order)
	{
		// 如果不是同楼订餐订单或没有building_id直接返回false
		if(!$order->groupon_id || !$order->building_id) {
			return false;
		}
		$date = self::getMkDate();
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('date'=>$date, 'shop_id'=>$order->shop_id, 'location_id'=>$order->building_id));
		$groupon = self::model()->find($criteria);
		if($groupon) {
			$groupon->order_nums--;
			$groupon->group_amount -= $order->group_amount;
			$groupon->amount -= $order->amount;
			if($groupon->save()) {
				return $groupon;
			}
		}
		return false;
	}
	
	/**
	 * 计算date时间
	 */
	public static function getMkDate()
	{
		$groupEndTime = self::getTodayGroupEndTime();
		if($groupEndTime > time()) {
			return date(param('formatDate'));
		} else {
			return date(param('formatDate'), mktime(0,0,0,date('m'),(date('d')+1),date('Y')));
		}
	}
	
	/**
	 * 获取今天同楼订餐的结束时间
	 */
	public static function getTodayGroupEndTime()
	{
		$t = explode(':', param('grouponEndTime'));
		return mktime($t[0],$t[1],$t[2],date('m'),date('d'),date('Y'));
	}
	
	/**
	 * 格式化价格
	 */
	public function getGroupAmountPrice()
	{
	    if ((int)$this->group_amount == ($this->group_amount + 0)) return (int)$this->group_amount;
		return app()->format->number($this->group_amount);
	}
	public function getAmountPrice()
	{
	    if ((int)$this->amount == ($this->amount + 0)) return (int)$this->amount;
		return app()->format->number($this->amount);
	}
	
	public function getPriceWidth()
	{
		if($this->shop_group_price && $this->shop_group_price > $this->amount) {
			return intval(($this->amount/$this->shop_group_price)*550);
		}
		return 550;
	}
}