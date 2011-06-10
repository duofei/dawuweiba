<?php

/**
 * This is the model class for table "{{Voucher}}".
 *
 * The followings are the available columns in table '{{Voucher}}':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $goods_id
 * @property float $price
 * @property string $img
 * @property integer $end_time
 * @property integer $create_time
 * @property string $create_ip
 */
class Voucher extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Voucher the static model class
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
		return '{{Voucher}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, goods_id, end_time, price', 'required'),
			array('shop_id, goods_id, end_time, create_time', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical', 'max'=>99999.99, 'min'=>0),
			array('img', 'length', 'max'=>255),
			array('create_ip', 'length', 'max'=>15, 'min'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, goods_id, price, img, end_time, create_time, create_ip', 'safe', 'on'=>'search'),
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
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
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
			'goods_id' => 'Goods',
			'price' => 'Price',
			'img' => 'Img',
			'end_time' => 'End Time',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('goods_id',$this->goods_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_ip',$this->create_ip,true);
		return new CActiveDataProvider('Voucher', array(
			'criteria'=>$criteria,
		));
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

	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord) {
			$shop = Shop::model()->findByPk($this->shop_id);
			if($shop) {
				$shop->is_voucher = STATE_ENABLED;
				$shop->update();
			}
	    }
	    return true;
	}
}