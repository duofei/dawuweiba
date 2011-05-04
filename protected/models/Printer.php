<?php

/**
 * This is the model class for table "{{Printer}}".
 *
 * The followings are the available columns in table '{{Printer}}':
 * @property integer $id
 * @property integer $city_id
 * @property string $code
 * @property string $phone
 * @property string $remark
 * @property integer $last_time
 * @property integer $status
 */
class Printer extends CActiveRecord
{
    const STATE_OFFLINE_TIMEOUT = 240;
    const STATE_ERROR_TIMEOUT = 86400;
    
    const STATE_ONLINE = 1;
    const STATE_OFFLINE = 2;
    const STATE_ERROR = 3;
    const STATE_PRINTER_PAUSE = 0;
    const STATE_PRINTER_CONTINUE = 1;
    
    public static $states = array(
        self::STATE_ONLINE => '在线',
        self::STATE_OFFLINE => '不在线',
        self::STATE_ERROR => '故障',
    );
    
    public static $orderStates = array(
        self::STATE_PRINTER_PAUSE => '暂停',
        self::STATE_PRINTER_CONTINUE => '继续',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Printer the static model class
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
		return '{{Printer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('code, phone', 'required'),
		    array('code, phone', 'unique'),
			array('city_id, last_time, status', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>10),
			array('phone', 'length', 'max'=>15),
			array('remark', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, city_id, code, phone, remark, last_time, status', 'safe', 'on'=>'search'),
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
		    'shop' => array(self::HAS_ONE, 'Shop', 'printer_no'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => '城市',
			'code' => '编号',
			'phone' => '手机号',
			'remark' => '备注',
			'last_time' => 'LastTime',
			'status' => 'status',
		);
	}
	
	/**
	 * 获取打印机整体状态
	 * 打印机状态为“继续下载”，并且当前状态为在线，则认为打印机为在线，否则不在线
	 * @return boolean 打印机整体状态
	 */
	public function getState()
	{
	    return $this->getOrderState() && ($this->getPrinterState() == self::STATE_ONLINE);
	}
	
	/**
	 * 打印机的当前状态
	 * 模糊判断，默认3分钟内发过包的即为在线，24小时不在线的为故障
	 */
	public function getPrinterState()
	{
	    $t = $_SERVER['REQUEST_TIME'];
	    
	    if (($t - $this->last_time) > self::STATE_ERROR_TIMEOUT)
	        return self::STATE_ERROR;
	    elseif (($t - $this->last_time) > self::STATE_OFFLINE_TIMEOUT)
	        return self::STATE_OFFLINE;
	    else
	        return self::STATE_ONLINE;
	}
	
	/**
	 * 打印机的状态
	 * 对应打印机表的status字段
	 */
	public function getOrderState()
	{
	    if ($this->status == self::STATE_PRINTER_CONTINUE)
	        return self::STATE_PRINTER_CONTINUE;
	    else
	        return self::STATE_PRINTER_PAUSE;
	}
	
	/**
	 * 打印机的状态html
	 * 对应打印机表的status字段
	 */
	public function getOrderStateHtml()
	{
	    $class = array(
	        self::STATE_PRINTER_PAUSE => 'cgray',
	        self::STATE_PRINTER_CONTINUE => 'cgreen',
	    );
	    $class = $class[$this->orderState];
	    $text = self::$orderStates[$this->orderState];
	    return '<span class="' . $class . '">' . $text . '</span>';
	}
	
	/**
	 * 打印机的
	 */
	
	
	public function getStateHtml()
	{
	    $class = array(
	        self::STATE_ONLINE => 'cgreen',
	        self::STATE_OFFLINE => 'cgray',
	        self::STATE_ERROR => 'cred',
	    );
	    $class = $class[$this->printerState];
	    $text = self::$states[$this->printerState];
	    return '<span class="' . $class . '">' . $text . '</span>';
	}
	
	public function getLastTimeText()
	{
	    return date(param('formatShortDateTime'), $this->last_time);
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
		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('code',$this->code,true);

		$criteria->compare('phone',$this->phone,true);

		$criteria->compare('remark',$this->remark,true);
		
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider('Printer', array(
			'criteria'=>$criteria,
		));
	}
}


