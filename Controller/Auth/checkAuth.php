<?
/*
 * smart_omr auth 
 * */
if($_SESSION['smart_omr']){
	
	$intAuthFlg = AUTH_TRUE;
	$GLOBALS['GLOBAL_APP_NAME'] = 'SMART_OMR';
	$intMemberSeq = $intMemberSeq?$intMemberSeq:SMART_OMR_TEACHER_SEQ;
}
?>