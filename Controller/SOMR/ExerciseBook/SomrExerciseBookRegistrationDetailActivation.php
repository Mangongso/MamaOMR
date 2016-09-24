<?
/**
 * @Controller Book 상세 정보
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
 * @var 	$strBookSeq			암호화 book 시컨즈
 * @var 	$mixTestSeq			암호화 테스트 시컨즈 또는 테스트 시컨즈
 */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$mixBookSeq = $_REQUEST['book_seq'];
$mixTestSeq = $_REQUEST['test_seq'];


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
	
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/smart_omr');
	exit;
}
//1. get book info isbn code
$arrSearch = array();
if(is_numeric($mixBookSeq)){
	$arrSearch['seq'] = $mixBookSeq;
}else{
	$arrSearch['md5(seq)'] = $mixBookSeq;
}
$arrBookInfo = $objBook->getBook($arrSearch);

$arrTestListByBook = $objBook->getTestListByBook(md5($arrBookInfo[0]['seq']));

//set arr survey seq
$arrTestsSeq = array();
foreach ($arrTestListByBook as $key => $arrResult) {
	//set all survey seq
	array_push($arrTestsSeq, $arrResult['test_seq']);
}
if(count($arrTestsSeq)){
	//get book's question count 
	$intBookQuestionCnt = $objQuestion->getQuestionCountInTest(null,$arrTestsSeq);
	//get book's record
	$arrUserTotalRecord = $objRecord->getTotalUserRecord(null,$arrTestsSeq);
}

//get sruvey info
$arrTestResult = $objTest->getTests($mixTestSeq,$intWriterSeq,true);
$arrQuestionList = $objTest->getTestQuestionListWithExample($arrTestResult[0]['seq'],false,array(1,2,3,4,5,6,7,8,9,11),$arrTestResult[0]['example_numbering_style']);

$intTotalScore = 0;
$intQuestionTotalCnt = 0;
foreach($arrQuestionList as $intKey=>$arrResult){
	$intTotalScore = $intTotalScore+$arrResult['question_score'];
	$arrSubExample = $objQuestion->getQuestionExample($arrTestResult[0]['example_numbering_style'],$arrResult['question_seq'],1);
	$intQuestionTotalCnt ++;
	foreach($arrSubExample['type_1'] as $intSubKey=>$arrSubResult){
		if($arrSubResult['answer_flg']){
			$arrQuestionList[$intKey]['answer'] = $arrSubResult;
		}
	}
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	array 			$arr_output['book_info']  					: book 정보
 * @property	string 			$arr_output['book_cover_img'] 			: 커버 이미지 url
 * @property	string 			$arr_output['test_info'] 						: 테스트 정보
 * @property	string 			$arr_output['test_total_score'] 			: 테스트 총 점수
 * @property	array 			$arr_output['test_question_list'] 			: 테스트 문제 목록 
 * @property	integer 		$arr_output['book_total_question_cnt']	: 총문항수
 * @property	array 			$arr_output['book_user_total_record'] 	: 총 성적
 * @property	integer 		$arr_output['book_join_count']			: 참여자수
 * @property	integer 		$arr_output['book_score_avarage']		: 평균점수
 * 
 */
$arr_output['book_info'] = $arrBookInfo;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/default_cover.png";
$arr_output['test_info'] = $arrTestResult;
$arr_output['test_total_score'] = $intTotalScore;
$arr_output['question_total_cnt'] = $intQuestionTotalCnt;
$arr_output['test_question_list'] = $arrQuestionList;
$arr_output['book_total_question_cnt'] = $intBookQuestionCnt;
$arr_output['book_user_total_record'] = $arrUserTotalRecord;
$arr_output['book_join_count'] = $arrUserTotalRecord[0]['user_count']?$arrUserTotalRecord[0]['user_count']:0;
$arr_output['book_score_avarage'] = $arrUserTotalRecord[0]['user_count']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
?>