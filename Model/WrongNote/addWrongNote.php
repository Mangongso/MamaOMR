<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];
$intTestSeq = $_POST['test_seq']?$_POST['test_seq']:$_GET['test_seq'];
$strCheckQuestions = $_POST['check_questions'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);

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
	$intBbsSeq = 6;
	$arrTestResult = $objTest->gettest($intTestSeq);
	//get student test status from test_join_user
	$arrUserTestStatus = $objTest->gettestJoinUser($intMemberSeq,$intTestSeq);
	//record 오답노트로 버튼 클릭시
	if($strCheckQuestions){
		$arrWrongAnswer = $objAnswer->getUserWrongAnswerIntest($intMemberSeq,$intTestSeq,$strCheckQuestions);
	}else{
		$arrWrongAnswer = $objAnswer->getUserWrongAnswerIntest($intMemberSeq,$intTestSeq);
	}
	$arrQuestionSeq = array();
	foreach($arrWrongAnswer as $intKey=>$arrResult){
		$arrWrongAnswer[$intKey]['question'] = $objQuestion->getQuestion($arrResult['question_seq'],$intTestSeq,$arrTestResult[0]['example_numbering_style']);
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
		$arrWrongAnswer[$intKey]['record'] = $objRecord->testUserRecordByRecordSeq($arrResult['record_seq'],$intTestSeq,$intMemberSeq);
	}
	include(CONTROLLER_NAME."/Common/include/getCategory.php");
}else{
	//go wrong note list
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/student/wrong_answer_note/?err_code='.$err_code.'&teacher_name='.$arrMyTeacherInfo[0]['name']);
	exit;
}

/* make output */
$arr_output['test'] = $arrTestResult;
$arr_output['user_test_status'] = $arrUserTestStatus;
$arr_output['user_wrong_answer'] = $arrWrongAnswer;
if($arr_output['user_test_status'][0]['test_status_flg']!=3 && !count($arrWrongAnswer)){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/student/test/test_detail?test_seq='.$intTestSeq);
	exit;	
}
?>