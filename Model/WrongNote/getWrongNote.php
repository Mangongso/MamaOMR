<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/Record.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intNoteSeq = $_POST['note_seq'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objRecord = new Record($resMangongDB);

/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth

if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}

//get select question
$arrWrongAnswer = $objWrongNote->getWrongAnswer($intMemberSeq,$intNoteSeq);
$arrTestResult = $objTest->gettest($arrWrongAnswer[0]['test_seq']);

//get student test status from test_join_user
$arrUserTestStatus = $objTest->gettestJoinUser($intMemberSeq,$arrWrongAnswer[0]['test_seq']);
$arrQuestionSeq = array();
foreach($arrWrongAnswer as $intKey=>$arrResult){
	$arrWrongAnswer[$intKey]['question'] = $objQuestion->getQuestion($arrResult['question_seq'],$arrWrongAnswer[0]['test_seq'],$arrTestResult[0]['example_numbering_style']);
	if($arrWrongAnswer[$intKey]['question'][0]['question_type']<5){
		foreach($arrWrongAnswer[$intKey]['question'][0]['arr_question_example']['type_1'] as $intExampleKey=>$arrExampleResult){
			if($arrExampleResult['seq']==$arrResult['user_answer']){
				$arrWrongAnswer[$intKey]['user_answer_text'] = $arrExampleResult['contents'];
				break;
			}
		}
	}else{
		$arrJsonDummy = json_decode($arrResult['user_answer'],true);
		foreach($arrWrongAnswer[$intKey]['question'][0]['arr_question_example']['type_1'] as $intExampleKey=>$arrExampleResult){
			$arrWrongAnswer[$intKey]['question'][0]['arr_question_example']['type_1'][$intExampleKey]['user_answer_text'] = $arrJsonDummy[$arrExampleResult['seq']];
		}		
	}
	$arrWrongAnswer[$intKey]['record'] = $objRecord->testUserRecordByRecordSeq($arrResult['record_seq'],$arrWrongAnswer[0]['test_seq'],$intMemberSeq);
}

/* make output */
$arr_output['test'] = $arrTestResult;
$arr_output['user_test_status'] = $arrUserTestStatus;
$arr_output['user_wrong_answer'] = $arrWrongAnswer;
?>