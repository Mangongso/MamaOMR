<?
/**
 * @Controller 테스트 저장
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/Member/Member.php');

/**
 * Variable 세팅
 * @var 	$intMasterSeq		마마OMR 마스터 선생님 시컨즈
 * @var 	$strMemberKey		md5암호화 유저 시컨즈
 * @var 	$strTitle		테스트 제목
 * @var 	$intQuestionType	문제 타입
 * @var 	$strBookSeq		암호화 책 시컨즈
 * @var 	$intExampleNumberingStyle		보기 번호 스타일
 * @var 	$intTotalScore		총점
 * @var 	$intQuestionCount		문제개수
 * @var 	$intTestsType		테스트 형식
 * @var 	$intTestsProgflg		테스트 진행 flg (1.일괄 2.개별)
 * @var 	$intTestsPaperType		테스트 페이저 형시 (0:OMF 1:paper 2:auto)
 * @var 	$intRepeatFlg		반복풀이 flg
 * @var 	$intRecordViewFlg		성적 보여주기 flg
 */
$intMasterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberKey = $_SESSION['smart_omr']['member_key'];
$strTitle = trim($_REQUEST['subject']);
$intQuestionType = $_REQUEST['question_type'];
$strBookSeq = $_REQUEST['book_md5_seq'];
$intExampleNumberingStyle = 0;//1,2,3
$intTotalScore = 100;
$intQuestionCount = 5;
$intTestsType = 1; //1.test 2.report
$intTestsProgflg = 2; //1.일괄 2.개별
$intTestsPaperType = 0; //0:OMF 1:paper 2:auto
$intRepeatFlg = 1;
$intRecordViewFlg = 1; //0:hidden 1:visible

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 * @property	object 		$objTest 					: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objMember = new Member($resMangongDB);

/**
 * Main Process
 */	
$intAuthRedirectFlg = 0;
include(CONTROLLER_NAME."/Auth/checkAuth.php");
if($intAuthFlg == AUTH_FALSE){
	$arrResult = array(
			'boolResult'=>false,
			'error_msg'=>'로그인 후 사용가능합니다.'
	);
	echo json_encode($arrResult);
	exit;
}

if($intQuestionCount>500){
	/* make output */
	$arrResult = array(
			'boolResult'=>false,
			'error_msg'=>'최대 500문항을 초과하였습니다.'
	);
	echo json_encode($arrResult);
	exit;
}

$arrSearch = array();
$arrSearch['md5(seq)'] = $strBookSeq;
$arrBookInfo = $objBook->getBook($arrSearch);

/* get membe info by member_key * */
$arrMember = $objMember->getMemberByMemberSeq($strMemberKey);
$boolResult = $objTest->setTests($arrMember[0]['member_seq'], $intTestsType, $strTitle, "", $intExampleNumberingStyle, $intTestsSeq,'',$intMasterSeq);
if($boolResult){
	//set survey published
	if($intPublishSeq){
		$boolResult = $objTest->updateTestsPublish($intTestsSeq, $intPublishSeq, $strStartDate, $strFinishDate, $time, $intCategory,$intGroupSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg);
	}else{
		$boolResult = $objTest->publishTests($intTestsSeq, $strStartDate, $strFinishDate, $time, $intCategory, $intGroupSeq, $intPublishSeq, $intTotalScore, $intTestsProgflg, $intTestsPaperType,$intRecordViewFlg,$intRepeatFlg);
		if($intQuestionCount>0){
			$intQuestoinNumber = 1;
			$boolQuestionFlg = true;
		}
		//update published book_seq
		$boolResult = $objBook->updatePublishedBookSeq($intPublishSeq,$arrBookInfo[0]['seq']);
	}
	if($boolQuestionFlg){
		//get question score
		$questoin_score = intval($intTotalScore/$intQuestionCount);
		$questoin_score_rest = $intTotalScore%$intQuestionCount;
		//set question
		for($intQuestoinNumber;$intQuestoinNumber<=$intQuestionCount;$intQuestoinNumber++){
			$intQuestionSeq = null;
			$boolResult = $objQuestion->setQuestion($intMasterSeq, '', $intQuestionType, 1, null, null, null, $intQuestionSeq);
			if($boolResult){
				if($intQuestoinNumber==$intQuestionCount && $questoin_score_rest){
					$boolResult = $objQuestion->setQuestionToTests($intTestsSeq, $intQuestionSeq, $intQuestoinNumber ,$questoin_score+$questoin_score_rest,$intQuestoinNumber);
				}else{
					$boolResult = $objQuestion->setQuestionToTests($intTestsSeq, $intQuestionSeq, $intQuestoinNumber ,$questoin_score,$intQuestoinNumber);
				}
	
				/*
				if($boolResult){
					$boolResult = $objQuestion->setQuestionExampleAll($intQuestionSeq,'',$intAnswerFlg=0,$intExampleType,null);
				}
				*/
	
				$intExampleCount = 5;
				for($intExampleNumber=1;$intExampleNumber<=$intExampleCount;$intExampleNumber++){
					$boolResult = $objQuestion->setQuestionExample($intQuestionSeq,'',$intAnswerFlg=0,1,null,$intExampleNumber,null,$intQuestionType);
				}
			}
		}
	}
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 테스트 저장 결과 성공 여부
 * @property	integer 			test_seq 		: 테스트 시컨즈
 * @property	integer 			$arrBookInfo[0]['seq'] 		: book 시컨즈
 */
$arr_output = array(
	'book_seq'=>$arrBookInfo[0]['seq'],
	'test_seq'=>$intTestsSeq,
	'boolResult'=>$boolResult
);
echo json_encode($arr_output);

?>