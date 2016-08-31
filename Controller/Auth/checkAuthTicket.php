<?
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/Report.php');
require_once('Model/ManGong/Ticket.php');
require_once('Model/Member/Member.php');

//set variable check
if(!$intMemberSeq){
	$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
}
$intTestSeq = $intTestSeq?$intTestSeq:$_POST['test_seq'];
$intReportSeq = $intReportSeq?$intReportSeq:$_POST['report_seq'];
$intAjaxFlg = $_POST['ajax_flg'];

//DB check
if(!$resMangongDB){
	$resMangongDB = new DB_manager('MAIN_SERVER');
}
if(!$objTest){
	$objTest = new Test($resMangongDB);
}
if(!$objReport){
	$objReport = new Report($resMangongDB);
}
if(!$objTicket){
	$objTicket = new Ticket($resMangongDB);
}
if(!$objMember){
	$objMember = new Member($resMangongDB);
}

/* main process */
/* check is my content */
if($intReportSeq){
	$arrMyContent = $objReport->checkMyReport($intReportSeq, $intMemberSeq, 'array');
	$arrMyTeacherInfo = $objMember->getMemberByMemberSeq($arrMyContent[0]['writer_seq']);
}else if($intTestSeq){
	$arrMyContent = $objTest->checkMySurvey($intTestSeq, $intMemberSeq, 'array');
	$arrMyTeacherInfo = $objMember->getMemberByMemberSeq($arrMyContent[0]['writer_seq']);
}

if(count($arrMyContent)){
	//check applied ticket useable 
	$intTeacherSeq = $arrMyTeacherInfo[0]['member_seq'];
	$arrAppliedTicket = $objTicket->getAppliedTicketToStudent($intMemberSeq,0,$intTeacherSeq);
	
	if(!count($arrAppliedTicket)){
		$boolAppliedTicket=false;
		$err_code = TICKET_EMPTY;
	}else if($arrAppliedTicket[0]['ticket_status']==3){
		$boolAppliedTicket=false;
		$err_code = TICKET_STOP;
	}else if(($arrAppliedTicket[0]['ticket_status']==2 || $arrAppliedTicket[0]['ticket_status']==4) && strtotime($arrAppliedTicket[0]['expiration_date'])>strtotime(date('Y-m-d H:i:s'))){
		$boolAppliedTicket=true;
		$err_code = TICKET_USE_ABLE;
	}else if(($arrAppliedTicket[0]['ticket_status']==2 || $arrAppliedTicket[0]['ticket_status']==4) && strtotime($arrAppliedTicket[0]['expiration_date'])<strtotime(date('Y-m-d H:i:s'))){
		$boolAppliedTicket=false;
		$err_code = TICKET_PERIOD_OVER;
	}else{
		$boolAppliedTicket=false;
		$err_code = TICKET_DISABLED;
	}
}else{
	$boolAppliedTicket = false;
	$err_code=NOT_MY_REPORT;
}

/* make output */
if($intAjaxFlg){
	$arrResult = array(
			'bool_apply_ticket'=>$boolAppliedTicket,
			'err_code'=>$err_code,
			'teacher_name'=>$arrMyTeacherInfo[0]['name']
	);
	echo json_encode($arrResult);
}
?>