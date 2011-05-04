<?php

/**
 * This is the model class for table "{{Promotion}}".
 *
 * The followings are the available columns in table '{{Promotion}}':
 * @property integer $id
 * @property integer $shop_id
 * @property string $content
 * @property integer $end_time
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $recommend
 */
class Promotion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Promotion the static model class
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
		return '{{Promotion}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, content, end_time', 'required'),
			array('shop_id, end_time, create_time, recommend', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, content, end_time, create_time, create_ip, recommend', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			$counters = array('coupon_nums' => 1);
	       	Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
		}
		return true;
	}
	protected function afterDelete()
	{
		parent::afterDelete();
		$counters = array('coupon_nums' => -1);
		Shop::model()->updateCounters($counters, 'id = ' . $this->shop_id);
		return true;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shop_id' => '商铺',
			'content' => '内容',
			'end_time' => '结束时间',
			'create_time' => '发布时间',
			'create_ip' => '发布IP',
			'recommend' => '推荐',
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

		$criteria->compare('shop_id',$this->shop_id,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('end_time',$this->end_time,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('recommend',$this->recommend,true);

		return new CActiveDataProvider('Promotion', array(
			'criteria'=>$criteria,
		));
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
	 * 格式化截止日期
	 * 输出时间格式：Y-m-d
	 */
	public function getEndDateText()
	{
		return date(param('formatDate'), $this->end_time);
	}
	
	public function getShowUrl()
	{
	    return url('promotion/index', array('pid'=>$this->id), $this->id);
	}
	
	/**
	 * 截取优惠信息
	 * @param integer $len 截取长度，使用mb_string库
	 * @return string
	 */
	public function getShortContent($len = 0)
	{
	    if (0 == $len) return $this->content;
	    return mb_strimwidth($this->content, 0, $len, '..');
	}
	
	/**
	 * 组合商家名称与优惠信息在一块
	 * @param integer $len1 商家名字截取长度
	 * @param integer $len2 优惠信息截取长度
	 * @return string 组合之后的html
	 */
	public function getShopNameGroupText($len1 = 10, $len2 = 18)
	{
	    $html = l('[' . $this->shop->getShortName($len1) . ']', $this->shop->relativeUrl, array('title'=>$this->shop->shop_name)) . '&nbsp;';
	    $html .= '<span class="cgray">' . l($this->getShortContent($len2), $this->showUrl, array('class'=>'cblack', 'title'=>$this->content)) . '</span>';
	    return $html;
	}
	
	public static function getPromotionFromShopIds(array $ids, $count = 6)
	{
	    /*
	     * 获取优惠信息
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addInCondition('shop_id', $ids);
	    $criteria->limit = (int)$count;
	    $promotions = Promotion::model()->with('shop')->findAll($criteria);
	    return $promotions;
	}

}