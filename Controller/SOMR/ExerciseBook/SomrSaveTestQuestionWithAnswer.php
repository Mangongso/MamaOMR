<?
/**
 * @Controller 테스트 문제와 정답 설정 저장
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Tag
 * @package      	Mangong/Test
 * @package      	Mangong/MQuestion
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Tag.php');

/**
 * Variable 세팅
 * @var 	$intWriterSeq		마마OMR 마스터 선생님 시컨즈
 * @var 	$intBookSeq		book 시컨즈 
 * @var 	$intTestsSeq		테스트 시컨즈 
 * @var 	$intPublishedSeq		published 시컨즈
 * @var 	$intMemberSeq		유저시컨즈
 * @var 	$arrQuestionSeq		문제 시컨즈 
 * @var 	$arrOrderNo		문제 번호
 * @var 	$arrQuestionScore		문제점수
 * @var 	$arrQuestionType		문제타입
 * @var 	$arrQuestionTags		문제 태그
 * @var 	$arrAnswer		정답 정보
 * @var 	$arrQuestion		문제 정보
 * @var 	$arrExamples		보기 정보
 * @var 	$arrQuestionHint		문제 힌트
 * @var 	$arrQuestionCommentary		문제 설명
 * @var 	$arrQuestionDetail		문제 상세 정보
 */
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$intBookSeq = $_POST['book_seq'];
$intTestsSeq = $_POST['test_seq'];
$intPublishedSeq = $_POST['published_seq'];
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];

$arrQuestionSeq = $_POST['question_seq'];
$arrOrderNo = $_POST['order_number'];

$arrQuestionScore = $_POST['question_score'];
$arrQuestionType = $_POST['question_type'];
$arrQuestionTags = $_POST['question_tags'];
$arrAnswer = $_POST['answer'];
$arrQuestion = $_POST['question'];
$arrExamples = $_POST['example'];

$arrQuestionHint = $_POST['question_hint'];
$arrQuestionCommentary = $_POST['question_commentary'];
$arrQuestionDetail = $_POST['question_detail'];

$arrAllTags = array();

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objTest  				: Test 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objTag 					: Tag 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objTag = new Tag($resMangongDB);

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

/* update question & update example */   
if(count($arrQuestionSeq)>0){
	//$boolResult = $objQuestion->deleteQuestionTags($intTestsSeq);//delete all question tags
	foreach($arrQuestionSeq as $intKey=>$intQuestionSeq){
		$intQuestionType = $arrQuestionType[$intQuestionSeq];
		// question type 1~4는 1의 input 을 사용함
		// question tyype 5~9은 5의 input 을 사용함
		switch($intQuestionType){
			case(1):
			case(2):
			case(3):
			case(4):
			case(11):
				$strQuestionContents = $arrQuestion[$intQuestionSeq][1];
				$arrExample = $arrExamples[$intQuestionSeq][1];
			break;
			case(5):
			case(6):
			case(7):
			case(8):
			case(9):
				$strQuestionContents = $arrQuestion[$intQuestionSeq][5];
				$arrExample = $arrExamples[$intQuestionSeq][5];
			break;
			default:
				$strQuestionContents = $arrQuestion[$intQuestionSeq][$intQuestionType];
				$arrExample = $arrExamples[$intQuestionSeq][$intQuestionType];				
			break;
		}
		$intExampleType = 1;
		$strQuestionHint = $arrQuestionHint[$intQuestionSeq];
		$strQuestionCommentary = $arrQuestionCommentary[$intQuestionSeq];
		$intJinoonSeq = null;
		$intQuestionScore =  $arrQuestionScore[$intQuestionSeq];
		$intOrderNumber = $arrOrderNo[$intQuestionSeq];
		$intQuestionNumber = $arrOrderNo[$intQuestionSeq];
		/* tags */
		$arrTags = $arrQuestionTags[$intQuestionSeq]?explode(',',preg_replace("/\s+/", "", $arrQuestionTags[$intQuestionSeq])):array();
		for($i=0;$i<count($arrTags);$i++){
			if($arrTags[$i]==null || $arrTags[$i]==''){
				unset($arrTags[$i]);
				continue;
			}
			//array_push surveytag
			if(!in_array($arrTags[$i], $arrAllTags, true)){
		        array_push($arrAllTags, $arrTags[$i]);
		    }
		}
		//sort($arrTags);
		$strTags = join(',',$arrTags);
		$strTags = $strTags?$strTags:'';
		
		$boolResult = $objQuestion->setQuestion($intMemberSeq, $strQuestionContents, $intQuestionType, $intExampleType,$strQuestionHint,$strQuestionCommentary,$intJinoonSeq, $intQuestionSeq, $strTags);
		if($boolResult){
			$boolResult = $objQuestion->updateQuestionToTests($intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber);
		}
		
		if($boolResult && $arrQuestionDetail[$intQuestionSeq]){
			$boolResult = $objQuestion->disableQuestionExample($intQuestionSeq);
			foreach($arrExample as $intExampleNumber=>$arrExampleResult){
				$boolResult = $objQuestion->setQuestionExample($intQuestionSeq,$arrExampleResult['content'],$intAnswerFlg=0,1,$arrExampleResult['seq'],$intExampleNumber,$arrExampleResult['subjective_answer'],$intQuestionType);
			}
		}
		if($boolResult){
			$boolResult = $objQuestion->updateExampleAnswerFlg($intQuestionSeq,null,$arrAnswer[$intQuestionSeq]['seq']);
		}
		
		//delete question tags
		$objTag->deleteQuestionTag($intQuestionSeq);
		if(count($arrTags)){
			foreach($arrTags as $intKey=>$strTagName){
				//set tags
				//$objTag->setTag($strTagName,2,$intMemberSeq);//tag type 1:survey 2:question
				//set test_tags
				$objTag->setQuestionTag($intQuestionSeq,$strTagName);
			}
		}
	}
	if($boolResult){
		$boolResult = $objTest->updateTestTotalScore($intTestsSeq,$intPublishedSeq);
		
		//get survey tags & push $arrAllTags
		$arrTests = $objTest->getTests($intTestsSeq,$intWriterSeq,true);
		if($arrTests[0]['tags'] && $arrTests[0]['tags']!==''){
			$arrTestsTag = explode(',', $arrTests[0]['tags']); 
			foreach($arrTestsTag as $intKey=>$strTestsTagName){
				if(!in_array($strTestsTagName, $arrAllTags, true)){
					array_push($arrAllTags, $strTestsTagName);
			    }
			}
		}
		
		//set survey tags
		sort($arrAllTags);
		$strAllTags = join(',',$arrAllTags);
		$strAllTags = $strAllTags?$strAllTags:'';
		if(count($arrAllTags)){
			foreach($arrAllTags as $intKey=>$strTagName){
				//set tags
				$objTag->setTag($strTagName,2,$intMemberSeq);//tag type 1:survey 2:question
				//set test_tags
				$objTag->setTestsTag($intTestsSeq,$strTagName);
				
			}
		}
		//update survey tags
		$objTest->updateTestsTags($intTestsSeq, $strAllTags);
	}	
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 테스트 문제 와 정답 설정 저장 성공 여부
 * @property	string 			str_book_seq 		: 암호화 책 시컨즈
 */
$arrResult = array(
		'boolResult'=>$boolResult,
		'str_book_seq'=>md5($intBookSeq)
);
echo json_encode($arrResult);
?>