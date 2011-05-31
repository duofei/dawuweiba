<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
require(dirname(__FILE__) . DS . 'define.php');
$params = require(dirname(__FILE__) . DS . 'params.php');

return array(
    'id' => '52wm.com',
	'basePath' => dirname(__FILE__) . DS . '..',
	'name' => '我爱外卖网',
    'charset' => 'utf-8',
    'language' => 'zh_cn',
    'layout' => 'main',
    'timeZone' => 'Asia/Shanghai',

    // preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.libs.*',
        'application.components.Portlet.*',
        'application.extensions.*',
        'application.extensions.AppApi.*',
        'application.extensions.wmapi.*',
	),
	
	'modules' => array(
	    'my' => array(
    	    'layout' => 'my',
    	),
    	'shopcp' => array(
    	    'layout' => 'shopcp',
    	),
    	'admin' => array(
    	    'layout' => 'admin',
    	),
    	'super' => array(
    	    'layout' => 'super',
    	),
    ),
    
	// application components
	'components' => array(
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace, info, error, warning, watch',
				    'categories' => 'system.db.*',
				),/*
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'trace, info, error, warning, watch',
					'categories' => 'system.*',
				),*/
			),
		),
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		    'guestName' => '游客',
		    'loginUrl' => array('/site/login'),
		),
		
		'db' => array(
		    'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=192.168.1.201; port=3306; dbname=wm_my52wm',
		    'username' => 'my52wm',
		    'password' => '123',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'wm_',
		    //'schemaCachingDuration' => 3600,    // metadata 缓存超时时间(s)
		    'enableParamLogging' => true,
		    'enableProfiling' => true,
		),
		'pgdb' => array(
		    'class' => 'CDbConnection',
			'connectionString' => 'pgsql:host=192.168.1.254; port=5432; dbname=my52wm',
		    'username' => 'my52wm',
		    'password' => '',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'wm_',
		    //'schemaCachingDuration' => 3600,    // metadata 缓存超时时间(s)
		    'enableParamLogging' => true,
		    'enableProfiling' => true,
		),
		'mdb' => array(
		    'class' => 'application.extensions.mongodb.CDMongodb',
		    'connectionString' => 'mongodb://192.168.1.254',
		    'dbname' => 'my52wm',
		    'options' => array(),
		    'collectionPrefix' => 'wm_',
		),
		
		'authManager' => array(
		    'class' => 'CDbAuthManager',
            'connectionID' => 'db',
		    'defaultRoles' => array('guest'),
		    'assignmentTable' => 'wm_AuthAssignment',
		    'itemChildTable' => 'wm_AuthItemChild',
		    'itemTable' => 'wm_AuthItem',
		),
		
		'assetManager' => array(
		    'basePath' => $params['resourceBasePath'] . 'assets',
		    'baseUrl' => $params['resourceBaseUrl'] . 'assets',
		),
		'mailer' => array(
		    'class' => 'application.extensions.CdcPhpMailer',
		    'SMTPDebug' => true,
		    'SMTPSecure' => $params['mailSMTPSecure'],
		    'Host' => $params['mailHost'],
		    'Port' => $params['mailPort'],
		    'Username' => $params['mailUsername'],
		    'Password' => $params['mailPassword'],
		),
		'image' => array(
		    'class' => 'application.extensions.image.CImageComponent',
		    'driver' => 'GD',
		),
		
		
		/*
		 * 大数据使用的内存方式
		 */
		'cache' => array(
		    'class' => 'CFileCache',
		    'directoryLevel' => 2,
		),
		'fileCache' => array(
		    'class' => 'CFileCache',
		    'directoryLevel' => 2,
		),
		'redis' => array(
		    'class' => 'CRedisCache',
		    'host' => '192.168.1.254',
		    'port' => '6379',
		),

		'urlManager' => array(
            'urlFormat' => 'path',
		    'showScriptName' => false,
		    'urlSuffix' => '.html',
		    'cacheID' => null, //此处上线后要修改为一个cache
            'rules' => array(
		    	'http://<uname:(?!www)\w+>.52wm.cn' => 'shop/boot',
            	'/' => 'site/index',
            	'connect/<_a:(sinat|renren)>' => 'site/<_a>',
		        '<_a:(login|logout|signup)>' => 'site/<_a>',
		        'at/<atid:\d+>-<cid:\d+>' => 'shop/list',
		        'at/<atid:\d+>' => 'shop/list',
		        'atc/<cid:\d+>' => 'shop/list',
		        'at/<lat:[\d\.]+>-<lon:[\d\.]+>' => 'shop/list',
		        'shop/<shopid:\d+>' => 'shop/show',
		        'shop/<_a>/<shopid:\d+>' => 'shop/<_a>',
		        'gift/<_a>/<giftid:\d+>' => 'gift/<_a>',
		        'goods/<goodsid:\d+>' => 'goods/show',
				'goods/top-c<cid:\d*>' => 'goods/top',
		        'promotion/<pid:\d+>' => 'promotion/index',
		
		        'groupon' => 'groupon/index',
		
		        'tuannav/' => 'tuannav/list',
		        'tuannav/category/<category_id:\d+>' => 'tuannav/search',
		        'tuannav/site/<source_id:[\d\-]+>' => 'tuannav/info',
		        'tuannav/show/<id:[\d]+>' => 'tuannav/show',
		        'tuannav/<sort:(buy|sell)>' => 'tuannav/second',
		        'tuannav/second-<sort:\w+>-<category_id:\d+>' => 'tuannav/secondSearch',
		        'tuannav/history/site/<source_id:\d+>' => 'tuannav/old',
		
		        
		
		        'static/<view:\w+>' => 'static/pages',
		        'sitemap/<_a>' => array('sitemap/<_a>', 'urlSuffix'=>'.xml'),
            ),
        ),
        'format' => array(
            'dateFormat' => 'Y-m-d',
            'timeFormat' => 'H:i:s',
            'datetimeFormat' => 'Y-m-d H:i:s',
            'numberFormat' => array('decimals'=>1, 'decimalSeparator'=>'.', 'thousandSeparator'=>''),
            'booleanFormat' => array('否', '是'),
        ),
        'session' => array(
            'sessionName' => md5('jkasdfasd^&&S*DFHJIOf89asdijf'),
            'cookieParams' => array(
                'path' => $params['cookiePath'],
                'domain' => $params['cookieDomain'],
            ),
        ),
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => $params,
);

