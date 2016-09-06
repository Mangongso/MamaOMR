<?php
/*
 * DB Connection Information
 * SERVICE_MODE in the View/_connector/yellow.501.php
 * SERVICE_MODE : STAGE or PRODUCTION or Other(DEV)
 */
$DB_info = array ();
switch (SERVICE_MODE) {
	case ("PRODUCTION") :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'localhost',
				'user' => 'root',
				'pass' => 'fkaofhrmdls',
				'database' => 'mamaomr' 
		);
		break;
	case ("STAGE") :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'localhost',
				'user' => 'root',
				'pass' => 'nothing',
				'database' => 'mamaomr_stage' 
		);
		break;
	default :
		$DB_info ['MAIN_SERVER'] = array (
				'host' => 'localhost',
				'user' => 'root',
				'pass' => 'nothing',
				'database' => 'mamaomr_dev' 
		);
		break;
}

/*
 * Set Social key
 */
$API_key = array ();
switch (SERVICE_MODE) {
	case ("PRODUCTION") :
		$API_key ['naver'] = array (
				'client_id' => 's6yf8ssuNGj8r5cMGUuL',
				'client_secret' => 'bNvCazxoHZ',
				'callback_url' => '/smart_omr/_common/elements/naver_auth_callback.php',
				'domain'=>'dev.mamaomr.hanbnc.com'
		);
		$API_key ['facebook'] = array (	'app_id' => '822982321170072');	
		$API_key ['kakao'] = array (
				'client_id' => 's6yf8ssuNGj8r5cMGUuL',
				'client_secret' => 'bNvCazxoHZ',
				'callback_url' => '/smart_omr/_common/elements/naver_auth_callback.php',
				'domain'=>'dev.mamaomr.hanbnc.com'
		);		
		break;
	case ("STAGE") :
		$API_key ['naver'] = array (
				'client_id' => 's6yf8ssuNGj8r5cMGUuL',
				'client_secret' => 'bNvCazxoHZ',
				'callback_url' => '/smart_omr/_common/elements/naver_auth_callback.php',
				'domain'=>'dev.mamaomr.hanbnc.com'
		);
		break;
	default :
		$API_key ['naver'] = array (
				'client_id' => 's6yf8ssuNGj8r5cMGUuL',
				'client_secret' => 'bNvCazxoHZ',
				'callback_url' => 'http://dev.mamaomr.hanbnc.com/smart_omr/_common/elements/naver_auth_callback.php',
				'domain'=>'dev.mamaomr.hanbnc.com'
		);
		break;
}
define("BOOK_SEARCH_API_KEY","49769dcbb5d89eaf2d3c069ac7ca321e");
?>