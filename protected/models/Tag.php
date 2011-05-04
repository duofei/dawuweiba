<?php

/**
 * This is the model class for table "{{Tag}}".
 *
 * The followings are the available columns in table '{{Tag}}':
 * @property integer $id
 * @property string $name
 */
class Tag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tag the static model class
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
		return '{{Tag}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'Goods' => array(self::MANY_MANY, 'Goods', '{{GoodsTag}}(goods_id, tag_id)'),
			'Shops' => array(self::MANY_MANY, 'Shop', '{{ShopTag}}(shop_id, tag_id)'),
			'Users' => array(self::MANY_MANY, 'User', '{{UserTag}}(user_id, tag_id)'),
			'Locations' => array(self::MANY_MANY, 'Location', '{{LocationTag}}(location_id, tag_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => '标签名称',
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

		return new CActiveDataProvider('Tag', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取tagid
	 * @param array $tagArray tag名称的数组
	 * @return array tag对应的tagid的数组
	 */
	public static function getTagId($tagArray) {
		$array = array();
		$criteria = new CDbCriteria();
		$criteria->addInCondition('name', $tagArray);
		$tags = self::model()->findAll($criteria);
		if($tags) {
			foreach($tags as $tag) {
				$array[$tag->name] = $tag->id;
				$temp[] = $tag->name;
			}
		}
		if($temp) {
			$diffArray = array_diff($tagArray, $temp);
		} else {
			$diffArray = $tagArray;
		}
		if($diffArray) {
			foreach($diffArray as $v) {
				$tag = new Tag();
				$tag->name = $v;
				$tag->save();
				$array[$tag->name] = $tag->id;
			}
		}
		return $array;
	}
	
	/**
	 * 往shopTag里添加记录
	 * @param array $tagArray tag名称的数组
	 * @return boolean
	 */
	public static function addShopTag($shopid, $tagArray) {
		if($tagArray) {
			$tagids = self::getTagId($tagArray);
			$sql = "insert into wm_ShopTag values ";
			$dot = '';
			foreach ($tagids as $tagid) {
				$sql .=  $dot . "($shopid, $tagid)";
				$dot = ',';
			}
			$command = app()->db->createCommand($sql);
			$command->execute();
		}
		return true;
	}
}