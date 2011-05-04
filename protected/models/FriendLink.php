<?php
/**
 * The followings are the available columns in table '{{FriendLink}}':
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property string $homepage
 * @property string $logo
 * @property string $desc
 * @property integer $order_id
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $isvalid
 */
class FriendLink extends CActiveRecord
{
	public $validateCode;
    const STYLE_TEXT = 1;
    const STYLE_IMAGE = 2;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return '{{FriendLink}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, order_id, isvalid, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('homepage, desc, logo', 'length', 'max'=>255),
			array('homepage, logo', 'url'),
			array('name, homepage', 'required'),
			array('name, homepage', 'unique'),
			array('validateCode', 'captcha', 'allowEmpty'=>false, 'on'=>'newlink'),
			array('create_ip, update_ip', 'length', 'min'=>7, 'max'=>15),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'city_id' => '城市',
			'homepage' => '网址',
			'logo' => 'LOGO',
			'desc' => '描述',
			'order_id' => '排序',
			'isvalid' => '有效',
		    'create_time' => '创建时间',
		    'create_ip' => '创建IP',
		    'update_time' => '更新时间',
		    'update_ip' => '更新IP',
		);
	}
	
	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	        ),
	    );
	} 
	
	public function getCreateDateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}
	
	public function getUpdateDateTimeText()
	{
	    return date(param('formatDateTime'), $this->update_time);
	}
	
    public static function getFriendLinks($cityId=0)
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('isvalid' => STATE_ENABLED));
        $criteria->order = 'order_id desc, id asc';
        $data = self::model()->findAll($criteria);
        return $data;
    }
}