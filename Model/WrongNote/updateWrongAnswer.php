<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/Ticket.php');

/* set variable */ 
$arrNoteList = $_POST['note'];
$intNoteSeq = $_POST['note_seq'];
$arrQuestions = $_POST['question'];
$arrExamples = $_POST['example'];
$inttestSeq = $_POST['test_seq'];
$intRecordSeq = $_POST['record_seq'];
$intUserSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strUserType = $_SESSION[$_COOKIE['member_token']]['member_type'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
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
$arrAppliedTicket = $objTicket->getAppliedTicket($intUserSeq, $strUserType);

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

if($boolTestStartFlg){
	// set wrong note
	foreach($arrNoteList as $intKey=>$arrResult){
		$boolResult = $objWrongNote->updateWrongNoteList($arrResult['note_seq'],$intUserSeq,$arrResult['note']);
	}
	
	// update question info
	foreach($arrQuestions as $intQuestionSeq=>$strQuestion){
		$boolResult = $objQuestion->updateQuestionByStudent($intQuestionSeq, $strQuestion);
		if($boolResult){
			if(is_array($arrExamples[$intQuestionSeq])){
				foreach($arrExamples[$intQuestionSeq] as $intExampleKey=>$arrExample){
					$boolResult = $objQuestion->updateQuestionExampleByStudent($intQuestionSeq, $arrExample['seq'], $arrExample['content']);
					if(!$boolResult){
						break;
					}
				}
			}
		}else{
			break;
		}
	}
}


/* make output */
$arrResult = array(
		'boolTestStartFlg'=>$boolTestStartFlg,
		'err_code'=>$err_code,
		'boolResult'=>$boolResult
);
echo json_encode($arrResult);
?>