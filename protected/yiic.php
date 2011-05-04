<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once(dirname(__FILE__) . '/../library/framework/yii.php');
$global = dirname(__FILE__) . '/../library/global.php';
require_once($global);
$config = dirname(__FILE__) . '/config/console.php';
Yii::createConsoleApplication($config)->run();