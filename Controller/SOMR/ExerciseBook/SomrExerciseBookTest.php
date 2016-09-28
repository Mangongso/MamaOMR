<?
/**
 * @Controller Book 테스트 조회
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Record
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');


/**
 * Variable 세팅
 * @var 	$intWriterSeq		유저 시컨즈
 * @var 	$strTestSeq			암호화 테스트 시컨즈
 */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strTestSeq = $_REQUEST['t'];


/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objRecord 					: Record 객체
 * 
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);

/**
 * Main Process
 */	
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
$arrUserRecord = $objRecord->getTotalUserRecord($arrTestResult[0]['seq']);


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
 * @property	integer 		$arr_output['user_score_avarage']		: 유저 평균 점수
 * 
 */
$arr_output['book_info'] = $arrBookInfo;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/no_cover.png";
$arr_output['test_info'] = $arrTestResult;
$arr_output['test_question_list'] = $arrQuestionList;
$arr_output['question_cnt'] = $intQuestionCnt;
$arr_output['user_record'] = $arrUserRecord;
$arr_output['user_score_avarage'] = $arrUserRecord[0]['total_user_score']?round($arrUserRecord[0]['total_user_score']/$arrUserRecord[0]['user_count'],1):0;
?>