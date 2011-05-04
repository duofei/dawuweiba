<?php
class CdCurl
{
    private $_ch;
    private $_headers;
    private $_http_code;
    private $_http_info;
    private $_options;
    private $_data;
    private $_timeout = 30;
    private $_connection_timeout = 60;
    private static $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:2.0) Gecko/20100101 Firefox/4.0';

    public function __construct()
    {
        if (!extension_loaded('curl'))
            throw new Exception('curl extension is not loaded');
            
        $this->_option_init();
    }
    
    private function _option_init()
    {
        $this->add_option(CURLOPT_USERAGENT, self::$user_agent);
        $this->add_option(CURLOPT_CONNECTTIMEOUT, $this->_connection_timeout);
        $this->add_option(CURLOPT_TIMEOUT, $this->_timeout);
        $this->add_option(CURLOPT_RETURNTRANSFER, true);
        $this->add_option(CURLOPT_HEADERFUNCTION, array($this, '_get_header'));
        $this->add_option(CURLOPT_HEADER, false);
    }
    
    public final function add_option($option, $value)
    {
        if ($option)
            $this->_options[$option] = $value;

        return $this;
    }
    
    public final function del_option($option = null)
    {
        if (null === $option)
            $this->_options = array();
        elseif (array_key_exists($option, $this->_options))
            unset($this->_options[$option]);
            
        return $this;
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
    
    public final function http_headers()
    {
        return $this->_headers;
    }
    
    public final function http_code()
    {
        if ($this->_http_code)
            return $this->_http_code;
            
        return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);;
    }
    
    public final function http_info()
    {
        if ($this->_http_info)
            return $this->_http_info;
        return curl_getinfo($this->_ch);
    }
    
    public final function basic_auth($username, $password)
    {
         if ($username != null) {
             curl_setopt($this->_ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
             curl_setopt($this->_ch, CURLOPT_USERPWD, "{$username}:{$password}");
         }
         return $this;
     }
    
    
    public final function referer($url = true)
    {
        if (is_string($url) && !empty($url)) {
            $this->add_option(CURLOPT_AUTOREFERER, false);
            $this->add_option(CURLOPT_REFERER, $url);
        }
        elseif (is_bool($url))
            $this->add_option(CURLOPT_AUTOREFERER, $url);
        
        return $this;
    }
    
    public final function revert()
    {
        $this->del_option();
        $this->_option_init();
        $this->_headers = $this->_http_code = $this->_http_info = $this->_data = null;
        return $this;
    }
     
    private function exec($url)
    {
        $this->add_option(CURLOPT_URL, $url);
        $this->_ch = curl_init();
        curl_setopt_array($this->_ch, $this->_options);
        $this->_data = curl_exec($this->_ch);
        
        return $this;
    }
    
    public function rawdata()
    {
        return $this->_data;
    }
    
    public final function errno()
    {
        return curl_errno($this->_ch);
    }
    
    public final function error()
    {
        return curl_error($this->_ch);
    }
    
    public final function post($url, $data = null)
    {
        $this->add_option(CURLOPT_POST, 'POST');
        if (null !== $data)
            $this->add_option(CURLOPT_POSTFIELDS, $data);
        return $this->exec($url);
    }
    
    public final function delete($url, $data = null)
    {
        $this->add_option(CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->exec($url);
    }
    
    public final function put($url, $data = null)
    {
        $this->add_option(CURLOPT_PUT, true);
        if (null !== $data) {
            $file = tmpfile();
            fwrite($file, $data);
            fseek($file, 0);
            $this->add_option(CURLOPT_INFILE, $file);
            $this->add_option(CURLOPT_INFILESIZE, strlen($data));
        }
        return $this->exec($url);
    }
    
    public final function get($url, $data = null)
    {
        $url = self::build_url($url, $data);
        return $this->exec($url);
    }
    
    private static final function build_url($url, $args)
    {
        if (empty($args)) return $url;
        
        $query = http_build_query($args);
        $info = parse_url($url);
        $join = $info['query'] ? '&' : '?';
        $url .= $join . $query;
        return $url;
    }
    
    public final function close()
    {
        curl_close($this->_ch);
    }
    
    public final function ssl($peer = false, $host = 2)
    {
        $this->add_option(CURLOPT_SSL_VERIFYHOST, $host);
        $this->add_option(CURLOPT_SSL_VERIFYPEER, $peer);
        return $this;
    }
    
    public final function follow($val = true)
    {
        $this->add_option(CURLOPT_AUTOREFERER, $val);
        $this->add_option(CURLOPT_FOLLOWLOCATION, $val);
        return $this;
    }

    
    public final function get_ch()
    {
        return $this->_ch;
    }
    
}