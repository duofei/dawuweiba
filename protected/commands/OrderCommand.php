<?php
class OrderCommand extends CConsoleCommand
{
    private $_log;
    
    public function init()
    {
        $this->_log = fopen(app()->runtimePath . DS . '52wm-autoGoodRating.log', 'a+');
		flock($this->_log, LOCK_EX);
        fwrite($this->_log, date(param('formatDateTime')) . " - start autoGoodRating\n");
        flock($this->_log, LOCK_UN);
    }
    public function getHelp()
    {
        return <<<EOD
order auto operate
EOD;
    }
    
    public function actionAutoGoodRating($rating = STATE_ENABLED)
    {
        $condition = new CDbCriteria();
		$condition->addCondition('t.status=' . Order::STATUS_COMPLETE . ' or t.status=' . Order::STATUS_DELIVERING);
		$time1 = strtotime('-7 day');
		$time2 = strtotime('-10 day');
		$condition->addCondition("t.create_time < $time1 and t.create_time > $time2");
		$orderList = Order::model()->with(array('shopCreditLogs'=>array('select'=>'id')))->findAll($condition);
		
		foreach ($orderList as $k => $row) {
			if(!$row->shopCreditLogs->id) {
			    try {
    				$shopcreditlog = new ShopCreditLog();
            		$shopcreditlog->order_id = $row->id;
            		$shopcreditlog->shop_id = $row->shop_id;
            		$shopcreditlog->evaluate = $rating;
            		$result = $shopcreditlog->save();
            		$status = $result ? 'success' : 'failed';
            		$log = date(param('formatDateTime')) . " - orderSn: $row->orderSn, Rating: {$rating}, status: {$status}\n";k($this->_log, LOCK_EX);
            		fwrite($this->_log, $log);
            		flock($this->_log, LOCK_UN);
			    } catch (CException $e) {
			        continue;
			    }
			}
		}
		   
    }
    
    protected function afterAction($action, $params)
    {
        fclose($this->_log);
    }
}