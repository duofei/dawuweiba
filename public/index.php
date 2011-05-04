<?php
defined('YII_DEBUG') or define('YII_DEBUG',true);
!defined('YII_DEBUG') && error_reporting(0);

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../library/framework/yii.php';
$config = dirname(__FILE__) . '/../protected/config/main.php';
$global = dirname(__FILE__) . '/../library/global.php';

require_once($yii);
require_once($global);

$app = Yii::createWebApplication($config);
mb_internal_encoding(app()->charset);
$app->run();
