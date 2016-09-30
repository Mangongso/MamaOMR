<?
/**
 * @ 로그인 여부를 확인 
 * */
if($_SESSION['smart_omr']){
	$intAuthFlg = AUTH_TRUE;
	//$intMemberSeq = $intMemberSeq?$intMemberSeq:SMART_OMR_TEACHER_SEQ;
}else{
	if($intAuthFlg!=AUTH_TRUE && !isset($intAuthRedirectFlg)){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/');
		exit;
	}else{
		$intAuthFlg = AUTH_FALSE;
	}
}
?>