<?
/**
 * @Controller 오답 노트 저장
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
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');

/**
 * Variable 세팅
 * @var 	$intWriterSeq		마마omr 마스타 시컨즈
 * @var 	$strMemberSeq		암호화 유저 시컨즈
 * @var 	$strTestSeq			암호화 테스트 시컨즈 
 */
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_REQUEST['view']=='manager'?$_REQUEST['ms']:$_SESSION['smart_omr']['member_key'];//student seq
$strTestSeq = $_REQUEST['t'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objBook 					: Book 객체
 * @property	object			$objTest  				: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object			$objRecord  				: Record 객체
 * @property	object 		$objAnswer 					: MAnswer 객체
 * @property	object 		$objWrongNote 					: WrongNote 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);

 /**
 * Main Process
 */
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
$arrUserRecord = $objRecord->getMemberRecords($strMemberSeq,$arrTestResult[0]['seq'],null,1);

// get wrong note
$arrSearch = array('record_seq'=>$arrUserRecord[0]['seq']);
$arrWrongNoteList = $objWrongNote->getWrongAnswerNoteFromTest($arrTestResult[0]['seq'],null,$arrUserRecord[0]['user_seq']);

$arrWrongQuestonSeq = array();
foreach($arrWrongNoteList as $intKey=>$arrResult){
	array_push($arrWrongQuestonSeq,$arrResult['question_seq']);
}

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
	if(in_array($arrResult['question_seq'],$arrWrongQuestonSeq)){
		$arrWrongQuestionList[$arrResult['question_seq']] = $arrQuestionList[$intKey];
	}
}
//echo "<pre>";var_dump($arrWrongQuestionList);echo "<pre>";
//exit;

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	array 		$arr_output['book_info'] 				: 책 정보
 * @property	string 		$arr_output['book_cover_img'] 		: 책 커버 이미지 url 
 * @property	array 		$arr_output['test_info'] 					: 테스트 정보
 * @property	array 		$arr_output['test_question_list'] 		: 테스트 문제 목록
 * @property	array 		$arr_output['question_cnt'] 			: 문제 개수
 * @property	array 		$arr_output['user_record'] 			: 유저 성적
 * @property	integer 	$arr_output['user_score_avarage'] 	: 유저 평균 점수
 * @property	string 		$arr_output['str_test_seq'] 				: 암호화 테스트 시컨즈
 * @property	array 		$arr_output['wrong_answer'] 			: 오답 정보
 * @property	array 		$arr_output['question_answer'] 		: 문제 정답 정보
 * @property	array 		$arr_output['wrong_questions'] 		: 오답 문제 정보
 * @property	integer 	$arr_output['book_seq'] 				: 책 시컨즈
 */
$arr_output['book_info'] = $arrBookInfo;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/no_cover.png";
$arr_output['test_info'] = $arrTestResult;
$arr_output['test_question_list'] = $arrQuestionList;
$arr_output['question_cnt'] = $intQuestionCnt;
$arr_output['user_record'] = $arrUserTotalRecord;
$arr_output['user_score_avarage'] = $arrUserTotalRecord[0]['total_user_score']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
$arr_output['str_test_seq'] = $strTestSeq;
$arr_output['wrong_answer'] = $arrWrongNoteList;
$arr_output['question_answer'] = $arrQuestionAnswer;
$arr_output['wrong_questions'] = $arrWrongQuestionList;
$arr_output['book_seq'] = $intBookSeq;
?>