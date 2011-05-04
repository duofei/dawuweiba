<?php
/**
 * 人人网应用接口
 * @author chendong@52wm.com
 *
 */
class RenrenApp
{
    // 我爱外卖网 应用API_Key
    private $_apiKey;
    // 我爱外卖网 应用 SECERET
    private $_apiSecret;
    // session_key
    private $_sessionKey;
    // session_secret
    private $_sessionSecret;
    // session_expires
    private $_expires;
    // session_user
    private $_userId;
    private $_user;
    
    // api版本
    private $_version = '1.0';
    private $_serverAddr = 'http://api.renren.com/restserver.do?';
    
    public static function getConnectBtnHtml()
    {
        return '<xn:login-button autologoutlink="true" onclick="onRenRenLogin();"></xn:login-button>';
    }
    
    public function __construct($apiKey, $apiSecret, $version = '1.0')
    {
        $this->_apiKey = $apiKey;
        $this->_apiSecret = $apiSecret;
        $this->_sessionKey = $this->getSessionKey();
        $this->_sessionSecret = $this->getSessionSecret();
        $this->_expires = $this->getSessionExpires();
        $this->_userId = $this->getSessionUserId();
        $this->_version = $version;
    }
    
    public function getSessionKey()
    {
        $name = $this->_apiKey . '_session_key';
        return $this->_sessionKey = $_COOKIE[$name];
    }
    
    public function getSessionSecret()
    {
        $name = $this->_apiKey . '_ss';
        return $this->_sessionSecret = $_COOKIE[$name];
    }
    
    public function getSessionExpires()
    {
        $name = $this->_apiKey . '_expires';
        return $this->_expires = $_COOKIE[$name];
    }
    
    public function getSessionUserId()
    {
        $name = $this->_apiKey . '_user';
        return $this->_userId = $_COOKIE[$name];
    }
    

    public function generateSig($params)
    {
        ksort($params);
        foreach($params as $key=>$value) {
            $sig .= "$key=$value";
        }
        $sig .= $this->_apiSecret;
        return md5($sig);
    }
    
    function generateBody($method, $params, $format = 'json')
    {
        $params['method'] = $method;
        if(!$params['uids']) {
            $params['session_key'] = $this->getSessionKey();
        }
        $params['api_key'] = $this->_apiKey;
        $params['call_id'] = $_SERVER['REQUEST_TIME'];
        $params['format'] = $format;
        
        if (!isset($params['v'])) {
          $params['v'] = $this->_version;
        }
        
        $postParams = array();
        foreach ($params as $key => &$val) {
           if (is_array($val)) $val = implode(',', $val);
           $postParams[] = $key . '=' . urlencode($val);
        }
        $postParams[] = 'sig=' . $this->generateSig($params);
        $data = implode('&', $postParams);
        return $data;
    }
    
    public function postRequest($method, $params)
    {
        $postBody = $this->generateBody($method, $params);
        
        if (function_exists('curl_init')) {
            $request = curl_init();
            curl_setopt($request, CURLOPT_URL, $this->_serverAddr);
            curl_setopt($request, CURLOPT_POST, 1);
            curl_setopt($request, CURLOPT_POSTFIELDS, $postBody);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($request);
            curl_close($request);
        } else {
            $context =array(
    			'http' => array(
    				'method' => 'POST',
    				'header' => 'Content-type: application/x-www-form-urlencoded'."\r\n".
    					'User-Agent: Facebook API PHP5 Client 1.1 '."\r\n".
    					'Content-length: ' . strlen($postBody),
    					'content' => $postBody
                    )
            );
            $contextid=stream_context_create($context);
            $sock=fopen(RR_API_SERVER, 'r', false, $contextid);
            if ($sock) {
                while (!feof($sock)) $result .= fgets($sock, 4096);
                fclose($sock);
            }
        }
        $data = json_decode($result, true);
        return $data[0];
    }
    
    public function getUserInfo($uids = 0)
    {
        if($uids)
            $params = array("uids"=>$uids);
        else
            $params = array("fields"=> array('name','sex','star','birthday','tinyurl','headurl','tinyurl_with_logo','headurl_with_logo','mainurl','university_history','work_history','hs_history','hometown_location'));
        
        $this->_user = $this->postRequest('users.getInfo', $params);
        return $this->_user;
    }
    
    public function create52WmId($user = null)
    {
        $user = (null === $user) ? $this->_user : $user;
        if (empty($user))
            throw new Exception('$user 不能为空', 0);
            
        $newUsername = $user['name'] . $user['uid'];
        $wmUser = User::model()->findByAttributes(array('username'=>$newUsername));
        if ($wmUser) return $wmUser;
        
        $wmUser = new User();
        $wmUser->username = $newUsername;
        $wmUser->password = $wmUser->clear_password = $wmUser->source_uid = $user['uid'];
        $wmUser->source = User::SOURCE_RENREN;
        $wmUser->realname = $user['name'];
        $wmUser->gender = $user['sex'];
        $wmUser->birthday = $user['birthday'];
        $wmUser->save();
        return $wmUser;
    }
    
    public function clearCookie($name = null)
    {
        if (null !== $name) {
            $cookieName = $this->_apiKey . '_' . $name;
            return setcookie($cookieName, null, null, param('cookie_path'), param('cookie_domain'));
        }
            
        $cookieApiKey = setcookie($this->_apiKey, null, null, param('cookie_path'), param('cookie_domain')); 
        $cookieExpires = setcookie($this->_apiKey. '_expires', null, null, param('cookie_path'), param('cookie_domain'));
        $cookieSessionKey = setcookie($this->_apiKey . '_session_key', null, null, param('cookie_path'), param('cookie_domain'));
        $cookieSS = setcookie($this->_apiKey . '_ss', null, null, param('cookie_path'), param('cookie_domain'));
        $cookieUser = setcookie($this->_apiKey . '_user', null, null, param('cookie_path'), param('cookie_domain'));
        
        return $cookieApiKey && $cookieExpires && $cookieSessionKey && $cookieSS && $cookieUser;
    }
}
