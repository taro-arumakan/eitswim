<?php

$host = $_SERVER['HTTP_HOST'];
switch ( $host ) {
    case 'eitswim.local':		        // ローカルWP開発環境
        /** MySQL のホスト名 */
        $host_name = 'localhost';
        $db_name = 'eitswim_system';
        $username = 'root';
        $password =  'penpen';
        break;
    case 'wp.eitswim.com':		        // テスト環境
        /** MySQL のホスト名 */
        $host_name = 'localhost';
        $db_name = 'eitswim_system_wp';
        $username = 'root';
        $password =  'eQ5fuRIo5hWL';
        break;
	default:				// 本番
		/** MySQL のホスト名 */
        $host_name = 'localhost';
        $db_name = 'eitswim_system';
        $username = 'root';
        $password =  'eQ5fuRIo5hWL';

		break;
}

return	array(
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host='.$host_name.';dbname='.$db_name,
			'emulatePrepare' => true,
			'username' => $username,
			'password' => $password,
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),

	),
);
