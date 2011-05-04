<?php

/**
 * This is the model class for table "{{UserShopFavorite}}".
 *
 * The followings are the available columns in table '{{UserShopFavorite}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $shop_id
 * @property string $shop_name
 * @property integer $create_time
 * @property string $create_ip
 */
class UserShopFavorite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserShopFavorite the static model class
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
		return '{{UserShopFavorite}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, shop_id, shop_name', 'required'),
			array('user_id, shop_id, create_time', 'numerical', 'integerOnly'=>true),
			array('shop_name', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, shop_id, shop_name, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
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
			'id' => 'Id',
			'user_id' => '用户',
			'shop_id' => '商铺',
			'shop_name' => '商铺名称',
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('shop_name',$this->shop_name,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		return new CActiveDataProvider('UserShopFavorite', array(
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
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
	    }
	    return true;
	}
	protected function afterDelete()
	{
		parent::afterDelete();
	    $counters = array('favorite_nums' => -1);
	    Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
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
	 * 获取商铺名称链接
	 */
	public function getNameLinkHtml()
	{
		return l($this->shop_name, url('shop/show', array('shopid' => $this->shop_id)), array('target'=>'_blank', 'class'=>'shop-name'));
	}
	
}