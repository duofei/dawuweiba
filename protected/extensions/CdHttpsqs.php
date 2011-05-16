<?php
class CdHttpsqs
{
    const HTTPSQS_PUT_OK = 'HTTPSQS_PUT_OK';
    const HTTPSQS_PUT_ERROR = 'HTTPSQS_PUT_ERROR';
    const HTTPSQS_PUT_END = 'HTTPSQS_PUT_END';
    
    const HTTPSQS_GET_END = 'HTTPSQS_GET_END';
    
    const HTTPSQS_RESET_OK = 'HTTPSQS_RESET_OK';
    const HTTPSQS_RESET_ERROR = 'HTTPSQS_RESET_ERROR';
    
    const HTTPSQS_MAXQUEUE_OK = 'HTTPSQS_MAXQUEUE_OK';
    const HTTPSQS_MAXQUEUE_CANCEL = 'HTTPSQS_MAXQUEUE_CANCEL';
    
    const HTTPSQS_SYNCTIME_OK = 'HTTPSQS_SYNCTIME_OK';
    const HTTPSQS_SYNCTIME_CANCEL = 'HTTPSQS_SYNCTIME_CANCEL';

    const HTTPSQS_AUTH_FAILED = 'HTTPSQS_AUTH_FAILED';
    const HTTPSQS_ERROR = 'HTTPSQS_ERROR';
    
    const HTTPSQS_OPT_PUT = 'put';
    const HTTPSQS_OPT_GET = 'get';
    const HTTPSQS_OPT_VIEW = 'view';
    const HTTPSQS_OPT_STATUS = 'status';
    const HTTPSQS_OPT_STATUS_JSON = 'status_json';
    const HTTPSQS_OPT_RESET = 'reset';
    const HTTPSQS_OPT_MAXQUEUE = 'maxqueue';
    const HTTPSQS_OPT_SYNCTIME = 'synctime';
    
    private $_url = '';
    private $_host = '127.0.0.1';
    private $_port = '1218';
    private $_name;
    private $_opt;
    private $_auth;
    private $_charset = 'utf-8';
    private $_errors = array();
    
    private $_params = array();
    private $_headers = array();
    
    private static $_debug = false;
    
    public function __construct($host, $port, $auth)
    {
        if ($host) $this->_host = $host;
        
        $port = (int) $port;
        if ($port) $this->_port = $port;
        
        if ($auth) $this->_auth = $auth;
    }
    
    public static function debug($debug = true)
    {
        self::$_debug = (bool)$debug;
    }
    
    public function params_revert()
    {
        $this->_params = array();
        $this->_params['auth'] = $this->_auth;
    }
    
    private function build_url()
    {
        if ($this->_host && $this->_port)
            $this->_url = http_build_url(array(
                'scheme' => 'http',
                'host' => $this->_host,
                'port' => $this->_port,
                'path' => '/',
                'query' => http_build_query($this->_params),
            ));
        else
            throw new Exception('$host and $port is not null');
            
        return $this;
    }
    
    public function errors($error = null)
    {
        if (null === $error)
            return $this->_errors;
        elseif ($error)
            $this->_errors[] = $error;
    }
    
    public function put($name, $data)
    {
        if (empty($name) || empty($data))
            throw new Exception('$name and $data is not null');
        
        $query = array(
            'opt' => self::HTTPSQS_OPT_PUT,
            'name' => trim($name),
            'data' => urlencode($data),
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        if ($data['body'] == self::HTTPSQS_PUT_OK)
            return true;
        else {
            $this->errors($data['body']);
            return false;
        }
    }
    
    public function get($name, $pos = false, $charset = null)
    {
        if (empty($name))
            throw new Exception('$name is not null');

        $charset = $charset ? $charset : $this->_charset;
        
        $query = array(
            'opt' => self::HTTPSQS_OPT_GET,
            'name' => trim($name),
            'charset' => $charset,
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
    
        if ($data['body'] != self::HTTPSQS_GET_END)
            if ($pos)
                return array($data['body'], $data['headers']['pos']);
            else
                return $data['body'];
        else {
            $this->errors($data['body']);
            return false;
        }
    }
    
    public function status($name)
    {
        if (empty($name))
            throw new Exception('$name is not null');

        $query = array(
            'opt' => self::HTTPSQS_OPT_STATUS_JSON,
            'name' => trim($name),
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        return $data['body'];
    }

    public function view($name, $pos, $charset = null)
    {
        if (empty($name) || empty($pos))
            throw new Exception('$name and $pos is not null');

        $charset = $charset ? $charset : $this->_charset;
        
        $query = array(
            'opt' => self::HTTPSQS_OPT_VIEW,
            'name' => trim($name),
            'charset' => $charset,
            'pos' => (int)$pos,
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        return $data['body'];
    }

    public function reset($name)
    {
        if (empty($name))
            throw new Exception('$name is not null');

        $query = array(
            'opt' => self::HTTPSQS_OPT_RESET,
            'name' => trim($name),
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        if ($data['body'] == self::HTTPSQS_RESET_OK)
            return true;
        else {
            $this->errors($data['body']);
            return false;
        }
    }
    
    public function maxqueue($name, $num)
    {
        if (empty($name) || empty($num))
            throw new Exception('$name and $num is not null');

        $query = array(
            'opt' => self::HTTPSQS_OPT_MAXQUEUE,
            'name' => trim($name),
            'num' => (int)$num,
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        if ($data['body'] == self::HTTPSQS_MAXQUEUE_OK)
            return true;
        else {
            $this->errors($data['body']);
            return false;
        }
    }
    
    public function synctime($name, $num)
    {
        if (empty($name) || empty($num))
            throw new Exception('$name and $num is not null');

        $query = array(
            'opt' => self::HTTPSQS_OPT_SYNCTIME,
            'name' => trim($name),
            'num' => (int)$pos,
        );
        $this->build_query($query)->build_url();
        $data = $this->sqs_get($this->_url);
        
        if ($data['body'] == self::HTTPSQS_SYNCTIME_OK)
            return true;
        else {
            $this->errors($data['body']);
            return false;
        }
    }
    
    private function build_query(array $params = array())
    {
        $this->params_revert();
        if ($params)
            $this->_params = array_merge($this->_params, $params);
        
        return $this;
    }
    
    private function sqs_get($url)
    {
        if (empty($url))
            throw new Exception('$url is not null');
            
        static $ch;
        
        $ch = $ch ? $ch : curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, '_get_header'));
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data['body'] = curl_exec($ch);
        $data['headers'] = $this->_headers;
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE );
                
        if (self::$_debug) {
            echo $url . '<br />';
            print_r($data);
        }
        
        
        if ($responseCode != 200)
            throw new Exception('httpsqs request error occurred');
            
        if ($data['body'] == self::HTTPSQS_ERROR)
            throw new Exception('An unknown error occurred');
            
        if ($data['body'] == self::HTTPSQS_AUTH_FAILED)
            throw new Exception('httpsqs auth error occurred');
        
        return $data;
    }
    
    private function _get_header($ch, $header)
    {
        $i = strpos($header, ':');
		if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->_headers[$key] = $value;
        }
		return strlen($header);
    }
    
}