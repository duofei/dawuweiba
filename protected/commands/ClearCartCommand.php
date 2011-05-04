<?php
class ClearCartCommand extends CConsoleCommand
{
    public function getHelp()
    {
        return 'Clear All Goods Of Cart';
    }
    
    public function run($args)
    {
        echo 'Are you sure you want to clear all goods of cart?(Yes|No)';
        $line = trim(fgets(STDIN));
        if ('no' == strtolower($line)) {
            echo 'Success Exit';
            exit(0);
        }
        
        $result = Cart::model()->deleteAll();
        
        echo (false === $result) ? 'Clear Error' : 'Clear Success';
        exit(0);
    }
}