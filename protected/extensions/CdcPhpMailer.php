<?php
class CdcPhpMailer extends CApplicationComponent
{
    public $abc;
    public $SMTPSecure;
    public $SMTPDebug;
    public $Host;
    public $Port;
    public $Username;
    public $Password;
    private $_mail;
    
    public function __construct()
    {
        require('PHPMailer/class.phpmailer.php');
	    $this->_mail = new PHPMailer();
    }
    
    public function getMail()
    {
        return $this->_mail;
    }

    public function init()
    {
        parent::init();
        
        $this->_mail->SMTPDebug = $this->SMTPDebug;
	    $this->_mail->CharSet = app()->charset;
	    $this->_mail->IsSMTP();
	    $this->_mail->SMTPAuth   = true;
	    $this->_mail->SMTPSecure = $this->SMTPSecure;
	    $this->_mail->Host = $this->Host;
	    $this->_mail->Port = $this->Port;
	    $this->_mail->Username = $this->Username;
	    $this->_mail->Password = $this->Password;
	    $this->_mail->From = $this->Username;
	    $this->_mail->FromName = app()->name;
    }
}