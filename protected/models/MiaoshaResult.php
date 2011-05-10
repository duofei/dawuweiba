<?php

/**
 * This is the model class for table "{{MiaoshaResult}}".
 *
 * The followings are the available columns in table '{{MiaoshaResult}}':
 * @property integer $id
 * @property integer $miaosha_id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $order_id
 * @property string $create_time
 * @property integer $create_ip
 */
class MiaoshaResult extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MiaoshaResult the static model class
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
		return '{{MiaoshaResult}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('miaosha_id, user_id, goods_id', 'required'),
			array('miaosha_id, user_id, goods_id, order_id, create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, miaosha_id, user_id, goods_id, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'miaosha' => array(self::BELONGS_TO, 'Miaosha', 'miaosha_id'),
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
			'miaosha_id' => 'Miaosha',
			'user_id' => 'User',
			'goods_id' => 'Goods',
			'order_id' => 'Order',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
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
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('miaosha_id',$this->miaosha_id,true);

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('goods_id',$this->goods_id,true);
		
		$criteria->compare('order_id',$this->order_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('MiaoshaResult', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortCreateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->create_time);
	}

	public static function addUntrues($miaosha_id)
	{
		$miaosha = Miaosha::model()->findByPk($miaosha_id);
		$miaoshaGoodsList = MiaoshaGoods::getGoodsList($miaosha_id);
		if(!$miaosha || !$miaoshaGoodsList) {
			return false;
		}
		$untrue_num = $miaosha->untrue_num;
		if($untrue_num > 0) {
			for ($i=0; $i<$untrue_num; $i++) {
				$user = User::getRandomUntrueUser();
				$msresult = new self();
				$msresult->miaosha_id = $miaosha_id;
				$msresult->user_id = $user->id;
				$key = array_rand($miaoshaGoodsList);
				$msresult->goods_id = $miaoshaGoodsList[$key];
				$msresult->order_id = 1;
				$msresult->save();
				//echo CHtml::errorSummary($msresult);
			}
		}
		return true;
	}

	public static function getSuccessUserTelphone() {
		$startTime = param('miaoshaStartTime');
		$endTime = param('miaoshaEndTime');
		
		$c = new CDbCriteria();
		$c->addCondition('t.order_id > 1');
		$c->addBetweenCondition('t.create_time', $startTime, $endTime);
		$model = self::model()->with('order')->findAll($c);
		
		$phoneArray = array();
		
		if($model) {
			foreach ($model as $v) {
				$phoneArray[] = $v->order->telphone;
			}
		}
		return $phoneArray;
	}
}