<?php
class RenrenappController extends Controller
{
    const API_KEY = 'e7785c96a90c4d63bbeaa44909ada1f7';
    const API_SECRET = '8c6e5f2860b044b4908f3a55a61b2dbc';
    
    public function init()
    {
        $this->layout = 'renren';
        parent::init();
    }
    
    public function actionIndex()
    {
        print_r($_GET);
        if (!$_GET['xn_sig_added'])
            $this->redirect(aurl('renrenapp/addapp'));
        $this->render('index');
    }
    
    public function actionAddApp()
    {
        $this->renderPartial('add_app');
    }
    
    /**
     * 用户授权应用后人人网平台回调
     */
    public function actionCallback()
    {
        $data = var_export($_POST, true);
        $filename = app()->getRuntimePath() . DS . 'callback.log';
        file_put_contents($filename, $data);
    }
    
    /**
     * 用户移除应用后人人网平台回调
     */
    public function actionRemove()
    {
        $data = var_export($_POST, true);
        $filename = app()->getRuntimePath() . DS . 'remove.log';
        file_put_contents($filename, $data);
    }
    
    public function actionInfo()
    {
        echo '我是我爱外卖应用';
    }
    
    public function actionXinxian()
    {
        print_r($_GET);
        
        $this->render('xinxian');
    }
    
    public function actionTest()
    {
        print_r($_GET);
        echo '<pre><hr />';
        $c = new CdRenrenClient(self::API_KEY, self::API_SECRET);
        $c->method('users.getInfo')
            ->add_args('uid', $_GET['xn_sig_user']);
        $data = $c->request()->data();
        echo '<hr />';
        echo $c->error();
        echo '<hr />';
        print_r($data);
        echo '<hr />';echo '<hr />';
        
        $data = $c->crevert()
            ->method('invitations.createLink')
            ->request()->data();
        echo $c->error();
        echo '<hr />';
        print_r($data);
        
        exit;
    }
    
    public function actionInvite()
    {
        $this->render('invite');
    }
}


