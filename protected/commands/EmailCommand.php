<?php
class EmailCommand extends CConsoleCommand
{
    const RETRY_COUNT_LIMIT = 3;
    
    private $logFile;
     
    public function getHelp()
    {
        return 'Email Daemon';
    }
    
    public function run($args)
    {
        if (empty($this->logFile))
            $this->logFile = app()->runtimePath . DS . '52wm-email.log';
            
        $file = fopen($this->logFile, 'a+');
        fwrite($file, date('Y-m-d H:i:s') . "Email Send Daemon\n");
        
        $criteria = new CDbCriteria();
        $criteria->order = 'priority desc, id desc';
        $criteria->addColumnCondition(array('state'=>STATE_DISABLED));
        $mail = app()->mailer->getMail();
        
        while (true) {
            $time = date('Y-m-d H:i:s');
            $email = SendMail::model()->find($criteria);
            if (null === $email) {
                //$txt = "{$time} Email box is empty\n";
                //fwrite($file, $txt);
                sleep(5);
                continue;
            }
            
            $result = $this->send($email->subject, $email->body, $email->mailto);

	        if(!$result) {
	            $email->state = SendMail::ERRNO_SEND;
	          	$note = "{$time} send mail failed\n";
			} else {
			    $email->state = STATE_ENABLED;
			    $email->update_time = time();
			    $note = "{$time} send mail success. MailTo: {$email->mailto}\n";
	        }
	        $email->update();
	        fwrite($file, $note);
	        unset($email, $result, $time, $note);
        }
        fclose($file);
    }
    
    private function send($subject, $body, $mailto)
    {
        $time = date('Y-m-d H:i:s');
        for ($i=0; $i<self::RETRY_COUNT_LIMIT; $i++) {
            $result = SendMail::mailSend($subject, $body, $mailto);
            if ($result)
                return true;
            else
                ;//echo "{$time} Error: {$i} Times Send Failed\n";
        }
        unset($i, $result, $time);
        return false;
    }
}