<?
/**
 * @Controller single 문제 가져오기
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');

/**
 * Variable 세팅
 * @var 	$intTestSeq	테스트 시컨즈
 * @var 	$intQuestionSeq		문제 시컨즈
 */  
$intTestSeq = $_POST['test_seq'];
$intQuestionSeq = $_POST['question_seq'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objTest  				: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);

/**
 * Main Process
 */
include(CONTROLLER_NAME."/Auth/checkAuth.php");
$arrTestResult = $objTest->getTests($intTestSeq);

//get select question
$arrTestQuestion = $objTest->getTestsQuestion($intTestSeq,$intQuestionSeq);
$arrSelectQuestion = $objQuestion->getQuestion($intQuestionSeq,null,$arrTestResult[0]['example_numbering_style']);
foreach($arrSelectQuestion[0]['arr_question_example']['type_1'] as $intKey=>$arrResult){
	$arrSelectQuestion[0]['arr_question_example']['type_1'][$intKey]['right_wrong_flg'] = $arrRightWrong[$arrResult['seq']]?1:0;
	$arrSelectQuestion[0]['arr_question_example']['type_1'][$intKey]['right_wrong_contents'] = $arrRightWrong[$arrResult['seq']];
	$arrSelectQuestion[0]['order_number'] = $arrTestQuestion[0]['order_number'];
	$arrSelectQuestion[0]['question_score'] = $arrTestQuestion[0]['question_score'];
	$arrSelectQuestion[0]['test_seq'] = $intTestSeq;
	$arrSelectQuestion[0]['question_seq'] = $arrSelectQuestion[0]['seq'];
}
//get question right_wrong
$arrSelectQuestion[0]['right_wrong_flg'] = $arrRightWrong[0]?1:0;
$arrSelectQuestion[0]['right_wrong_contents'] = $arrRightWrong[0];

$intQuestionTotalCount = $objTest->getTestsQuestionCount($intTestSeq);

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * @property	array 			$arr_output['question'] 			: 선택한 문제
 * @property	integer 		$arr_output['question_number'] 			: 문제번호
 * @property	integer 		$arr_output['question_score'] 	: 문제 점수
 * @property	integer 		$arr_output['question_total_count'] 	: 전체 문제 개수
 * @property	integer 		$arr_output['test_seq'] 	: 테스트 시컨즈
 * @property	array 			$arr_output['test'] 	: 문제 결과 
 */
$arr_output['question'] = $arrSelectQuestion;
$arr_output['question_number'] = $intQuestionNumber;
$arr_output['question_score'] = $intQuestionScore;
$arr_output['question_total_count'] = $intQuestionTotalCount;
$arr_output['test_seq'] = $intTestSeq;
$arr_output['question_seq'] = $arrSelectQuestion[0]['seq'];
$arr_output['test'] = $arrTestResult;
?>