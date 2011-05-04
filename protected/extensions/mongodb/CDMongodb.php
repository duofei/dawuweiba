<?php
class CDMongodb extends CApplicationComponent
{
    public $connectionString;
    public $dbname;
    public $options;
    public $collectionPrefix;
    private $_active = false;
    private $_conn;
    private $_db;
    private $_collection;
    
    public function __construct($dsn = '')
    {
        $this->connectionString = $dsn;
    }
    
    public function init()
    {
        parent::init();
    }
    
    public function initConnection()
    {
        $this->_conn = new Mongo($this->connectionString, $this->options);
        $this->setDb($this->dbname);
    }
    
    public function getConnection()
    {
        if (!$this->_active)
            $this->setActive(true);
        return $this->_conn;
    }
    
    public function open()
    {
        if(empty($this->connectionString))
			throw new CDbException(Yii::t('yii', 'CDMdbConnection.connectionString cannot be empty.'));
		try {
			Yii::trace('Opening DB connection', 'system.db.CDbConnection');
			$this->initConnection();
			$this->_active = true;
		} catch(MongoException $e) {
			if(YII_DEBUG) {
				throw new MongoException(Yii::t('yii','CDbConnection failed to open the DB connection: {error}',
					array('{error}'=>$e->getMessage())),(int)$e->getCode(),$e->errorInfo);
			} else {
				Yii::log($e->getMessage(),CLogger::LEVEL_ERROR,'exception.CDbException');
				throw new MongoException(Yii::t('yii','CDbConnection failed to open the DB connection.'),(int)$e->getCode(),$e->errorInfo);
			}
		}
    }
    
    public function close()
    {
        $this->_conn->close();
    }
    
    public function setActive($value)
    {
        if($value != $this->_active) {
			if($value)
				$this->open();
			else
				$this->close();
		}
    }
    
    public function getActive()
    {
        return $this->_active;
    }
    
    public function setDb($dbname)
    {
        $this->_db = $this->_conn->selectDb($dbname);
        return $this->_db;
    }
    public function getDb()
    {
        return $this->_db;
    }
    
    public function setCollection($collection)
    {
        $this->_collection = $this->_db->{$collection};
        return $this->_collection;
    }
    public function getCollection()
    {
        return $this->_collection;
    }
}