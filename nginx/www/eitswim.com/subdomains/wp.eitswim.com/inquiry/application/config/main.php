<?php

require_once( dirname(__FILE__).'/../globals.php');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	require(dirname(__FILE__).'/local.php'),
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'eitswim',

		'homeUrl'=>'https://'.$_SERVER['SERVER_NAME'],

		'sourceLanguage'=>'en',
		'language'=>'ja',

		// preloading 'log' component
		'preload'=>array('log'),

		// autoloading model and component classes
		'import'=>array(
			'application.models.*',
			'application.components.*',
			'application.components.widgets.*',
			'application.modules.user.models.*',
			'application.modules.user.components.*',
			'application.vendors.*',
			'ext.yiireport.*',
		),

		'defaultController'=>'site',

		// application components
		'components'=>array(
			'user'=>array(
				// enable cookie-based authentication
				'allowAutoLogin'=>true,
				'loginUrl' => array('/user/login'),
			),
			'file'=>array(
				'class'=>'ext.file.CFile',
			),
			'errorHandler'=>array(
				//use 'site/error' action to display errors
				'errorAction'=>'site/error',
			),
			'urlManager'=>array(
				'urlFormat'=>'path',
				"urlSuffix"=>'',
				'showScriptName'=>false,
				'rules'=>array(
					'user'=>'user',
					'user/<action:\w+>'=>'user/<action>',
					'ajax/inquiry'=>'ajax/inquiry',
                    'admin/top'=>'admin/index',
                    'admin/history/<function:\w+>'=>'admin/history',
					'<kind:\w+>'=>'site/index',
                    '<function:\w+>/<kind:\w+>'=>'site/index',
				),
			),
			'coreMessages'=>array(
				'basePath'=>null,
			),
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
				),
			),
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'mobileDetect' => array(
				'class' => 'ext.MobileDetect.MobileDetect'
			),
			'request'=>array(
				'enableCookieValidation'=>true,
			),
		),
		'modules'=>array(
			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'12345',
			),
			'user',
		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>require(dirname(__FILE__).'/params.php'),
	));
