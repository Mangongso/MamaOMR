<?
/* include package */
//require_once("Model/Core/DBmanager/DBmanager.php");
//require_once('Model/Member/Member.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];

/* create object */

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
	$strBasicSelectCookie = $_COOKIE['basic_select'];
	if($strBasicSelectCookie){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/login');
		exit;
	}
}
?>