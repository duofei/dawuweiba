<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../library/framework/yiit.php';
$config = dirname(__FILE__) . '/../protected/config/main.php';
$global = dirname(__FILE__) . '/../library/global.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
require_once($global);
Yii::createWebApplication($config)->run();
