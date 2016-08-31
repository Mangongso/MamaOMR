<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');

/* set variable */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_REQUEST['view']=='manager'?$_REQUEST['ms']:$_SESSION['smart_omr']['member_key'];//student seq
$strTestSeq = $_REQUEST['t'];
$intRevisionFlg = $_REQUEST['revision'];


/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);

/*main process*/	
//get sruvey info
$arrTestResult = $objTest->getTests($strTestSeq,$intWriterSeq,true);
$arrQuestionList = $objTest->getTestQuestionListWithExample($arrTestResult[0]['seq'],false,array(1,2,3,4,5,6,7,8,9,11),$arrTestResult[0]['example_numbering_style']);

//1. get book info isbn code
$arrSearch = array();
$arrSearch['seq'] = $arrTestResult[0]['publish'][0]['book_seq'];
$arrBookInfo = $objBook->getBook($arrSearch);

$arrTestListByBook = $objBook->getTestListByBook(md5($arrBookInfo[0]['seq']));

//get book's question count 
$intQuestionCnt = $objQuestion->getQuestionCountInTest($arrTestResult[0]['seq']);
//get book's record
$arrUserTotalRecord = $objRecord->getTotalUserRecord($arrTestResult[0]['seq']);
//$arrUserRecord = $objRecord->getLastRecord($strMemberSeq,$arrTestResult[0]['seq']);
if($intRevisionFlg){
	$arrUserRecord = $objRecord->getMemberRecords($strMemberSeq,$arrTestResult[0]['seq'],$intRevisionFlg);
}else{
	$arrUserRecord = $objRecord->getMemberRecords($strMemberSeq,$arrTestResult[0]['seq'],null,1);
}
$arrUserRecordByTass = $objRecord->getTestsRecordReportByTags($arrTestResult[0]['seq'],$arrUserRecord[0]['seq']);
//get user answer
$arrUserAnswer = $objAnswer->getUserAnswer($arrUserRecord[0]['user_seq'],$arrTestResult[0]['seq'],null,$arrUserRecord[0]['seq'],array(1,2,3,4,5,6,7,8,9,11));

// get wrong note
$arrSearch = array('record_seq'=>$arrUserRecord[0]['seq']);
$arrWrongNoteList = $objWrongNote->getWrongAnswerNoteFromTest($arrTestResult[0]['seq'],$arrUserRecord[0]['seq'],$arrUserRecord[0]['user_seq']);

/* make output */
$arr_output['book_info'] = $arrBookInfo;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/default_cover.png";
$arr_output['test_info'] = $arrTestResult;
$arr_output['test_question_list'] = $arrQuestionList;
$arr_output['question_cnt'] = $intQuestionCnt;
$arr_output['user_record'] = $arrUserTotalRecord;
$arr_output['record'] = $arrUserRecord;
$arr_output['record_tags'] = $arrUserRecordByTass;
$arr_output['user_score_avarage'] = $arrUserTotalRecord[0]['total_user_score']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
$arr_output['user_answer'] = $arrUserAnswer;
$arr_output['str_test_seq'] = $strTestSeq;
$arr_output['wrong_answer'] = $arrWrongNoteList;

/*
echo "<pre style='margin-left:400px;'>";
var_dump($strMemberSeq);
var_dump($intRevisionFlg);
var_dump($arrUserRecord);
print_r($arr_output);
echo "</pre>";
*/
?>