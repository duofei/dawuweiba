<?php
class CRedisCache extends CCache
{
    public $host;
    public $port = 6379;
    public $password;
    
    private $_cache;
    
	public function init()
	{
		parent::init();
		if(!extension_loaded('redis'))
			throw new CException(Yii::t('yii','CRedisCache requires PHP redis extension to be loaded.'));
	    
		if (null === $this->host)
		    throw new CException(Yii::t('yii','CRedisCache server configuration must have "host" value.'));
		    
		$this->getRedisCache();
		$this->_cache->connect($this->host, $this->port);
	}

    public function getRedisCache()
    {
        if (null !== $this->_cache)
            return $this->_cache;
        else
            return $this->_cache = new Redis();
    }
	protected function getValue($key)
	{
		return $this->_cache->get($key);
	}

	protected function getValues($keys)
	{
		return array_combine($keys, $this->_cache->getMultiple($keys));
	}


	protected function setValue($key,$value,$expire)
	{
		if ($expire)
		    return $this->_cache->setex($key, $expire, $value);
		else
		    return $this->_cache->set($key, $value);
	}


	protected function addValue($key,$value,$expire)
	{
	    if ($expire)
		    return $this->_cache->setex($key, $expire, $value);
		else
		    return $this->_cache->setnx($key, $value);
	}

	protected function deleteValue($key)
	{
		return $this->_cache->delete($key);
	}
}
