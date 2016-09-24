<?php
/**
 * @Controller 메일 발송 컨트롤러
 * @package      	Mangong/Tag
 * @package      	Mangong/StudentMG
 * @subpackage   	Core/DBmanager/DBmanager
 * @subpackage   	Core/Mail/MailHandler
 */
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once("Model/Core/Mail/MailHandler.php");
require_once('Model/Member/Member.php');
require_once('Model/ManGong/StudentMG.php');

/**
 * Variable 세팅
 * @var 	$strMemberSeq md5암호화 유저 시컨즈
 * @var 	$strSenderEmail 발송자 이메일
 * @var 	$strReceiverEmail 수신자 이메일
 * @var 	$strContents 메일내용
 * @var 	$strMailType 메일 형식
 */
$strMemberSeq = $_SESSION['smart_omr']['member_key'];//student seq
$strSenderEmail = $_POST['sender_email'];
$strReceiverEmail = $_POST['receiver_email'];
$strContents = $_POST['contents'];
$strMailType = $_POST['mail_type'];


/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objMailHandler 	: MailHandel 객체
 * @property	object			$objMember  		: Member 객체
 * @property	object 		$objStudentMG 		: StudentMG 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objMailHandler = new MailHandler();
$objMember = new Member($resMangongDB);
$objStudentMG = new StudentMG($resMangongDB);

/**
 * Main Process
 */
switch($strMailType){
	/**
	 * 학습 매니져 요청 메일 
	 * @property	string	$strMailType이 send_manager_request 일경우
	 * */
	case('send_manager_request'):
		$arrMember = $objMember->getMemberByMemberSeq($strMemberSeq);
		if($strReceiverEmail && count($arrMember)){
			$strAuthKey = md5(uniqid());
			$boolResult = $objStudentMG->setManagerStudentAuthKey($arrMember[0]['member_seq'],$strAuthKey); 
			if($boolResult){
				$strFromEmail = 'han@mangongso.com';
				$strFromName = '마마OMR';
				$strTargetEmail = $strReceiverEmail;
				$strTargetName = $strReceiverEmail;
				$subject = '마마OMR 학습매니저요청메일 입니다.';
				$strMemo = '안녕하세요~ 스마트한 학습매니저 마마OMR입니다. <br>'.$arrMember[0]['name'].'님이 <a href="http://www.mangongso.com/smart_omr/" target="_blank">마마OMR</a> 학습매니저로 요청을 하였습니다. <br>아래 링크를 클릭하시고 로그인해 주시면 학습매니저로 등록이 완료 됩니다.<br><br><a href="http://www.mangongso.com/smart_omr/?mat='.$strAuthKey.'">http://www.mangongso.com/smart_omr/?mat='.$strAuthKey.'</a><br><br>감사합니다.';
				$boolResult = $objMailHandler->sendMail($strFromEmail,$strFromName,$strTargetEmail,$strTargetName,$subject,$strMemo);
				$strMessage = '전송되었습니다. 학습매니저의 메일 확인 및 마마OMR 등록이 필요합니다.';
			}
		}else{
			$boolResult = false;
		}
	break;
	/**
	 * 마마OMR 문의 메일
	 * @property	string	$strMailType이 send_manager_request가 아닐 경우
	 * */
	default: 
		if($strSenderEmail && $strContents){
			$strFromEmail = $strSenderEmail;
			$strFromName = $strSenderEmail;
			$strTargetEmail = 'han@mangongso.com';
			$strTargetName = '마마OMR';
			$subject = '마마OMR 문의 메일';
			$strMemo = $strSenderEmail.' 님의 마마OMR 문의 내용입니다.';
			$strMemo .= '<br><br>';
			$strMemo .= $strContents;
			$boolResult = $objMailHandler->sendMail($strFromEmail,$strFromName,$strTargetEmail,$strTargetName,$subject,$strMemo);
			$strMessage = '전송되었습니다. 확인하여 답변 드리겠습니다.';
		}else{
			$boolResult = false;
		}
	break;
}


/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 메일 발송 결과 성공 여부
 * @property	string 			$strMessage 		: 발송 결과 메세지
 */
$arrResult = array('result'=>$boolResult,'message'=>$strMessage);
echo json_encode($arrResult);
?>