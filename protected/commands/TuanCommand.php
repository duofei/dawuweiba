<?php
class TuanCommand extends CConsoleCommand
{
    private $_log;
    
    public function init()
    {
        $this->_log = fopen(app()->runtimePath . DS . '52wm-collectTuanInfo.log', 'a+');
		flock($this->_log, LOCK_EX);
        fwrite($this->_log, date(param('formatDateTime')) . " - start collect Tuan Info \n");
        flock($this->_log, LOCK_UN);
    }
    
    public function getHelp()
    {
        return <<<EOD
Tuan auto operate
EOD;
    }
    
	public function actionCollect()
    {
    	$criteria = new CDbCriteria();
    	$criteria->addCondition("apiurl!='' and apitype>0");
    	$tuanDataList = TuanData::model()->findAll($criteria);
    	foreach ($tuanDataList as $data) {
    		$function = 'collectDate'.$data->apiTypeName;
    		$this->$function($data->apiurl, $data->id);
    	}

    	$count = count($tuanDataList);
    	$log = date(param('formatDateTime')) . " - site total: {$count}\n";
		flock($this->_log, LOCK_EX);
		fwrite($this->_log, $log);
		flock($this->_log, LOCK_UN);
    }
    
    /**
     * 通用API接口采集
     * @param string $apiUrl 团购api地址
     * @param integer $source_id 团购网
     */
    public function collectDategeneral($apiUrl, $source_id)
    {
    	$remoteFile = @file_get_contents($apiUrl);
    	if(!$remoteFile) {
    		return false;
    	}
		$xml = @simplexml_load_string($remoteFile);
		if(!$xml) {
			return false;
		}
		unset($remoteFile);
		foreach ($xml as $url) {
			$display = $url->data->display;
			if((string)$display->city == '济南') {
				$criteria = new CDbCriteria();
				$criteria->addColumnCondition(array('url'=>(string)$url->loc));
				$tuanCount = Tuannav::model()->count($criteria);
				if($tuanCount) {
					continue;
				} else {
					$tuan = new Tuannav();
					
					if(isset($display->description)) {
						$content = (string)$display->description; 	// 团p
					} elseif(isset($display->detail)) {
						$content = (string)$display->detail;		// 拉手
					} else {
						$content = (string)$display->title;
					}
					
					$tuan->attributes = array(
						'url' => (string)$url->loc,
						'title' => (string)$display->title,
						'content' => $content,
						'image' => (string)$display->image,
						'group_price' => (string)$display->price,
						'original_price' => (string)$display->value,
						'effective_time' => preg_match("/^\d+$/",(string)$display->endTime) ? date('Y-m-d H:i:s',(string)$display->endTime) : (string)$display->endTime,
						'source_id' => $source_id,
						'city_id' => 1, // 1:济南
					);
					$tuan->save();
					//echo $tuan->title. "<br />\n-------------------------<br />\n";
					//echo CHtml::errorSummary($tuan);
				}
			}
		}
    }

    /**
     * 最土API接口采集
     * @param string $apiUrl 团购api地址
     * @param integer $source_id 团购网
     */
    public function collectDatezuitu($apiUrl, $source_id)
    {
    	$remoteFile = @file_get_contents($apiUrl);
    	if(!$remoteFile) {
    		return false;
    	}
		$xml = @simplexml_load_string($remoteFile);
		if(!$xml) {
			return false;
		}
		unset($remoteFile);
		$team = $xml->data->teams->team;
		foreach ($team as $t) {
			if((string)$t->city == '济南') {
				$criteria = new CDbCriteria();
				$criteria->addColumnCondition(array('url'=>(string)$t->link));
				$tuanCount = Tuannav::model()->count($criteria);
				if($tuanCount) {
					continue;
				} else {
					$tuan = new Tuannav();
					$tuan->attributes = array(
						'url' => (string)$t->link,
						'title' => (string)$t->title,
						'content' => (string)$t->title,
						'image' => (string)$t->large_image_url,
						'group_price' => (string)$t->team_price,
						'original_price' => (string)$t->market_price,
						'effective_time' => str_replace('T', ' ', substr((string)$t->end_date, 0, 19)),
						'source_id' => $source_id,
						'city_id' => 1, // 1:济南
					);
					$tuan->save();
					//echo $tuan->title. "<br />\n-------------------------<br />\n";
					//echo CHtml::errorSummary($tuan);
				}
			}
		}
    }
    
    /**
     * 团齐鲁API接口采集
     * @param string $apiUrl 团购api地址
     * @param integer $source_id 团购网
     */
    public function collectDatetuanqilu($apiUrl, $source_id)
    {
    	$remoteFile = @file_get_contents($apiUrl);
    	if(!$remoteFile) {
    		return false;
    	}
		$xml = @simplexml_load_string($remoteFile);
		if(!$xml) {
			return false;
		}
		unset($remoteFile);
		$goods = $xml->goods;
		foreach ($goods as $t) {
			if((string)$t->cityname == '济南') {
				$criteria = new CDbCriteria();
				$criteria->addColumnCondition(array('url'=>(string)$t->url));
				$tuanCount = Tuannav::model()->count($criteria);
				if($tuanCount) {
					continue;
				} else {
					$tuan = new Tuannav();
					$tuan->attributes = array(
						'url' => (string)$t->url,
						'title' => (string)$t->title,
						'content' => (string)$t->brief,
						'image' => (string)$t->bigimg,
						'group_price' => (string)$t->groupprice,
						'original_price' => (string)$t->marketprice,
						'effective_time' => date('Y-m-d H:i:s', strtotime((string)$t->endtime)),
						'source_id' => $source_id,
						'city_id' => 1, // 1:济南
					);
					$tuan->save();
					//echo $tuan->title. "<br />\n-------------------------<br />\n";
					//echo CHtml::errorSummary($tuan);
				}
			}
		}
    }

    public function collectDate58($apiUrl, $source_id)
    {
    	$remoteFile = @file_get_contents($apiUrl);
    	if(!$remoteFile) {
    		return false;
    	}
		$xml = @simplexml_load_string($remoteFile);
		if(!$xml) {
			return false;
		}
		unset($remoteFile);
    	$city = $xml->city;
		foreach ($city as $c) {
			if((string)$c->name == '济南') {
				$product = $c->product;
				foreach ($product as $p) {
					$criteria = new CDbCriteria();
					$criteria->addColumnCondition(array('url'=>(string)$p->url));
					$tuanCount = Tuannav::model()->count($criteria);
					if($tuanCount) {
						continue;
					} else {
						$tuan = new Tuannav();
						$tuan->attributes = array(
							'url' => (string)$p->url,
							'title' => (string)$p->name,
							'content' => (string)$p->introduction,
							'image' => (string)$p->image,
							'group_price' => (string)$p->group_price,
							'original_price' => (string)$p->market_price,
							'effective_time' => str_replace('日', '', str_replace(array('年','月'), '-', (string)$p->end_date)),
							'source_id' => $source_id,
							'city_id' => 1, // 1:济南
						);
						$tuan->save();
						//echo $tuan->url. "<br />\n-------------------------<br />\n";
						//echo CHtml::errorSummary($tuan);
					}
				}
			}
		}
    }
    
    protected function afterAction($action, $params)
    {
        fclose($this->_log);
    }
}