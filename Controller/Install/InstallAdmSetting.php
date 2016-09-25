<?
session_unset(); //세션변수 초기화
session_destroy(); //세션 파괴
require_once(dirname ( __file__)."/ChkInstall.php");
$arrResult = $resMangongDB->DB_access($resMangongDB, "show tables");
if (count($arrConfFailCnt) > 0 || count($arrResult)==0) {
	header('Location: /smart_omr/install');
	exit;
} else {
	global $API_key;
	$arr_output['$API_key'] = $API_key;	
}
?>