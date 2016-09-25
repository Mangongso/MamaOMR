<?
/**
 * @ 로그인 여부를 확인 
 * */
if($_SESSION['smart_omr']){
	
	$intAuthFlg = AUTH_TRUE;
	$intMemberSeq = $intMemberSeq?$intMemberSeq:SMART_OMR_TEACHER_SEQ;
}
?>