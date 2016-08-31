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
$strNoteTitle = $_POST['note_title'];
$arrNoteList = $_POST['note'];
$arrQuestions = $_POST['question'];
$arrExamples = $_POST['example'];
$intTestSeq = $_POST['test_seq'];
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

/* check is my test */
include(CONTROLLER_NAME."/Auth/checkAuthTicket.php");
if($boolAppliedTicket){
	//select wrong note checked list
	foreach($arrNoteList as $intKey=>$arrResult){
		if(!$arrResult['check']){
			unset($arrNoteList[$intKey]);
		}
	}  
	
	// set wrong note
	$mixNoteSeq = $objWrongNote->setWrongNote($intUserSeq,$strNoteTitle);
	if($mixNoteSeq){
		$boolResult = $objWrongNote->setWrongNoteList($intUserSeq, $mixNoteSeq, $arrNoteList);
	}else{
		$boolResult = $mixNoteSeq;
	}
	
	// update question info
	foreach($arrQuestions as $intQuestionSeq=>$strQuestion){
		$boolResult = $objQuestion->updateQuestionByStudent($intQuestionSeq, $strQuestion);
		if($boolResult){
			if(count($arrExamples[$intQuestionSeq])>0){
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
}else{
	$boolResult = false;
}

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult,
		'bool_apply_ticket'=>$boolAppliedTicket,
		'err_code'=>$err_code,
		'teacher_name'=>$arrMyTeacherInfo[0]['name']
);
echo json_encode($arrResult);
?>