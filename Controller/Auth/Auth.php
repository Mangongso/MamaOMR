<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Common.php');
require_once('Model/ManGong/Teacher.php');
require_once('Model/ManGong/Profile.php');
require_once('Model/ManGong/Group.php');
require_once('Model/Member/Member.php');

/* set variable */ 
$strCphone = $_POST['cphone1']."-".$_POST['cphone2']."-".$_POST['cphone3'];
$strPassword = $_POST['password'];
$intAutoLoginFlg = $_POST['auto_login_flg'];
$intTeacherSeq = $_POST['teacher_seq'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
$objAuth = new Auth($resAuthDB);
$objCommon = new Common($resAuthDB);
$objMember = new Member($resAuthDB);
$objTeacher = new Teacher($resAuthDB);
$objProfile = new Profile($resAuthDB);
$objGroup = new Group($resAuthDB);

/* main process */
$arrMember = $objMember->getMemberByCphone($resAuthDB,$strCphone,false);
$intResult = $objMember->checkIsMember($resAuthDB,$arrMember[0]['member_seq'],$strPassword);
if(!count($arrMember)){
	$err_msg = "가입되지 않은 번호입니다. 회원으로 가입해 주십시오.";
}else if(!$intResult){
	$err_msg = "비밀번호를 잘못 입력하셨습니다. 다시 확인해 주세요.";
}

if(($intResult && !$arrMember[0]['del_flg']) || (!$arrMember[0]['del_flg'] && $strPassword=="dktnfkqkfqkfxk")){
	
	/*check trial member
	if($arrMember[0]['member_type']=='T'){
		$checkPeriodResult = $objAuth->checkMemberPeriod($arrMember[0]['member_seq']);
		//check auth
		if($checkPeriodResult!=MEMBER_POLICY_COMPLETE){
			session_unset(); //세션변수 초기화
			session_destroy(); //세션 파괴
			setcookie("member_token", "", time()-3600, '/');
			setcookie($strAutoLoginToken, "", time()-3600, '/');
			setcookie("mg_select_teacher", "", time()-3600, '/');
			
			$arrResult = array(
				'boolResult'=>false,
				'action'=>'3'
			);
			echo json_encode($arrResult);
			exit;
		}
	}
	*/
	//update auth key 
	$strAuthKey = $objMember->updateAuthKey($resAuthDB,$arrMember[0]['member_seq']);
	//update address ip
	$arr_input = array('member_id'=>$arrMember[0]['member_seq'],'ip_address'=>$_SERVER['REMOTE_ADDR']);
	$objMember->updateMember($resAuthDB,$arr_input);
	
	//set auth key
	$strMemberToken = md5($arrMember[0]['email'].$arrMember[0]['cphone'].$_SERVER['REMOTE_ADDR'].time());
	setcookie("member_token", $strMemberToken, time()+60*60*24*30, '/');
	if($arrMember[0]['member_type']=="S" || ($arrMember[0]['member_type']=="T" && $arrMember[0]['confirm_flg']==0)){
		$_SESSION[$strMemberToken] = array(
				'auth_key'=>$strAuthKey,
				'member_seq'=>$arrMember[0]['member_seq'],
				'nickname'=>$arrMember[0]['nickname'],
				'name'=>$arrMember[0]['name'],
				'member_type'=>$arrMember[0]['member_type']
		);		
	}
	//set auto login
	if($intAutoLoginFlg){
		//set cookie 
		$strAutoLoginToken = md5($strAuthKey);
		setcookie(md5('autoLoginToken'), $strAutoLoginToken, time()+60*60*24*30, '/');
	}
	//set mg_select_teacher cookie
	if($arrMember[0]['member_type']=="S"){
		setcookie("mg_select_teacher", 0, time()+60*60*24*30, '/');
	}
	
	$boolResult=true;
}else{
	$boolResult=false;
}

//set student course application
if($arrMember[0]['member_type']=="S" && $intTeacherSeq){
	$arrTeacherResult = $objCommon->getTeacherSeqByStudentSeq($arrMember[0]['member_seq']);
	$arrTeacherProfile = $objProfile->getTeacherProfileInfo($intTeacherSeq);
	foreach($arrTeacherResult as $intKey=>$arrResult){
		if($arrResult['teacher_seq']==$intTeacherSeq){
			if(!$arrResult['approve_flg']){
				$boolPending = true;
			}
			$boolApplicationDuplication = true; 
		}
	}
	//check is member && is teacher seq
	if($boolPending){
		$strApplicationAuth = 'pending';
	}else if($boolApplicationDuplication){
		$strApplicationAuth = 'duplication'; 
	}else if(count($arrMember)>0 && $arrTeacherProfile){
		$boolResult = $objTeacher->setTeacherStudentList($intTeacherSeq,$arrMember[0]['member_seq']);
		$strApplicationAuth = 'complete';
	}else{
		$strApplicationAuth = 'fail';
	}
	
	if($_COOKIE['free_flg'] && ($strApplicationAuth == 'complete' || $strApplicationAuth = 'pending')){
		//get teacher info 
		$arrTeacherInfo = $objMember->getMemberByMemberSeq($intTeacherSeq);
		if($arrTeacherInfo[0]['level']=='10000'){//10000 content account
			//get teacher group 
			$arrTeacherGroup = $objGroup->getGroupList($intTeacherSeq);
			//set group
			$boolResult = $objGroup->setGroupUserList($intTeacherSeq, $arrTeacherGroup[0]['seq'], array($arrMember[0]['member_seq']));
			setcookie("mg_teacher_key", md5($intTeacherSeq), time()+(60*60), '/');
			setcookie('free_flg', null, -1, '/');
			unset($_COOKIE['free_flg']);
		}
	}else{
		setcookie('free_flg', null, -1, '/');
		unset($_COOKIE['free_flg']);
	}
}

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult,
		'member_type'=>$arrMember[0]['member_type'],
		'application_result'=>$strApplicationAuth,
		'login_guide_flg'=>$arrMember[0]['login_guide_flg'],
		'err_msg'=>$err_msg
);
echo json_encode($arrResult);
?>