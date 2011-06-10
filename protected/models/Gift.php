<?php

/**
 * This is the model class for table "{{Gift}}".
 *
 * The followings are the available columns in table '{{Gift}}':
 * @property integer $id
 * @property string $name
 * @property string $small_pic
 * @property string $content
 * @property integer $integral
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $state
 */
class Gift extends CActiveRecord
{
	/**
	 * 礼品状态
	 */
	public static $states = array(
    	STATE_ENABLED => '有货',
        STATE_DISABLED => '售完',
    );
	
    public static $sortIntegral = array(5000, 10000, 15000, 20000, 99999999);
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Gift the static model class
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
		return '{{Gift}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, small_pic, content, integral', 'required'),
			array('state, integral, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('name, small_pic', 'length', 'max'=>255),
			array('create_ip, update_ip', 'length', 'max'=>15, 'min'=>7),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, small_pic, content, integral, create_time, create_ip, update_time, update_ip, state', 'safe', 'on'=>'search'),
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
			'giftExchangeLogs' => array(self::HAS_MANY, 'GiftExchangeLog', 'gift_id'),
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
			'name' => '名称',
			'small_pic' => '缩略图片',
			'content' => '内容',
			'integral' => '积分',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'update_time' => '修改时间',
			'update_ip' => '修改IP',
			'state' => '状态',
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

		$criteria->compare('name',$this->name,true);

		$criteria->compare('small_pic',$this->small_pic,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('integral',$this->integral,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('Gift', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('gift/show', array('giftid' => $this->id));
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('gift/show', array('giftid' => $this->id));
	}

	public function getSmallPic()
	{
		return sbu($this->small_pic);
	}
	
	/**
	 * 获取礼品缩略图
	 */
	public function getSmallPicHtml()
	{
		return CHtml::image($this->smallPic, $this->name, array('class'=>'gift-pic', 'title'=>$this->name));
	}
	
	/**
	 * 获取礼品缩略图链接
	 */
	public function getPicLinkHtml()
	{
		return l($this->smallPicHtml, $this->absoluteUrl, array('title'=>$this->name, 'class'=>'gift-pic-link'));
	}
	
	/**
	 * 获取礼品名称链接
	 */
	public function getNameLinkHtml($target = '_self')
	{
		return l($this->name, $this->absoluteUrl, array('class'=>'gift-name', 'target'=>$target));
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
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getUpdateDateTimeText()
	{
		return date(param('formatDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortUpdateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d
	 */
	public function getUpdateDateText()
	{
		return date(param('formatDate'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i:s
	 */
	public function getUpdateTimeText()
	{
		return date(param('formatTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：H:i
	 */
	public function getShortUpdateTimeText()
	{
		return date(param('formatShortTime'), $this->update_time);
	}
	
	public static function getSortGiftList()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('state'=>STATE_ENABLED));
	    $criteria->order = 'integral asc';
	    $gifts = self::model()->findAll($criteria);
	    $data = array();
	    foreach ($gifts as $v) {
	        foreach (self::$sortIntegral as $d) {
	            if ($v->integral <= $d) {
	                $data[$d][] = $v;
	                break;
	            }
	        }
	    }
	    
	    return $data;
	}
	
	/**
	 * 获取礼品状态
	 */
	public function getStatusText()
	{
		return self::$states[$this->state];
	}
}