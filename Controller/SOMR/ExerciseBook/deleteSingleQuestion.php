<?
/**
 * @Controller single 문제 삭제 
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
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}
$boolResult = $objQuestion->deleteQuestion($intTestSeq, $intQuestionSeq);

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * @property	boolean 		$boolResult 			: 문제 삭제 성공 여부 확인
 * @property	string 			$intTestSeq 			: 테스트 시컨즈
 * @property	string 			$intQuestionSeq 	: 문제 시컨즈
 */
$arrResult = array(
		'boolResult'=>$boolResult,
		'test_seq'=>$intTestSeq,
		'question_seq'=>$intQuestionSeq
);
echo json_encode($arrResult);
?>