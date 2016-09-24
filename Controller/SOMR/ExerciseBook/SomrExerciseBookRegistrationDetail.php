<?
/**
 * @Controller Book 등록 상세
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
require_once('Model/ManGong/Teacher.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');


/**
 * Variable 세팅
 * @var 	$intWriterSeq		유저 시컨즈
 * @var 	$strBookSeq			암호화 book 시컨즈
 */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strBookSeq = $_REQUEST['bs'];


/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objRecord 					: Record 객체
 * @property	object 		$objTeacher 					: Teacher 객체
 * 
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTeacher = new Teacher($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);

/**
 * Main Process
 */	
//1. get book info isbn code
$arrSearch = array();
$arrSearch['md5(seq)'] = $strBookSeq;
$arrBook = $objBook->getBook($arrSearch);
$arrResult[0]['writer_info'] = $objTeacher->getTeacher($intWriterSeq);
//get all test info 
$arrTestListByBook = $objBook->getTestListByBook($strBookSeq);

//set arr survey seq
$arrTestsSeq = array();
foreach ($arrTestListByBook as $key => $arrResult) {
	//set all survey seq
	array_push($arrTestsSeq, $arrResult['test_seq']);
	
	//set test count
	$arrTestListByBook[$key]['test_question_cnt'] = $objQuestion->getQuestionCountInTest($arrResult['test_seq']);
	$arrTestListByBook[$key]['test_record'] = $objRecord->getTotalUserRecord($arrResult['test_seq']);
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
 * OutPut Type Json
 * 
 * @property	array 			$arr_output['book_info']  					: book 정보
 * @property	string 			$arr_output['book_cover_img'] 			: 커버 이미지 url
 * @property	array 			$arr_output['book_test_list'] 				: 테스트 리스트 
 * @property	integer 		$arr_output['book_total_question_cnt']	: 총문항수
 * @property	array 			$arr_output['book_user_total_record'] 	: 총 성적
 * @property	integer 		$arr_output['book_join_count']			: 참여자수
 * @property	integer 		$arr_output['book_score_avarage']		: 평균점수
 * 
 */
$arr_output['book_info'] = $arrBook;
$arr_output['book_test_list'] = $arrTestListByBook;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/default_cover.png";
//$arr_output['book_join_usre_count'] = $intTestJoinUserCount;
$arr_output['book_total_question_cnt'] = $intBookQuestionCnt;
$arr_output['book_user_total_record'] = $arrUserTotalRecord;
$arr_output['book_join_count'] = $arrUserTotalRecord[0]['user_count']?$arrUserTotalRecord[0]['user_count']:0;
$arr_output['book_score_avarage'] = $arrUserTotalRecord[0]['user_count']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
?>