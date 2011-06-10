<?php

/**
 * This is the model class for table "{{Tuannav}}".
 *
 * The followings are the available columns in table '{{Tuannav}}':
 * @property string $id
 * @property string $category_id
 * @property string $title
 * @property string $content
 * @property string $url
 * @property string $image
 * @property string $source_id
 * @property float $group_price
 * @property float $discount
 * @property float $original_price
 * @property string $sell_num
 * @property string $effective_time
 * @property string $favorite_num
 * @property string $good_num
 * @property string $buy_num
 * @property string $comment_nums
 * @property integer $city_id
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 */
class Tuannav extends CActiveRecord
{
	public static $states = array(
		STATE_ENABLED => '发布',
		STATE_DISABLED => '不发布'
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tuannav the static model class
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
		return '{{Tuannav}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, title, content, url, image, group_price, discount, original_price, effective_time, city_id, source_id', 'required'),
			array('city_id, create_time, category_id, sell_num, favorite_num, good_num, buy_num, comment_nums, source_id, state', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
			array('url, image', 'length', 'max'=>255),
			array('url, image', 'url'),
			array('discount', 'numerical', 'max'=>9.9, 'min'=>0),
			array('group_price, original_price', 'numerical', 'max'=>999999.99, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_id, title, content, url, image, source_id, group_price, discount, original_price, sell_num, effective_time, favorite_num, good_num, buy_num, comment_nums, city_id, create_time, create_ip, state', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'category' => array(self::BELONGS_TO, 'TuanCategory', 'category_id'),
			'tuandata' => array(self::BELONGS_TO, 'TuanData', 'source_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'category_id' => '分类',
			'title' => '标题',
			'content' => '内容',
			'url' => '连接Url',
			'image' => '图片url',
			'source_id' => '来源',
			'group_price' => '团购价',
			'discount' => '折扣',
			'original_price' => '原价',
			'sell_num' => 'Sell Num',
			'effective_time' => '截至日期',
			'favorite_num' => '收藏次数',
			'good_num' => '顶次数',
			'buy_num' => '购买次数',
			'comment_nums' => '评价次数',
			'city_id' => '城市',
			'create_time' => '添加时间',
			'create_ip' => '添加ip',
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

		$criteria->compare('category_id',$this->category_id,true);

		$criteria->compare('title',$this->title,true);
		
		$criteria->compare('content',$this->content,true);

		$criteria->compare('url',$this->url,true);

		$criteria->compare('image',$this->image,true);

		$criteria->compare('source_id',$this->source_id,true);

		$criteria->compare('group_price',$this->group_price,true);

		$criteria->compare('discount',$this->discount);

		$criteria->compare('original_price',$this->original_price,true);

		$criteria->compare('sell_num',$this->sell_num,true);

		$criteria->compare('effective_time',$this->effective_time,true);

		$criteria->compare('favorite_num',$this->favorite_num,true);
		
		$criteria->compare('good_num',$this->good_num,true);
		
		$criteria->compare('buy_num',$this->buy_num,true);

		$criteria->compare('comment_nums',$this->comment_nums,true);

		$criteria->compare('city_id',$this->city_id);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);
		
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('Tuannav', array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		parent::beforeSave();
		if($this->original_price == $this->group_price) {
			$this->discount = 0;
		} elseif ($this->original_price > 0){
			$this->discount = round($this->group_price/$this->original_price*10, 1);
			if($this->discount == 0) $this->discount = 0.1;
		}
		return true;
	}
	
	/**
	 * 获取当前模型绝对地址的URL
	 */
	public function getAbsoluteUrl()
	{
		return aurl('tuannav/show', array('id' => $this->id));
	}
	
	/**
	 * 获取当前模型相对地址的URL
	 */
	public function getRelativeUrl()
	{
		return url('tuannav/show', array('id' => $this->id));
	}
	
	/**
	 * 获取图片
	 */
	public function getImageHtml()
	{
		return CHtml::image($this->image, $this->title, array('class'=>'image-tuan'));
	}
	
	/**
	 * 获取图片链接
	 */
	public function getImageLinkHtml()
	{
		return l($this->imageHtml, $this->absoluteUrl, array(
			'alt'=>$this->title,
			'title'=>$this->title ,
			'target'=>'_blank'));
	}
	
	/*
	 * 截取标题
	 */
	public function getTitleSub()
	{
		if (strlen($this->title) >= 54) {
			return mb_substr($this->title,'0','20','utf-8').'...';
		}else{
			return $this->title;
		}
	}
	
	public function getCreateTimeText()
	{
	    return date(param('formatDateTime'), $this->create_time);
	}
	
	/**
	 * 取得截至日期
	 */
	public function getEffectiveTime()
	{
		$endtime = $this->getEndTime();
		if($endtime) {
			return $endtime['d'].'天'.$endtime['h'].'小时'.$endtime['i'].'分';
		} else {
			return '已结束';
		}
	}
	
	/**
	 * 截至日期
	 */
	public function getEndTime()
	{
		$effective_time = strtotime($this->effective_time);
		$date = date('Y-m-d H:i:s');
		$now = strtotime($date);
		$surplus_time = $effective_time - $now;
		if ($surplus_time>=0) {
			$d = floor($surplus_time/(24*60*60));
			$h = floor($surplus_time%(24*60*60)/(60*60));
			$i = floor($surplus_time%(24*60*60)%(60*60)/60);
			return array(
				'd' => $d,
				'h' => $h,
				'i' => $i
			);
		} else {
			return false;
		}
	}
	
	/**
	 * 获取团购人气排行
	 */
	public function getTuannavBuyOfCity($cityId)
	{
		$cityId = (int)$cityId;
		$today = date('Y-m-d');
		$condition = new CDbCriteria();
		$condition->addCondition(array('city_id' => $cityId));
	   	$condition->addCondition('effective_time >= \''.$today.'\'');
	    $condition->order = 'buy_num desc, id desc';
	    $condition->limit = '10';
    	$tuanbuy = Tuannav::model()->findAll($condition);
    	return $tuanbuy;
	}
}