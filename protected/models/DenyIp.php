<?php

/**
 * This is the model class for table "{{DenyIp}}".
 *
 * The followings are the available columns in table '{{DenyIp}}':
 * @property integer $id
 * @property integer $ip_start
 * @property integer $ip_end
 * @property string $user_id
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $type
 */
class DenyIp extends CActiveRecord
{
    /*
     * 禁止发表内容
     */
    const TYPE_POST = 0;
    
    /*
     * 禁止访问
     */
    const TYPE_ACCESS = 1;
    
    public static $types = array(
        self::TYPE_POST => '禁止发言',
        self::TYPE_ACCESS => '禁止访问',
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return DenyIp the static model class
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
		return '{{DenyIp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip_start, ip_end', 'required'),
			array('ip_start', 'unique'),
			array('user_id', 'length', 'max'=>10),
			array('id, ip_start, ip_end, user_id, create_time, type', 'numerical', 'integerOnly'=>true),
			array('user_id', 'exist', 'className'=>'User', 'attributeName'=>'id', 'message'=>'该用户不存在'),
			array('ip_start', 'compare', 'compareAttribute'=>'ip_end', 'operator'=>'<=', 'message'=>'结束IP必须要大于或等于起始IP'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip_start, ip_end, user_id, create_time, create_ip, type', 'safe', 'on'=>'search'),
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
		);
	}

		public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	            'updateAttribute' => null,
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	            'updateAttribute' => null,
	        ),
	    );
	} 
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ip_start' => '起始IP',
		    'ip_end' => '结束IP',
			'user_id' => '用户',
		    'create_time' => '禁用时间',
		    'create_ip' => '操作IP',
		    'type' => '禁用类型',
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

		$criteria->compare('ip',$this->ip,true);

		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider('DenyIp', array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCreateDateTimeText()
	{
	    if (empty($this->create_time)) return null;
	    return date(param('formatDateTime'), $this->create_time);
	}
	
	public function getStartIp()
	{
	    if (empty($this->ip_start)) return null;
	    return long2ip($this->ip_start);
	}
	
    public function getEndIp()
	{
	    if (empty($this->ip_end)) return null;
	    return long2ip($this->ip_start);
	}
	
	protected function beforeValidate()
	{
		parent::beforeValidate();
	    if ($this->ip_start != (string)(int)$this->ip_start) $this->ip_start = ip2long($this->ip_start);
	    if ($this->ip_end != (string)(int)$this->ip_end) $this->ip_end = ip2long($this->ip_end);
	    return true;
	}
	
	public function getTypeText()
	{
	    return self::$types[$this->type];
	}
	
	/**
	 * 检查ip状态
	 * @param string $ip
	 * @return mixed 如果该ip没有被禁用，则返回true，否则返回type字段值，即禁用类型，参见TYPE_POST和TYPE_ACCESS常量
	 */
	public static function checkIpState($ip)
	{
	    $ip = ip2long($ip);
	    $criteria = new CDbCriteria();
	    $criteria->addCondition("ip_start <= $ip and ip_end >= $ip");
	    $row = self::model()->find($criteria);
	    return (null === $row) ? true : (int)$row->type;
	}
	
    public static function CheckPostIpState($model = null)
    {
    	$ip = CdcBetaTools::getClientIp();
		if(self::TYPE_POST === self::checkIpState($ip)) {
			if (null === $model)
				return false;
			else 
				$model->addError('ip', '您的IP已经被禁用');
		}
		return true;
    }
}