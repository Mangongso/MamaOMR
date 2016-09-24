<?php
/**
 * DB Connection Information
 * @final	SERVICE_MODE STAGE or PRODUCTION or Other(DEV)
 * SERVICE_MODE in the View/_connector/yellow.501.php
 */
global $DB_info;
$DB_info = array ();
switch (SERVICE_MODE) {
	case ("PRODUCTION") :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'MY_HOST',
				'user' => 'MY_USER',
				'pass' => 'MY_PASSWORD',
				'database' => 'MY_DATABASE' 
		);
		break;
	case ("STAGE") :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'MY_HOST',
				'user' => 'MY_USER',
				'pass' => 'MY_PASSWORD',
				'database' => 'MY_DATABASE' 
		);
		break;
	default :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'MY_HOST',
				'user' => 'MY_USER',
				'pass' => 'MY_PASSWORD',
				'database' => 'MY_DATABASE' 
		);
		break;
}

/**
 * Set Social key
 * @final	SERVICE_MODE STAGE or PRODUCTION or Other(DEV)
 * SERVICE_MODE in the View/_connector/yellow.501.php
 */
global $API_key;
$API_key = array ();
switch (SERVICE_MODE) {
	case ("PRODUCTION") :
		$API_key ['naver'] = array (
				'client_id' => 'CLIENT_ID',
				'client_secret' => 'CLIENT_SECRET',
				'callback_url' => 'CALLBACK_URL',
				'domain'=>'DOMAIN'
		);
		$API_key ['facebook'] = array (	'app_id' => 'APP_ID' );
		$API_key ['kakao'] = array (	'client_id' => 'APP_ID' );
		break;
	case ("STAGE") :
		$API_key ['naver'] = array (
				'client_id' => 'CLIENT_ID',
				'client_secret' => 'CLIENT_SECRET',
				'callback_url' => 'CALLBACK_URL',
				'domain'=>'DOMAIN'
		);
		$API_key ['facebook'] = array (	'app_id' => 'APP_ID' );
		$API_key ['kakao'] = array (	'client_id' => 'APP_ID' );	
		break;
	default :
		$API_key ['naver'] = array (
				'client_id' => 'CLIENT_ID',
				'client_secret' => 'CLIENT_SECRET',
				'callback_url' => 'CALLBACK_URL',
				'domain'=>'DOMAIN'
		);
		$API_key ['facebook'] = array (	'app_id' => 'APP_ID' );
		$API_key ['kakao'] = array (	'client_id' => 'APP_ID' );	
		break;
}
define("BOOK_SEARCH_API_KEY","49769dcbb5d89eaf2d3c069ac7ca321e");
//define("TMP_DIR","/tmp/");
define("TMP_DIR","C:/xampp/tmp/");
?>