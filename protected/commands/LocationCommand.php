<?php
class LocationCommand extends CConsoleCommand
{
    private $_log;
    
    public function init()
    {
        $this->_log = fopen(app()->runtimePath . DS . '52wm-ShopNumsCalculate.log', 'a+');
    }
    
    public function actionIndex()
    {
        echo $this->getHelp();
    }
    
    public function actionShopNumsCalculate()
    {
        echo 'Are you sure you want to retry calculate shop count of every location?(Yes|No)';
        $line = trim(fgets(STDIN));
        if ('yes' != strtolower($line)) {
            echo 'Success Exit';
            exit(0);
        }
        flock($this->_log, LOCK_EX);
        fwrite($this->_log, date(param('formatDateTime')) . " - start CalculateShopNums\nLoading Locations And Shops...\n");
        flock($this->_log, LOCK_UN);
        
        $sql = "select id, shop_name, category_id, map_x, map_y, map_region, state from {{Shop}} where state = " . STATE_ENABLED;
        $command = app()->db->createCommand($sql);
        $shops = $command->queryAll();
        $sql = "select id, map_x, map_y, food_nums, cake_nums from {{Location}} where map_x != '' and map_y != ''";
        $command = app()->db->createCommand($sql);
        $dataReader = $command->query();
        
        foreach ($dataReader as $l) {
            $count = array();
            foreach ($shops as $sk => $s) {
                if (empty($s['map_region'])) continue;
                if (CdcBetaTools::pointInPolygon(self::getMapRegion($s), $l['map_x'], $l['map_y'])) {
                    $count[$s['category_id']]++;
                }
                echo "{$l['id']}: {$s['id']}\n";
                unset($sk, $s);
            }
            $sql = 'update {{Location}} set food_nums=' . (int)$count[ShopCategory::CATEGORY_FOOD] . ', cake_nums=' . (int)$count[ShopCategory::CATEGORY_CAKE] . ' where id=' . $l['id'];
            app()->db->createCommand($sql)->execute();
            unset($sql);
        }
        
        $time = microtime(true) - YII_BEGIN_TIME;
        $log = 'Location Count: ' . $dataReader->rowCount . ', Shop Count: ' . count($shops) . ", Time: {$time}s\n";
        flock($this->_log, LOCK_EX);
		fwrite($this->_log, $log);
		flock($this->_log, LOCK_UN);
		fclose($this->_log);
        unset($shops, $command, $dataReader);
    }
    
	private static function getMapRegion($shop)
	{
	    if ($shop['map_region']) {
	        $data = explode(Shop::SEPARATOR_REGION_POINT, $shop['map_region']);
	        foreach ($data as $v) {
	            $points[] = explode(Shop::SEPARATOR_REGION_LATLON, $v);
	        }
	        return $points;
	    }
	    return null;
	}

    public function getHelp()
    {
        return <<<EOD
Follow args is available:
shopNumsCalculate - Calculate Shop Count of Every Location

EOD;
    }

    protected function afterAction($action, $params)
    {
        fclose($this->_log);
    }
}