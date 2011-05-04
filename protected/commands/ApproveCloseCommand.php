<?php
class ApproveCloseCommand extends CConsoleCommand
{
    public function init()
    {
       
    }
    
    public function actionIndex()
    {
    	 Setting::setValue(param('s_orderApprove'), STATE_DISABLED);
    }
}