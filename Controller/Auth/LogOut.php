<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/Member/Member.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
$objMember = new Member($resAuthDB);

/* main process */
$intResult = $objMember->checkIsMember($resAuthDB,$intMemberSeq,$strPassword);
if($intResult){
	//update auth key 
	//$intResult = $objAuth->updateAuthKey($resAuthDB,$arrMember[0]['member_seq']);
}
//token && session empty
session_unset(); //세션변수 초기화
session_destroy(); //세션 파괴
setcookie(md5("autoLoginToken"), "", time()-3600, '/');
setcookie("member_token", "", time()-3600, '/');
setcookie("mg_select_teacher", "", time()-3600, '/');

$boolResult=true;

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult
);
echo json_encode($arrResult);
?>