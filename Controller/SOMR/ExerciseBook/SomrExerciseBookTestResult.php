<?
/**
 * @Controller Book 테스트 결과 조회
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Record
 * @package      	Mangong/MAnswer
 * @package      	Mangong/WrongNote
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/Member/Member.php');
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/StudentMG.php');

/**
 * Variable 세팅
 * @var 	$intWriterSeq		유저 시컨즈
 * @var 	$strTestSeq			암호화 테스트 시컨즈
 * @var 	$strMemberSeq		암호화 유저시컨즈
 * @var 	$intRevisionFlg		리비전 flg
 */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strStudentKey = $_REQUEST['sk'];//student seq
if($strStudentKey){
	$strManagerKey = $_SESSION['smart_omr']['member_key'];//manager seq
}else{
	$strMMemberSeq = $_SESSION['smart_omr']['member_key'];//member seq
}
$strTestSeq = $_REQUEST['t'];
$intRevisionFlg = $_REQUEST['revision'];


/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objRecord 					: Record 객체
 * @property	object 		$objAnswer 					: MAnswer 객체
 * @property	object 		$objWrongNote 					: WrongNote 객체
 * 
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objStudentMG = new StudentMG($resMangongDB);
$objMember = new Member($resMangongDB);

/**
 * Main Process
 */	
if(trim($strStudentKey) && !$objStudentMG->checkIsManager($strStudentKey, $strManagerKey)){
	header("location:/");
	exit;
}else{
	$strMemberSeq = $strStudentKey;
	$arrStudentInfo = $objMember->getMemberByMemberSeq($strMemberSeq);
}
//get sruvey info
$arrTestResult = $objTest->getTests($strTestSeq,$intWriterSeq,true);
$arrQuestionList = $objTest->getTestQuestionListWithExample($arrTestResult[0]['seq'],false,array(1,2,3,4,5,6,7,8,9,11),$arrTestResult[0]['example_numbering_style']);

//1. get book info isbn code
$arrSearch = array();
$arrSearch['seq'] = $intBookSeq = $arrTestResult[0]['publish'][0]['book_seq'];
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


$arrWrongQuestionList = array();
$arrQuestionAnswer = array();
foreach($arrQuestionList as $intKey=>$arrResult){
	
	/* set quesiton answer */
	foreach($arrResult['example']['type_1'] as $intSubKey=>$arrSubResult){
		// set question answer
		switch($arrResult['question_type']){
			case(1):
			case(2):
			case(3):
			case(4):
			case(11):
				//object answer
				if($arrSubResult['answer_flg']==1){
					//$arrQuestionAnswer[$arrResult['question_seq']] = $arrSubResult['seq'];
					$mixAnswer = $arrSubResult['seq'];
				}
				break;
			default:
				// 1.subject answer
				if(count($arrResult['example']['type_1'])==($intSubKey+1)){
					$mixAnswer .= $arrSubResult['subjective_answer'];
					//$arrQuestionAnswer[$arrResult['question_seq']] = $strSubjectAnswer;
				}else{
					$mixAnswer .= $arrSubResult['subjective_answer']."|";
				}
				break;
		}
	}
	$arrQuestionAnswer[$arrResult['question_seq']] = $mixAnswer?$mixAnswer:'';
	//set wrong question 
	if(!$arrUserAnswer[$intKey]['result_flg']){
		$arrWrongQuestionList[$arrResult['question_seq']] = $arrQuestionList[$intKey];
	}
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	array 			$arr_output['book_info']  					: book 정보
 * @property	string 			$arr_output['book_cover_img'] 			: 커버 이미지 url
 * @property	string 			$arr_output['test_info'] 						: 테스트 정보
 * @property	array 			$arr_output['test_question_list'] 			: 테스트 문제 목록 
 * @property	integer 		$arr_output['question_cnt']				: 문항수
 * @property	array 			$arr_output['user_record'] 				: 유저 성적
 * @property	array 			$arr_output['record'] 						: 성적
 * @property	array 			$arr_output['record_tags'] 					: 성적태그
 * @property	integer 		$arr_output['user_score_avarage']		: 유저 평균 점수
 * @property	array 			$arr_output['user_answer'] 				: 유저 선택 답
 * @property	array 			$arr_output['str_test_seq'] 					: 암호화 테스트 시컨즈
 * @property	array 			$arr_output['wrong_answer'] 				: 오답
 * @property	array 			$arr_output['question_answer'] 			: 문제 정답
 * @property	array 			$arr_output['wrong_questions'] 			: 틀린 문제
 * @property	array 			$arr_output['book_seq'] 					: book  시컨즈
 * 
 */
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
$arr_output['question_answer'] = $arrQuestionAnswer;
$arr_output['wrong_questions'] = $arrWrongQuestionList;
$arr_output['book_seq'] = $intBookSeq;
$arr_output['student_info'] = $arrStudentInfo;
?>