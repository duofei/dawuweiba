<?php
class CdRenrenClient extends CdcCurl
{
    public static $api_url = 'http://api.renren.com/restserver.do';
    public static $api_version = 1.0;
    private $api_key;
    private $api_secret;
    private $_args;
    private $_format = 'json';
    private $_data;
    
    public function __construct($api_key, $api_secret)
    {
        if (empty($api_key) || empty($api_secret))
            throw new Exception('$api_key and $api_secret is can not empty.');

        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        
        parent::__construct();
        
        $this->init();
    }
    
    public function init()
    {
        $this->api_key()
            ->callid()
            ->session_key()
            ->version()
            ->format();
        return $this;
    }
    
    private function api_key()
    {
        $this->add_args('api_key', $this->api_key);
        return $this;
    }
    
    public function signature()
    {
        if (!is_array($this->_args)) return null;
    
        ksort($this->_args);
        reset($this->_args);
        foreach ($this->_args as $k => $v)
            $sig .= $k . '=' . $v;
        $sig .= $this->api_secret;
        $this->add_args('sig', md5($sig));
        return $this;
    }
    
    public function callid($id = null)
    {
        if (null === $id)
            $id = microtime(true);
        $this->add_args('call_id', $id);
        return $this;
    }
    
    public function session_key($key = null)
    {
        if (null === $key)
            $key = $_GET['xn_sig_session_key'];
        $this->add_args('session_key', $key);
        return $this;
    }
    
    public function add_args($name, $value)
    {
        if (empty($name)) return $this;
        
        if (is_string($name))
            $this->_args[$name] = $value;
        elseif (is_array($name)) {
            foreach ($name as $k => $v)
                $this->_args[$k] = $v;
        }
        return $this;
    }
    
    public function version($version = null)
    {
        if (null === $version)
            $version = self::$api_version;
        $this->add_args('v', $version);
        return $this;
    }
    
    public function format($format = null)
    {
        if (null === $format)
            $format = $this->_format;
        
        $this->add_args('format', $format);
        return $this;
    }
    
    public function method($method)
    {
        if (null !== $method)
            $this->add_args('method', $method);
        return $this;
    }
    
    public function request()
    {
        if (!array_key_exists('api_key', $this->_args) || !array_key_exists('method', $this->_args))
            throw new Exception('api_key and method is required');
            
        $this->signature();
        $this->_data = $this->post(self::$api_url, $this->_args)->rawdata();
        return $this;
    }
    
    public function data()
    {
        $method = strtolower($this->_format);
        if (method_exists($this, $method))
            return $this->$method();
        else
            throw new Exception('指定的format不支持');
    }
    
    public function xml()
    {
        return $this->_data;
    }
    
    public function json()
    {
        return json_decode($this->_data, true);
    }
    
    public function crevert()
    {
        parent::revert();
        
        $this->_args = array();
        $this->init();
        return $this;
    }
    
}