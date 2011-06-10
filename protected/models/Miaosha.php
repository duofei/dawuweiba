<?php

/**
 * This is the model class for table "{{Miaosha}}".
 *
 * The followings are the available columns in table '{{Miaosha}}':
 * @property integer $id
 * @property integer $shop_id
 * @property string $desc
 * @property integer $active_time
 * @property integer $active_num
 * @property integer $untrue_num
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $state
 */
class Miaosha extends CActiveRecord
{
	const STATE_CLOSE = 0;
	const STATE_OPEN = 1;
	const STATE_OVER = 2;
	public static $states = array(
		self::STATE_CLOSE => '关闭',
		self::STATE_OPEN => '开启',
		self::STATE_OVER => '结束'
	);
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Miaosha the static model class
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
		return '{{Miaosha}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, active_time, active_num', 'required'),
			array('state, shop_id, active_time, untrue_num, active_num, create_time', 'numerical', 'integerOnly'=>true),
			array('desc', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, desc, active_time, active_num, untrue_num, create_ip, create_time, state', 'safe', 'on'=>'search'),
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
			'miaoshaGoods' => array(self::HAS_MANY, 'MiaoshaGoods', 'miaosha_id'),
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
			'shop_id' => 'Shop',
			'desc' => 'Desc',
			'active_time' => 'Active Time',
			'active_num' => 'Active Num',
			'untrue_num' => 'Untrue Num',
			'create_ip' => 'Create Ip',
			'create_time' => 'Create Time',
			'state' => 'State',
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('desc',$this->desc,true);

		$criteria->compare('active_time',$this->active_time,true);

		$criteria->compare('active_num',$this->active_num,true);
		
		$criteria->compare('untrue_num',$this->untrue_num,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('Miaosha', array(
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
	
	public function getActiveTimeWeek()
	{
		$week = array(
			'0' => '星期天',
			'1' => '星期一',
			'2' => '星期二',
			'3' => '星期三',
			'4' => '星期四',
			'5' => '星期五',
			'6' => '星期六',
		);
		return $week[date('w', $this->active_time)];
	}
}