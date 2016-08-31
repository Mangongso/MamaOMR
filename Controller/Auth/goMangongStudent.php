<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/Member/Member.php');
require_once('Model/ManGong/Teacher.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTeacher = new Teacher($resMangongDB);
$objMember = new Member($resMangongDB);

/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}
if($arrMember[0]['auth_key']){
	//get mangong student
	$arrMangongStudent = $objTeacher->getMemberByLevel(1000);
	
	//token && session empty
	session_unset(); //세션변수 초기화
	setcookie("member_token", "", time()-3600, '/');
	setcookie("mg_select_teacher", "", time()-3600, '/');
	//set auth key
	$strMemberToken = md5($arrMangongStudent[0]['email'].$arrMangongStudent[0]['cphone'].$_SERVER['REMOTE_ADDR'].time());
	setcookie("member_token", $strMemberToken, time()+60*60*24*30, '/');
	$_SESSION[$strMemberToken] = array(
		'auth_key'=>$arrMangongStudent[0]['auth_key'],//기존 인증키를 그대로 사용한다.
		'member_seq'=>$arrMangongStudent[0]['member_seq'],
		'nickname'=>$arrMangongStudent[0]['nickname'],
		'name'=>$arrMangongStudent[0]['name'],
		'member_type'=>$arrMangongStudent[0]['member_type'],
		'teacher_seq'=>$intMemberSeq
	);	
	$boolResult = true;	
}else{	
	$boolResult = false;
}
$arrResult = array(
	'boolResult'=>$boolResult,
	'error_msg'=>'화면을 새로고침 후 다시 시도하세요<br>문제가 지속되면 <a href="mailto:membership@mangongso.com" target="_blank" style="color:#ffff00;" >membership@mangongso.com</a> 으로 <br>문의 주세요.'		
);
echo json_encode($arrResult);


?>