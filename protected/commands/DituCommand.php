<?php
class DituCommand extends CConsoleCommand
{
    private $_log;
    
    public function init()
    {
        $this->_log = fopen(app()->runtimePath . DS . '52wm-createMapRegion.log', 'a+');
		flock($this->_log, LOCK_EX);
        fwrite($this->_log, date(param('formatDateTime')) . " - start create MapRegion\n");
        flock($this->_log, LOCK_UN);
    }
    
    public function getHelp()
    {
        return <<<EOD
Ditu auto operate
EOD;

    }
    
    public function actionGenerateImage()
    {
    	$criteria = new CDbCriteria();
    	$criteria->select = "id, shop_name, map_region, map_region2, map_region3, create_time";
    	$criteria->addColumnCondition(array('state'=>STATE_ENABLED));
    	$criteria->addInCondition('business_state', array(Shop::BUSINESS_STATE_OPEN, Shop::BUSINESS_STATE_SUSPEND));
    	$shops = Shop::model()->findAll($criteria);
    	$maxMin = array(
    		'max' => array('x'=>0, 'y'=>0),
    		'min' => array('x'=>1000, 'y'=>1000),
    	);
    	$regions = array();
    	$regionsMonthAgo = array();
    	foreach ($shops as $shop) {
    		$points = $shop->maxMapRegion;
    		if($points) {
	    		foreach ($points as $temp) {
	    			if($maxMin['max']['x'] < $temp[0]) {
	    				$maxMin['max']['x'] = $temp[0];
	    			}
	    			if($maxMin['max']['y'] < $temp[1]) {
	    				$maxMin['max']['y'] = $temp[1];
	    			}
	    			if($maxMin['min']['x'] > $temp[0]) {
	    				$maxMin['min']['x'] = $temp[0];
	    			}
	    			if($maxMin['min']['y'] > $temp[1]) {
	    				$maxMin['min']['y'] = $temp[1];
	    			}
	    			$regions[$shop->id][] = array($temp[0], $temp[1]);
	    		}
    			if($shop->create_time < (time()-2592000)) {
	    			$regionsMonthAgo[$shop->id] = $regions[$shop->id];
	    		}
    		}
    	}
    	$baseNum = 3000;
    	$width = ($maxMin['max']['x'] - $maxMin['min']['x']) * $baseNum;
    	$height = ($maxMin['max']['y'] - $maxMin['min']['y']) * $baseNum;
    	app()->cache->set('dituGenerateImage', serialize($maxMin));
    	// 图片处理开始
    	$image = ImageCreate($width, $height);
    	$bg = imagecolorallocatealpha($image, 255, 255, 255, 100);
    	$color = imagecolorallocatealpha($image, 255, 151, 0, 80);
    	foreach ($regions as $key=>$coored) {
    		$values = array();
    		foreach ($coored as $c) {
    			$values[] = ($c[0]-$maxMin['min']['x']) * $baseNum;
    			$values[] = ($maxMin['max']['y']-$c[1]) * $baseNum;
    		}
    		imagefilledpolygon($image, $values, count($values)/2, $color);
    	}
		imagepng($image, param('staticBasePath') . 'ditu' . '/ditu_now.png');
		imagedestroy($image);
		
		$imageMonthAgo = ImageCreate($width, $height);
		$bgMonthAgo = imagecolorallocatealpha($imageMonthAgo, 255, 255, 255, 100);
		$colorMonthAgo = imagecolorallocatealpha($imageMonthAgo, 255, 103, 4, 80);
		foreach ($regionsMonthAgo as $key=>$coored) {
    		$values = array();
    		foreach ($coored as $c) {
    			$values[] = ($c[0]-$maxMin['min']['x']) * $baseNum;
    			$values[] = ($maxMin['max']['y']-$c[1]) * $baseNum;
    		}
    		imagefilledpolygon($imageMonthAgo, $values, count($values)/2, $colorMonthAgo);
    	}
		imagepng($imageMonthAgo, param('staticBasePath') . 'ditu' . '/ditu_monthago.png');
		imagedestroy($imageMonthAgo);
		
		flock($this->_log, LOCK_EX);
        fwrite($this->_log, date(param('formatDateTime')) . " - createMapRegion Successfully.\n");
        flock($this->_log, LOCK_UN);
    }

    protected function afterAction($action, $params)
    {
        fclose($this->_log);
    }

}