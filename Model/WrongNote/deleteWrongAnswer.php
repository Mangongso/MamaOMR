<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/Ticket.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];
$intNoteSeq = $_POST['note_seq'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objWrongNote = new WrongNote($resMangongDB);
$objTicket = new Ticket($resMangongDB);

/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth

if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}

//check applied ticket useable 
$arrAppliedTicket = $objTicket->getAppliedTicket($intMemberSeq, $strMemberType);

if(!count($arrAppliedTicket)){
	$boolTestStartFlg=false;
	$err_code = TICKET_EMPTY;
}else if($arrAppliedTicket[0]['ticket_status']==3){
	$boolTestStartFlg=false;
	$err_code = TICKET_STOP;
}else if(($arrAppliedTicket[0]['ticket_status']==2 || $arrAppliedTicket[0]['ticket_status']==4) && strtotime($arrAppliedTicket[0]['expiration_date'])>strtotime(date('Y-m-d H:i:s'))){
	$boolTestStartFlg=true;
	$err_code = TICKET_USE_ABLE;
}else if(($arrAppliedTicket[0]['ticket_status']==2 || $arrAppliedTicket[0]['ticket_status']==4) && strtotime($arrAppliedTicket[0]['expiration_date'])<strtotime(date('Y-m-d H:i:s'))){
	$boolTestStartFlg=false;
	$err_code = TICKET_PERIOD_OVER;
}else{
	$boolTestStartFlg=false;
	$err_code = TICKET_DISABLED;
}

$boolResult = $objWrongNote->deleteWrongNoteList($intMemberSeq, $intNoteSeq);

/* make output */
$arrResult = array(
		'boolTestStartFlg'=>$boolTestStartFlg,
		'err_code'=>$err_code,
		'boolResult'=>$boolResult
);
echo json_encode($arrResult);
?>