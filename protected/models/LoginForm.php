<?php
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $repassword;
    public $validateCode;
    public $rememberMe = 1;
    public $email;
    public $telphone;
    public $service;
    
    private $_identity;
    private static $_maxLoginErrorNums = 3;
    
    public function rules()
    {
        return array(
            array('username', 'required', 'message'=>'请输入会员名称'),
            array('username', 'unique', 'className'=>'User', 'attributeName'=>'username', 'on'=>'insert', 'message'=>'用户名已经存在'),
            array('email', 'unique', 'className'=>'User', 'attributeName'=>'email', 'on'=>'insert', 'message'=>'邮箱已被使用'),
            array('username', 'checkReserveWords', 'on'=>'insert, update'),
            array('username', 'CdcDenyWordsValidator', 'on'=>'insert, update'),
            array('password', 'required', 'on'=>'insert', 'message'=>'请输入会员密码'),
            array('validateCode', 'captcha', 'allowEmpty'=>!self::getEnableCaptcha(), 'on'=>'login'),
            array('password', 'authenticate', 'on'=>'login, airLogin'),
            array('repassword', 'compare', 'compareAttribute'=>'password', 'on'=>'insert', 'message'=>'两次密码输入不一致'),
            array('email', 'email', 'on'=>'insert'),
            array('telphone', 'required', 'on'=>'insert'),
            array('service', 'compare', 'compareValue'=>true, 'on'=>'insert', 'message'=>'请同意我爱外卖网的服务条款和协议'),
            array('rememberMe', 'in', 'range'=>array(0, 1)),
        );
    }
    
    public function authenticate($attribute, $params)
    {
        $this->_identity = new UserIdentity($this->username, $this->password);
        
        if (!$this->_identity->authenticate()) {
            $this->addError($attribute, '用户名或密码错误');
        }
    }
    
    public function checkReserveWords($attribute, $params)
    {
        $words = require(app()->basePath . '/config/user_words.php');
        foreach ($words as $v) {
            $pos = strpos($this->$attribute, $v);
            if (false !== $pos) {
                $this->addError($attribute, '用户名中存在禁止使用的保留字，请重新选择用户名');
                break;
            }
        }
        return true;
    }
    
    
    public function attributeLabels()
    {
        return array(
            'username' => '用户名',
            'password' => '密　码',
            'repassword' => '确认密码',
            'validateCode' => '验证码',
            'rememberMe' => '记住我',
            'email' => '电子邮箱',
            'telphone' => '手机',
        	'service' => '服务条款和协议',
        );
    }
    
    /**
     * 用户登陆
     */
    public function login()
    {
        if ($this->_identity->isAuthenticated) {
            $duration = (user()->allowAutoLogin && $this->rememberMe) ? param('autoLoginDuration') : 0;
            user()->login($this->_identity, $duration);
        }
    }
    
    /**
     * 创建新账号
     */
    public function createUser()
    {
        $user = new User();
	    $user->username = $this->username;
	    $user->password = $user->clear_password = $this->password;
	    $user->email = $this->email;
	    $user->telphone = $this->telphone;
	    $result = $user->save();
	    if (!$result) return false;
	    
	    $this->_identity = new UserIdentity($this->username, $this->password);
        if (!$this->_identity->authenticate()) return false;
        
        return $result;
    }
    
    public static function incrementErrorLoginNums()
    {
        $errorNums = (int)$_COOKIE['loginErrorNums'];
        setcookie('loginErrorNums', ++$errorNums, $_SERVER['REQUEST_TIME'] + 3600, '/', param('cookie_domain'));
    }
    
    public static function clearErrorLoginNums()
    {
        return setcookie('loginErrorNums', null, null, param('cookie_path'), param('cookie_domain'));
    }
    
    public static function getEnableCaptcha()
    {
        $errorNums = (int)$_COOKIE['loginErrorNums'];
        return ($errorNums >= self::$_maxLoginErrorNums) ? true : false;
    }
    
    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->getErrors())
            self::incrementErrorLoginNums();
        else
            self::clearErrorLoginNums();
    }
    
}