<?
/**
 * @Controller 테스트 정답 제출
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Record
 * @package      	Mangong/MAnswer
 * @subpackage      	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/Member/Member.php');
require_once('Model/ManGong/Record.php');

/**
 * Variable 세팅
 * @var 	$strMemberSeq		암호화 유저 시컨즈
 * @var 	$intTestSeq		테스트 시컨즈
 * @var 	$arrUserAnswer	유저 선택 답
 */
$strMemberSeq = $_SESSION['smart_omr']['member_key'];//Member seq
$intTestSeq = $_POST['test_seq'];
$arrUserAnswer = $_POST['user_answer'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objRecord 					: Record 객체
 * @property	object 		$objAnswer 					: MAnswer 객체
 * @property	object 		$objMember 					: Member 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objMember = new Member($resMangongDB);
$objRecord = new Record($resMangongDB);

 /**
 * Main Process
 */
$intAuthRedirectFlg = 0;
include(CONTROLLER_NAME."/Auth/checkAuth.php");
if($intAuthFlg == AUTH_FALSE){
	$arrResult = array(
			'boolResult'=>false
	);
	echo json_encode($arrResult);
	exit;
}
//get member info
$arrMemberInfo = $objMember->getMemberByMemberSeq($strMemberSeq);
$intMemberSeq = $arrMemberInfo[0]['member_seq'];

$arrTest = $objTest->getTests($intTestSeq,null,true);
if(count($arrTest)>0){
	// record 테이블에 test_time = null 인 row 가 있는지 확인 후 없을 경우 record 를 insert 한다. 여시서 insert 하는게 정상이나 기존 소스에 test 상태 update 시 insert 하는 로직이 있음
	if(!$objRecord->checkNotFinishedUserRecord($intMemberSeq, $intTestSeq)){
		$intRecordSeq = 0;
		$boolResult = $objRecord->setUserRecord($intMemberSeq,$arrTest[0]['seq'],$arrMemberInfo[0]['name'],$arrMemberInfo[0]['sex'],0,$arrTest[0]['publish'][0]['total_score'],0,0,$intRecordSeq);
	}else{
		$intRecordSeq = $objRecord->getLastRecordSeq($intMemberSeq, $intTestSeq);
	}
	$arrQuestions = $objTest->getTestQuestionListWithExample($intTestSeq,null,array(1,2,3,4,5,6,7,8,9,10,20,11),$arrTest[0]['example_numbering_style']);
	foreach($arrQuestions as $intKey=>$arrQuestion){
		$intQuestionSeq = $arrQuestion['question_seq'];
		$intQuestionType = $arrQuestion['question_type'];
		$arrQuestionExample = $arrQuestion['example']['type_1'];
		$mixAnswer = $arrUserAnswer[$intQuestionSeq];
		$arrUserAnswerCorrectInfo = $objQuestion->checkAnswerCorrect($intQuestionSeq,$intQuestionType,$arrQuestionExample,$mixAnswer);
		
		$strQuestionAnswer = $arrUserAnswerCorrectInfo['question_answer'];
		$strUserAnswer = $arrUserAnswerCorrectInfo['user_answer'];
		$boolResultFlg = $arrUserAnswerCorrectInfo['result'];
		$intQuestionScore = $arrQuestion['question_score'];
		if($intQuestionType==20 || $intQuestionType==10){
			$mixResult = $objAnswer->setUserAnswer($intMemberSeq,$intTestSeq,$intQuestionSeq,$strQuestionAnswer,'',$boolResultFlg,$arrMemberInfo[0]['name'],$arrMemberInfo[0]['sex'],0);
			if($mixResult){
				$boolResult = $objAnswer->setUserAnswerDiscus($mixResult,$intTestSeq,$intRecordSeq,$intQuestionSeq,$intMemberSeq,$strUserAnswer);
			}
		}else{
			$boolResult = $objAnswer->setUserAnswer($intMemberSeq,$intTestSeq,$intQuestionSeq,$strQuestionAnswer,$strUserAnswer,$boolResultFlg,$arrMemberInfo[0]['name'],$arrMemberInfo[0]['sex'],$intQuestionScore);
		}
	}
	
	// set join user info
	$boolResult = $objTest->setTestsJoinUserStatusByUserSeq($intTestSeq, $arrTest[0]['publish'][0]['seq'], $intMemberSeq, null, 3);
	$arrUserAnswerTotal = $objAnswer->getUserAnswerTotal($intMemberSeq,$intTestSeq,$intRecordSeq);
	if(count($arrUserAnswerTotal)>0){
		$boolResult = $objRecord->updateUserRecord($intMemberSeq,$intTestSeq,$arrUserAnswerTotal[0]['user_score'],$arrUserAnswerTotal[0]['right_count'],$arrUserAnswerTotal[0]['total_count']-$arrUserAnswerTotal[0]['right_count'],$testingTime,$arrTestsJoinUserInfo[0]['start_date'],$arrTestsJoinUserInfo[0]['end_date']);
	}	
}
/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 테스트 정답 제출 결과 성공 여부
 * @property	string 			md5($intTestSeq) 		: 암호화 테스트 시컨즈
 */
$arrResult = array(
		'boolResult'=>$boolResult,
		'str_test_seq'=>md5($intTestSeq),
);
echo json_encode($arrResult);
exit;
?>