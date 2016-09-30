<?php
/**
 * ----------------------------------------
 * This file copy to MamaOMR.conf.php
 * ----------------------------------------
 */

/**
 * DB Connection Information
 * MY_HOST : MySQL HOST ex)localhost,192.168.0.100
 * MY_USER : MySQL User Name ex)gildong, john
 * MY_PASSWORD : MySQL User Password
 * MY_DATABASE : MySQL Database Name ex)mamaomr
 */
global $DB_info;
$DB_info['MAIN_SERVER'] = array (
		'host' => 'MAMA_OMR_DB_HOST',
		'user' => 'MAMA_OMR_DB_USER',
		'pass' => 'MAMA_OMR_DB_PASSWORD',
		'database' => 'MAMA_OMR_DB_DATABASE' 
);
/**
 * Set Social key
 * Login MamaOMR With SNS
 * doc - https://github.com/Mangongso/MamaOMR/wiki/%EC%84%A4%EC%B9%98%EC%A4%80%EB%B9%84%ED%95%98%EA%B8%B0
 */
global $API_key;
$API_key['naver'] = array (
		'client_id' => 'CLIENT_ID',
		'client_secret' => 'CLIENT_SECRET',
		'callback_url' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/smart_omr/_common/elements/naver_auth_callback.php',
		'domain'=>$_SERVER['HTTP_HOST']
);
$API_key['facebook'] = array (	'app_id' => 'APP_ID' );
$API_key['kakao'] = array ('client_id' => 'CLIENT_ID' );

/**
 * Set book api key
 * MamaOMR Book Search From Daum Book Search API
 * doc - https://github.com/Mangongso/MamaOMR/wiki/%EC%84%A4%EC%B9%98%EC%A4%80%EB%B9%84%ED%95%98%EA%B8%B0
 */
define("BOOK_SEARCH_API_KEY","BOOK_SEARCH_API_KEY");

/**
 * File Upload Temp Directory
 */
define("TMP_DIR","/tmp");

/**
 * OCR Select
 * MamaOMR Prepare 2 OMR one is tesseract the other is ocr.space API
 * value : tesseract or ocr.space
 * tesseract 를 사용할 경우 tesseract 가 설치 되어 있어야 합니다. - https://github.com/tesseract-ocr/tesseract
 */
define("OCR_TYPE","ocr.space");
define("OCR_API_KEY","OCR_API_KEY");
?>