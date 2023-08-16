<?php

// this contains the application parameters that can be maintained via GUI
$host = $_SERVER['HTTP_HOST'];
switch ( $host ) {
	case 'logpose.local':		        // ローカル開発環境
		$ips = array('*');
		break;
	case 'test.logpose.me':
		$ips = array(
			"133.232.126.163",
			"202.241.144.138",
			"120.143.49.72",
			"125.63.43.100",
			"203.138.98.181",
			"120.143.1.41"
		);
		break;
	default:				// 本番
		$ips = array(
			"133.232.126.163",
			"202.241.144.138",
		);
		break;
}

return CMap::mergeArray(
	require(dirname(__FILE__).'/local_params.php'),
	array(
		//USE SPOT有効・無効
		'enable_usen_spot' => false,
		//Base URL
		'base_url'=> 'http://'.$_SERVER['SERVER_NAME'],
		// this is displayed in the header section
		'title'=>'Wifi Spot',
		// this is used in error pages
		'adminEmail'=>'info@xxxxxxx.com',

		// the copyright information displayed in the footer section
		'copyrightInfo'=>'Copyright &copy; 2018 by Studio E.L.C.',

		'description' => '',
		'keywords' => '',

		'ips' => $ips,

		//<title>タイトル XXXXXXXX</title>
		'web_title_add' => ' - Logpose,',

		'ssl' => false,

		//１回に取得する件数
		'max_count_one' => 20,
		//１ページに表示するリンク数
		'max_link_page' => 10,

		//ERRORファイル保存パス
		'error_file_path' => '/data/log/',

		'question_type' => array(
			'Text',
			'Radio',
			'Select',
			'Checkbox',
			'TextArea',
		),
		'question_validation' => array(
			'電話',
			'メール',
			'確認',
			'カタカナ',
			'数字',
		),
		'rest_api_cmd' => array(
			'wp-json/wp/v2/posts?search=',
			'wp-json/wp/v2/pages?search=',
		),

	));
