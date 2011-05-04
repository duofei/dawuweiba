<?php
class BuildingCommand extends CConsoleCommand
{
    public function run($args)
    {
        if (empty($args)) {
            echo $this->getHelp();
            exit(0);
        }
        
        if (method_exists($this, $args[0]))
            $this->{$args[0]}();
        else
            echo $args[0] . ' method not exist';
        
    }
    
    private function updateBuildingFirstLetter()
    {
        echo 'Are you sure you want to update the first letter of building?(Yes|No)';
        $line = trim(fgets(STDIN));
        if ('no' == strtolower($line)) {
            echo 'Success Exit';
            exit(0);
        }
        
        echo 'Loading Buildings...';
        
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name, letter';
        $criteria->addColumnCondition(array('state'=>STATE_ENABLED, 'letter'=>''));
        $criteria->order = 'id asc';
        $buildings = Building::model()->findAll($criteria);
        
        foreach ($buildings as $k => $v) {
            $v->letter = CdcBetaTools::getFirstLetter($v->name);
            $result = $v->update();
            echo "Budilgin Id:{$v->id}, update ", ($result ? 'success' : 'failed'), "\n";
        }
        
        $time = microtime(true) - YII_BEGIN_TIME;
        echo 'Building Count: ' . count($buildings) . ", Time: {$time}s\n";
        
    }
    
    public function getHelp()
    {
        return <<<EOD
Follow args is available:
updateBuildingFirstLetter - update letter field of building table

EOD;

    }
}