<?php

/**
 * This is the model class for table "{{ShopCategory}}".
 *
 * The followings are the available columns in table '{{ShopCategory}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $state
 */
class ShopCategory extends CActiveRecord
{
    const CATEGORY_FOOD = 1;
    const CATEGORY_CAKE = 2;
    const CATEGORY_FLOWER = 3;
    
    public static $categorys = array(
        self::CATEGORY_FOOD => '美食',
        self::CATEGORY_CAKE => '蛋糕',
        self::CATEGORY_FLOWER => '鲜花',
    );
    
    public static $storeNames = array(
        self::CATEGORY_FOOD => '餐馆',
        self::CATEGORY_CAKE => '蛋糕店',
        self::CATEGORY_FLOWER => '鲜花店',
    );
    
    public static $states = array(
    	STATE_DISABLED => '禁用',
    	STATE_ENABLED => '正常'
    );
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShopCategory the static model class
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
		return '{{ShopCategory}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, name', 'required'),
			array('parent_id, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, name, state', 'safe', 'on'=>'search'),
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
			'shops' => array(self::HAS_MANY, 'Shop', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'parent_id' => '父分类',
			'name' => '分类名称',
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

		$criteria->compare('parent_id',$this->parent_id,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider('ShopCategory', array(
			'criteria'=>$criteria,
		));
	}

    public static function getStoreName($cid = 0)
    {
        $data = self::$storeNames[$cid];
        if (empty($data)) $data = '商铺';
        return $data;
    }
    
    public static function getFilterTags($cid)
    {
        if (empty(self::$categorys[$cid])) return null;
        
        $data = array('营业中', '清真');
        
        $categorys = self::model()->findAllByAttributes(array('parent_id' => $cid, 'state'=>STATE_ENABLED));
        foreach ($categorys as $v) $data[] = $v->name;
        return $data;
    }
    
    /**
     *  返回商铺分类id=>name形式的数组 
     */
    public static function getShopCategoryArray()
    {
    	$shopCategory = ShopCategory::model()->findAll();
    	$shopCategoryArray = array();
		foreach ($shopCategory as $row) {
			$shopCategoryArray[$row->id] = $row->name;
		}
		return $shopCategoryArray;
    }
    
	public function getStateText()
	{
		return self::$states[$this->state];
	}
}