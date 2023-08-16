<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',
	'modules' => array(
		'user'=>array(
			# encrypting method (php hash function)
			'hash' => 'md5',
			# send activation email
			'sendActivationMail' => true,
			# allow access for non-activated users
			'loginNotActiv' => false,
			# activate user on registration (only sendActivationMail = false)
			'activeAfterRegister' => false,
			# automatically login from registration
			'autoLogin' => true,
			# registration path
			'registrationUrl' => array('/signup/'),
			# recovery password path
			'recoveryUrl' => array('/user/recovery'),
			# login form path
			'loginUrl' => array('/login/'),
			# page after login
			'returnUrl' => array('/'),
			# page after logout
			'returnLogoutUrl' => array('/'),
		),
	),
	'components'=>array(
		'db'=>array(
                        'connectionString' => 'mysql:host=localhost;dbname=stacey_yii',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                        'tablePrefix' => 'tbl_',
		),
	),
);