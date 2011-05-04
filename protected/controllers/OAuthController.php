<?php
class OAuthController extends Controller
{
    private $_server;
    /**
     * OAuthStore
     * @var OAuthStore
     */
    private $_store;
    
    public function init()
    {
        parent::init();
        require 'oauth/OAuthServer.php';
        $options = array(
            'server' => '192.168.1.254',
            'username' => 'my52wm',
            'password' => '123',
            'database' => 'wm_my52wm',
        );
        
        $this->_store = OAuthStore::instance('MySQL', $options);
        $this->_server = new OAuthServer();
        
    }
    
    public function actionRequestToken()
    {
        $this->_server->requestToken();
        app()->end();
    }
    
    public function actionAccessToken()
    {
        $this->_server->accessToken();
        app()->end();
    }
    
    public function actionAuthorize()
    {
        /*if (!$_SESSION['authorized'])
            throw new Exception('hahahaha', 101);*/
            
        $user_id = 1;
    	try
    	{
    		$a=$this->_server->authorizeVerify();
    		$this->_server->authorizeFinish(true, $user_id);
    	}
    	catch (OAuthException2 $e)
    	{
    		header('HTTP/1.1 400 Bad Request');
    		header('Content-Type: text/plain');
    		echo "Failed OAuth Request: " . $e->getMessage();
    	}
    	exit;
    }
    public function test2($consumer_key, $consumer_secret)
    {
        // Get the id of the current user (must be an int)
        $user_id = 1;
        
        // The server description
        $server = array(
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'server_uri' => 'http://www.52wm.cn/api/',
            'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
            'request_token_uri' => 'http://www.52wm.cn/oauth/requestToken',
            'authorize_uri' => 'http://www.52wm.cn/oauth/authorize',
            'access_token_uri' => 'http://www.52wm.cn/oauth/accessToken'
        );
        
        // Save the server in the the OAuthStore
        $consumer_key = $this->_store->updateServer($server, $user_id);
    }
    public function actionRegister()
    {
        // The currently logged on user
        $user_id = 1;
        
        // This should come from a form filled in by the requesting user
        $consumer = array(
            // These two are required
            'requester_name' => 'Chris Chen',
            'requester_email' => 'cdcchen@gmail.com',
        
            // These are all optional
            'callback_uri' => 'http://www.24beta.cn/',
            'application_uri' => 'http://www.24beta.cn/',
            'application_title' => 'John Doe\'s consumer site',
            'application_descr' => 'Make nice graphs of all your data',
            'application_notes' => 'Bladibla',
            'application_type' => 'website',
            'application_commercial' => 0
        );
        
        // Register the consumer
        $key   = $this->_store->updateConsumer($consumer, $user_id);
        
        // Get the complete consumer from the store
        $consumer = $this->_store->getConsumer($key, $user_id);
        
        // Some interesting fields, the user will need the key and secret
        $consumer_id = $consumer['id'];
        $consumer_key = $consumer['consumer_key'];
        $consumer_secret = $consumer['consumer_secret'];
        $this->test2($consumer_key, $consumer_secret);
        echo $consumer_id . '<br />';
        echo $consumer_key . '<br />';
        echo $consumer_secret . '<br />';
    }

}