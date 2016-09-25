<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");

/* set variable */
$arrConfFailCnt = array();

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');

/*main process*/
// 1.Connector file 존재 확인
if (!file_exists ( $_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php" )) {
	array_push($arrConfFailCnt,1);
}
// 2.Include path 확인
if (strpos ( ini_get ( 'include_path' ), dirname ( $_SERVER ["DOCUMENT_ROOT"], 1 ) ) === false) {
	array_push($arrConfFailCnt,2);
}
// 3.Question upload directory 확인
if (!defined ( "QUESTION_FILE_DIR" ) || !trim ( QUESTION_FILE_DIR )) {
	array_push($arrConfFailCnt,3);
}
// 4.OMR upload directory 확인
if (!defined ( "OMR_FILE_DIR" ) || !trim ( OMR_FILE_DIR )) {
	array_push($arrConfFailCnt,4);
}
// 5.Files directory 확인
if (!file_exists ( dirname ( $_SERVER ["DOCUMENT_ROOT"], 1 ) . "/Files" ) || !substr ( sprintf ( "%o", fileperms ( dirname ( $_SERVER ["DOCUMENT_ROOT"], 1 ) . "/Files" ) ), - 4 ) == "0777") {
	array_push($arrConfFailCnt,5);
}
// 6.Config File 확인
if (!file_exists ( $_SERVER ["DOCUMENT_ROOT"] . "/../Controller/_Config/MamaOMR.conf.php" )) {
	array_push($arrConfFailCnt,6);
}
// 7.DB 설정 확인
global $DB_info;
if (($DB_info ['MAIN_SERVER'] ['host'] == "MAMA_OMR_DB_HOST" || ! trim ( $DB_info ['MAIN_SERVER'] ['host'] )) || ($DB_info ['MAIN_SERVER'] ['user'] == "MAMA_OMR_DB_USER" || ! trim ( $DB_info ['MAIN_SERVER'] ['user'] )) || ($DB_info ['MAIN_SERVER'] ['pass'] == "MAMA_OMR_DB_PASSWORD" || ! trim ( $DB_info ['MAIN_SERVER'] ['pass'] )) || ($DB_info ['MAIN_SERVER'] ['database'] == "MAMA_OMR_DB_DATABASE" || ! trim ( $DB_info ['MAIN_SERVER'] ['database'] ))) {
	array_push($arrConfFailCnt,7);
}
// 8.DB 연결 확인
if ($DB_info ['MAIN_SERVER'] ['host'] && $DB_info ['MAIN_SERVER'] ['database'] && $DB_info ['MAIN_SERVER'] ['user'] && $DB_info ['MAIN_SERVER'] ['pass']) {
	if (phpversion () < 7) {
		$conn = mysql_connect ( $DB_info ['MAIN_SERVER'] ['host'], $DB_info ['MAIN_SERVER'] ['user'], $DB_info ['MAIN_SERVER'] ['pass'], true );
	} else {
		try {
			$conn = new PDO ( 'mysql:host=' . $DB_info ['MAIN_SERVER'] ['host'] . ';dbname=' . $DB_info ['MAIN_SERVER'] ['database'], $DB_info ['MAIN_SERVER'] ['user'], $DB_info ['MAIN_SERVER'] ['pass'] );
		} catch ( PDOException $e ) {
			$conn = false;
		}
	}
} else {
	$conn = false;
}

if ($conn == false) {
	array_push($arrConfFailCnt,8);
}
// 9.APIP Key 설정 확인
global $API_key;
if (($API_key ['naver'] ['client_id'] == "CLIENT_ID" || ! trim ( $API_key ['naver'] ['client_id'] )) && ($API_key ['facebook'] ['app_id'] == "APP_ID" || ! trim ( $API_key ['facebook'] ['app_id'] )) && ($API_key ['kakao'] ['client_id'] == "CLIENT_ID" || ! trim ( $API_key ['kakao'] ['client_id'] ))) {
	array_push($arrConfFailCnt,'9-1');
}
if (!defined ( "BOOK_SEARCH_API_KEY" ) || trim ( BOOK_SEARCH_API_KEY ) == "BOOK_SEARCH_API_KEY" && trim ( BOOK_SEARCH_API_KEY ) == "") {
	array_push($arrConfFailCnt,'9-2');
}
// 10.temp directory 확인
if (!defined ( "TMP_DIR" ) || !file_exists ( TMP_DIR )) {
	array_push($arrConfFailCnt,10);
}
// 11.OCR Type 확인
if (!defined ( "OCR_TYPE" ) || (OCR_TYPE != "ocr.space" && OCR_TYPE != "tesseract")) {
	array_push($arrConfFailCnt,11);
}
// 12.OCR API Key 확인
if (!defined ( "OCR_API_KEY" ) || (OCR_API_KEY == "OCR_API_KEY" || trim ( OCR_TYPE ) == "")) {
	array_push($arrConfFailCnt,12);
}
?>