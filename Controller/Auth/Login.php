<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/Member/Member.php');

/* set variable */ 
$strRememberToken = $_COOKIE['remember_token'];
$intTeacherSeq = $_POST['teacher_seq'];
$intInviteValue = $_GET['invite'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
$objMember = new Member($resAuthDB);

/* main process */
include(CONTROLLER_NAME."/Auth/checkAuth.php");
if($intAuthFlg==AUTH_TRUE){
	if($arrMember[0]['member_type']=="S"){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/student');
		exit;
	}else if($arrMember[0]['member_type']=="T"){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/teacher');
		exit;
	}
}else{
	$arrMember = $objMember->getMemberByCphone($resAuthDB,$strRememberToken);
	$arrCphone = explode("-",$arrMember[0]['cphone']);
}

if($intTeacherSeq){
	$arr_output['teacher'] = $objMember->getMemberByMemberSeq($intTeacherSeq);
}
if($intInviteValue){
	$arrInvite = $objMember->getMemberByCphone($resAuthDB,$intInviteValue,false);
	if($arrInvite[0]['member_type']=="T"){
		$arr_output['invite_teacher'] = $arrInvite;
	}
}
/* make output */
$arr_output['cphone'] = array($arrCphone[0],$arrCphone[1],$arrCphone[2]);
?>