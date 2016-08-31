<?
/*
 * smart_omr auth 
 * */
if($_SESSION['smart_omr']){
	
	$intAuthFlg = AUTH_TRUE;
	$GLOBALS['GLOBAL_APP_NAME'] = 'SMART_OMR';
	$intMemberSeq = $intMemberSeq?$intMemberSeq:SMART_OMR_TEACHER_SEQ;
}
else{
/*
 * mangong auth start
 * */	
	
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/Member/Member.php');
require_once('Model/ManGong/Ticket.php');

//set variable check
if(!$intMemberSeq){
	$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
}
if(!$strAuthKey){
	$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];
}
//check url student or teacher variable
$arrRequestUrl = explode("/",$_SERVER['REQUEST_URI']);
$boolSTFlg = true;
switch($arrRequestUrl[1]){
	case('student'):
		$strRequestUrlType = "S";
		require_once('Model/ManGong/Profile.php');
		if(!$objProfile){
			$objProfile = new Profile($resMangongDB);
		}
		$arrAuthMemberProfile = $objProfile->getStudentProfileInfo($intMemberSeq);
		break;
	case('teacher'):
		$strRequestUrlType = "T";
		require_once('Model/ManGong/Profile.php');
		if(!$objProfile){
			$objProfile = new Profile($resMangongDB);
		}
		$arrAuthMemberProfile = $objProfile->getTeacherProfileInfo($intMemberSeq);
		break;
	default:
		$strRequestUrlType = "F";
		$boolSTFlg = false;
		break;
		
}
//auto login variable
$strAutoLoginToken = $_COOKIE[md5('autoLoginToken')];

//DB check
if(!$resMangongDB){
	$resMangongDB = new DB_manager('MAIN_SERVER');
}
if(!$objAuth){
	$objAuth = new Auth($resMangongDB);
}
if(!$objMember){
	$objMember = new Member($resMangongDB);
}
if(!$objTicket){
	$objTicket = new Ticket($resMangongDB);
}

/* main process */
if($intMemberSeq && $strAuthKey){
	$arrMember = $objAuth->getMemberByAuthKey($intMemberSeq,$strAuthKey);
}else if($strAutoLoginToken){
	$arrMember = $objAuth->getMemberByIPAddress($_SERVER['REMOTE_ADDR'],$strAutoLoginToken);
	if($arrMember && count($arrMember)>0){
		$strAuthKey = $arrMember[0]['auth_key'];
		$boolAutoLogin = true;
	}
}
if($arrMember && count($arrMember)>0){
	/* login duplication check */
	if($arrMember[0]['auth_key'] && $arrMember[0]['auth_key']==$strAuthKey){
		//member_type S -> only access '/student', 'T' -> only access '/teacher'
		if(($strRequestUrlType != $arrMember[0]['member_type']) && $boolSTFlg){
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
			$intAuthFlg = AUTH_TRUE;
			//if auto loginflg true -> set login process
			if($boolAutoLogin){
				//update auth key 
				$strAuthKey = $objMember->updateAuthKey($resMangongDB,$arrMember[0]['member_seq']);
				//set auth key
				$strMemberToken = md5($arrMember[0]['email'].$arrMember[0]['cphone'].$_SERVER['REMOTE_ADDR'].time());
				setcookie("member_token", $strMemberToken, time()+60*60*24*30, '/');
				$_SESSION[$strMemberToken] = array(
					'auth_key'=>$strAuthKey,
					'member_seq'=>$arrMember[0]['member_seq'],
					'nickname'=>$arrMember[0]['nickname'],
					'name'=>$arrMember[0]['name'],
					'member_type'=>$arrMember[0]['member_type']
				);
				//set cookie 
				$strAutoLoginToken = md5($strAuthKey);
				setcookie(md5('autoLoginToken'), $strAutoLoginToken, time()+60*60*24*30, '/');
				//set mg_select_teacher cookie
				if($arrMember[0]['member_type']=="S"){
					
				}
			}
			//check mg_select_teacher cookie
			if($arrMember[0]['member_type']=="S" && !is_null($_POST['mg_select_teacher'])){
				// $_COOKIE['mg_select_teacher'] = $_POST['mg_select_teacher']?md5($_POST['mg_select_teacher']):0;
				$_COOKIE['mg_select_teacher'] = $strMbSelectTeacher = $_POST['mg_select_teacher']?md5($_POST['mg_select_teacher']):0;
				setcookie("mg_select_teacher",$strMbSelectTeacher, time()+60*60*24*30, '/');
			}
		}
	}else if($arrMember[0]['auth_key'] && $arrMember[0]['auth_key']!=$strAuthKey){
		$intAuthFlg = AUTH_MEMBER_DUPLICATION;
	}else{
		$intAuthFlg = AUTH_TOKEN_EMPTY;
	}
	if($intAuthFlg!=AUTH_TRUE){
		session_unset(); //세션변수 초기화
		session_destroy(); //세션 파괴
		setcookie("member_token", "", time()-3600, '/');
		setcookie($strAutoLoginToken, "", time()-3600, '/');
		setcookie("mg_select_teacher", "", time()-3600, '/');
	}
}else{
	$intAuthFlg = AUTH_MEMBER_EMPTY;
}

/*
//check trial member
if($arrMember[0]['member_type']=='T'){
	$checkPeriodResult = $objAuth->checkMemberPeriod($arrMember[0]['member_seq']);
	//check auth
	if($checkPeriodResult!=MEMBER_POLICY_COMPLETE){
		session_unset(); //세션변수 초기화
		session_destroy(); //세션 파괴
		setcookie("member_token", "", time()-3600, '/');
		setcookie($strAutoLoginToken, "", time()-3600, '/');
		setcookie("mg_select_teacher", "", time()-3600, '/');
		
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/login?action='.$checkPeriodResult);
		exit;
	}
}
*/

//check student from to teacher
if($_SESSION[$_COOKIE['member_token']]['teacher_seq']){
	$_COOKIE['mg_select_teacher'] = $strMbSelectTeacher = md5($_SESSION[$_COOKIE['member_token']]['teacher_seq']);
	setcookie("mg_select_teacher",$strMbSelectTeacher, time()+60*60*24*30, '/');
} 

//get my ticket count()
if($_SESSION[$_COOKIE['member_token']]['member_type']=="T"){
	$arr_output['my_ticket_count'] = $objTicket->getTicketsCount($intMemberSeq,0,0);
}

//print "<pre>";
//var_dump($_COOKIE);
//var_dump($_POST);
//print "</pre>";
/* make arr_output */
//$arr_output['auth_flg'] = $intAuthFlg;
$arr_output['auth_member_profile'] = $arrAuthMemberProfile;


/*
 * mangong auth end
 * */
}

?>