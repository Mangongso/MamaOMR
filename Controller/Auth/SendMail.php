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

				$strMemo = '<!doctype html>';
				$strMemo .= '<html>';
				$strMemo .= '<head>';
				$strMemo .= '<meta charset="utf-8">';
				$strMemo .= '<title>'.$arrMember[0]['name'].'님이 MamaOMR 학습매니저 등록을 요청 하셨습니다.</title>';
				$strMemo .= '</head>';
				$strMemo .= '<div style="padding: 20px;">';
				$strMemo .= '<div style="text-align: center; border-bottom: 1px solid #e2e2e2; margin-bottom: 20px;">';
				$strMemo .= '<a href="https://github.com/Mangongso/MamaOMR" title="MamaOMR Home"	target="_blank">';
				$strMemo .= '<img src="https://github.com/Mangongso/MamaOMR/raw/ziman/Docs/Images/mamaomr.png?raw=true" alt="MamaOMR Home" style="height: 80px;" /></a>';
				$strMemo .= '</div>';
				$strMemo .= '<h1 style="font-size: 15px; font-weight: bold; color: #333;">'.$arrMember[0]['name'].'님이 학습 매니저 등록을 요청 하였습니다!</h1>';
				$strMemo .= '<p style="font-size: 12px; color: #666;">아래 링크를 클릭하시면 마마OMR에 로그인 하여 학생 학습 기록을 열람하실 수 있습니다.</p>';
				$strMemo .= '<a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/smart_omr/" target="_blank" title="로그인 하기">로그인 하기</a>';
				$strMemo .= '<div style="text-align: center; border-top: 1px solid #e2e2e2; margin-top: 20px;">';
				$strMemo .= '<p style="font-size: 9px; color: #bbb;">';
				$strMemo .= 'Copyright © mamaomr All rights reserved. powered by ';
				$strMemo .= '<a href="https://github.com/Mangongso/MamaOMR" title="MamaOMR Home" target="_blank">MamaOMR</a>';
				$strMemo .= '</p>';
				$strMemo .= '</div>';
				$strMemo .= '</div>';
				$strMemo .= '<body>';
				$strMemo .= '</body>';
				$strMemo .= '</html>';
				$boolResult = $objMailHandler->sendMail($strFromEmail,$strFromName,$strTargetEmail,$strTargetName,$subject,$strMemo);
				$strMessage = '전송되었습니다. 학습매니저가 메일 확인 후 로그인하면 학습매니저 등록이 완료 됩니다.';
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