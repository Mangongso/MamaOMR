<?
/**
 * @Controller 마이페이지 참여 책 상세 목록
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Record
 * @package      	Mangong/Teacher
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/Teacher.php');


/**
 * Variable 세팅
 * @var 	$intWriterSeq		마마OMR 마스터 시컨즈
 * @var 	$strMemberSeq		암호화 유저 시컨즈 
 * @var 	$strBookSeq		암호화 책 시컨즈 
 */
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_REQUEST['view']=='manager'?$_REQUEST['ms']:$_SESSION['smart_omr']['member_key'];//student seq
$strBookSeq = $_REQUEST['bs'];


/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objBook 					: Book 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object			$objRecord  				: Record 객체
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objTeacher 					: Teacher 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objTeacher = new Teacher($resMangongDB);

 /**
 * Main Process
 */
	
//1. get book info isbn code
$arrSearch = array();
$arrSearch['md5(seq)'] = $strBookSeq;
$arrBook = $objBook->getBook($arrSearch);
$arrBook[0]['writer_info'] = $objTeacher->getTeacher($intWriterSeq);

//get all test info 
$arrTestListByBook = $objBook->getTestListByBook($strBookSeq);

//set arr survey seq
$arrTestsSeq = array();
foreach ($arrTestListByBook as $key => $arrResult) {
	//set all survey seq
	array_push($arrTestsSeq, $arrResult['test_seq']);
	
	//get test count
	$arrTestListByBook[$key]['test_question_cnt'] = $objQuestion->getQuestionCountInTest($arrResult['test_seq']);
	$arrTestListByBook[$key]['test_record'] = $objRecord->getTotalUserRecord($arrResult['test_seq']);
	$arrTestRecord = $arrTestListByBook[$key]['test_record'][0];
	$arrTestListByBook[$key]['score_avarage'] = $arrTestRecord['user_count']?round($arrTestRecord['total_user_score']/$arrTestRecord['user_count'],1):0;
	
	//get my test revision info
	$arrTestListByBook[$key]['my_record_list'] = $objRecord->getMemberRecords($strMemberSeq,$arrResult['test_seq']);
}

if(count($arrTestsSeq)){
	//get book's question count 
	$intBookQuestionCnt = $objQuestion->getQuestionCountInTest(null,$arrTestsSeq);
	//$intTestJoinUserCount = $objTest->getTestsJoinUserTotalCount(null,$arrTestsSeq);
	
	//get book's record
	$arrUserTotalRecord = $objRecord->getTotalUserRecord(null,$arrTestsSeq);
}

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	array 		$arr_output['book_info'] 					: 책 정보
 * @property	string 		$arr_output['book_cover_img'] 			: 책 커버 이미지 url 
 * @property	array 		$arr_output['book_test_list'] 				: 책 테스트 목록
 * @property	integer 	$arr_output['book_total_question_cnt'] 	: 총 문제 개수
 * @property	array 		$arr_output['book_user_total_record'] 	: 유저 성적 정보
 * @property	integer 	$arr_output['book_join_count'] 			: 책 참여자 수
 * @property	integer 	$arr_output['book_score_avarage'] 		: 책 평균 점수
 */
$arr_output['book_info'] = $arrBook;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/default_cover.png";
$arr_output['book_test_list'] = $arrTestListByBook;
$arr_output['book_total_question_cnt'] = $intBookQuestionCnt;
$arr_output['book_user_total_record'] = $arrUserTotalRecord;
$arr_output['book_join_count'] = $arrUserTotalRecord[0]['user_count']?$arrUserTotalRecord[0]['user_count']:0;
$arr_output['book_score_avarage'] = $arrUserTotalRecord[0]['user_count']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
?>