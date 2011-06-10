<?php
/**
 * 新浪微博应用接口
 * @author chendong@52wm.com
 *
 */

/**
 * @see SinaTOAuth.php
 */
require 'SinaTOAuth.php';

class SinaTApp
{
    // 我爱外卖网 应用API_Key
    private $_apiKey;
    // 我爱外卖网 应用 SECERET
    private $_apiSecert;
    private $_callback;
    private $_oauth;
    public $client;
    private $_requestToken;
    private $_requestTokenSecret;
    private $_accessToken;
    private $_accessTokenSecret;
    private $_user;
    private $_userId;
    
    private static $_genders = array(
        'f' => User::GENDER_FEMALE,
        'm' => User::GENDER_MALE,
    );
    
    public function getConnectUrl()
    {
        if (!$this->_requestToken || !$this->_requestTokenSecret)
            $this->getRequestToken();
        $url = $this->_oauth->getAuthorizeURL($this->_requestToken, false, $this->_callback);
        return $url;
    }
    
    public function getRequestToken()
    {
        if (!$this->_requestToken || !$this->_requestTokenSecret) {
            $tokens = $this->_oauth->getRequestToken();
            $_SESSION['requestToken'] = $this->_requestToken = $tokens['oauth_token'];
            $_SESSION['requestTokenSecret'] = $this->_requestTokenSecret = $tokens['oauth_token_secret'];
            return $tokens;
        }
    }
    
    public function getAccessToken($oauthVerifier)
    {
        if (!$this->_accessToken || !$this->_accessTokenSecret) {
            $tokens = $this->_oauth->getAccessToken($oauthVerifier);
            $_SESSION['accessToken'] = $this->_accessToken = $tokens['oauth_token'];
            $_SESSION['accessTokenSecret'] = $this->_accessTokenSecret = $tokens['oauth_token_secret'];
            $_SESSION['userId'] = $this->_userId = $tokens['user_id'];
        }
        $this->_requestToken = $this->_requestTokenSecret = null;
    }
    
    public function __construct($apiKey, $apiSecert)
    {
        session_start();
        $this->_apiKey = $apiKey;
        $this->_apiSecert = $apiSecert;
        
        $this->_requestToken = $_SESSION['requestToken'];
        $this->_requestTokenSecret = $_SESSION['requestTokenSecret'];
        $this->_accessToken = $_SESSION['accessToken'];
        $this->_accessTokenSecret = $_SESSION['accessTokenSecret'];
        $this->_userId = $_SESSION['userId'];
        
        $this->_oauth = new WeiboOAuth($this->_apiKey, $this->_apiSecert, $this->_requestToken, $this->_requestTokenSecret);
    }
    
    public function setCallback($url)
    {
        $this->_callback = $url;
    }
    
    public function getCallback()
    {
        return $this->_callback;
    }
    
    public function getClient()
    {
        if (!$this->_accessToken || !$this->_accessTokenSecret)
            throw new OAuthException('未授权', 0);
        return $this->client = new WeiboClient($this->_apiKey, $this->_apiSecert, $this->_accessToken, $this->_accessTokenSecret);
    }
    
    public function getUserInfo($uid = null)
    {
        $userid = (null === $uid) ? $this->_userId : $uid;
        if (empty($userid))
            throw new OAuthException('$uid 不能为空', 0);
        
        if (!$this->client) $this->getClient();
        return $this->_user = $this->client->show_user($userid);
    }
    
    public function create52WmId($user = null)
    {
        $user = (null === $user) ? $this->_user : $user;
        if (empty($user))
            throw new Exception('$user 不能为空', 0);
            
        $newUsername = $user['name'] . $user['id'];
        $wmUser = User::model()->findByAttributes(array('username'=>$newUsername));
        if ($wmUser) return $wmUser;
        
        $wmUser = new User();
        $wmUser->username = $newUsername;
        $wmUser->password = $wmUser->clear_password = $wmUser->source_uid = $user['id'];
        $wmUser->source = User::SOURCE_SINA;
        $wmUser->realname = $user['name'];
        $wmUser->gender = self::$_genders[$user['gender']];
        $wmUser->save();
        return $wmUser;
    }
    
    public static function clearSession()
    {
        unset($_SESSION['requestToken'], $_SESSION['requestTokenSecret'], $_SESSION['accessToken'], $_SESSION['accessTokenSecret'], $_SESSION['userId']);
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
}
