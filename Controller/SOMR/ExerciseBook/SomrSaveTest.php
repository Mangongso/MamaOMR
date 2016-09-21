<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');

/* set variable */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intMemberSeq = SMART_OMR_TEACHER_SEQ;
$strTitle = trim($_REQUEST['subject']);
$intQuestionType = $_REQUEST['question_type'];
$strBookSeq = $_REQUEST['book_md5_seq'];
$intExampleNumberingStyle = 0;//1,2,3
$intTotalScore = 100;
$intQuestionCount = 5;
$intTestsType = 1; //1.test 2.report
$intTestsProgflg = 2; //1.일괄 2.개별
$intTestsPaperType = 0; //0:OMF 1:paper 2:auto
// $intRepeatFlg = $_POST['repeat_flg']; //0:no repeat 1:repeat
$intRepeatFlg = 1;
$intRecordViewFlg = 1; //0:hidden 1:visible

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);

/*main process*/	
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
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

$boolResult = $objTest->setTests($intMemberSeq, $intTestsType, $strTitle, "", $intExampleNumberingStyle, $intTestsSeq);
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
			$boolResult = $objQuestion->setQuestion($intMemberSeq, '', $intQuestionType, 1, null, null, null, $intQuestionSeq);
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

/* make output */
$arr_output = array(
	'book_seq'=>$arrBookInfo[0]['seq'],
	'test_seq'=>$intTestsSeq,
	'boolResult'=>$boolResult
);
echo json_encode($arr_output);

?>