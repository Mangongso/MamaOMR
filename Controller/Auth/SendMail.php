<?php
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once("Model/Core/Mail/MailHandler.php");
require_once('Model/Member/Member.php');
require_once('Model/ManGong/StudentMG.php');

/* set variable */
$strMemberSeq = $_SESSION['smart_omr']['member_key'];//student seq
$strSenderEmail = $_POST['sender_email'];
$strReceiverEmail = $_POST['receiver_email'];
$strContents = $_POST['contents'];
$strMailType = $_POST['mail_type'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objMailHandler = new MailHandler();
$objMember = new Member($resMangongDB);
$objStudentMG = new StudentMG($resMangongDB);

/* main process */
switch($strMailType){
	case('send_manager_request'):
		//get member info 
		$arrMember = $objMember->getMemberByMemberSeq($strMemberSeq);
		if($strReceiverEmail && count($arrMember)){
			$strAuthKey = md5(uniqid());
			//set manager_student auth key
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
	default: 
		if($strSenderEmail && $strContents){
			$strFromEmail = $strSenderEmail;
			$strFromName = $strSenderEmail;
			$strTargetEmail = 'han@mangongso.com';
			//$strTargetEmail = 'inkuk.yang@hanbnc.com';
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

/* make output */
$arrResult = array('result'=>$boolResult,'message'=>$strMessage);
echo json_encode($arrResult);
?>