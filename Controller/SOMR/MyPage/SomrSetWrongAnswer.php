<?php
/**
 * @Controller 오답 노트 저장
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @subpackage   	Core/DataManager/FileHandler
 * @package      	Mangong/MAnswer
 * @package      	Mangong/Book
 * @package      	Mangong/MQuestion
 * @package      	Mangong/WrongNote
 * @subpackage      	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/Member/Member.php');
require_once('Model/Core/DataManager/FileHandler.php');

/**
 * Variable 세팅
 * @var 	$strMemberSeq		암호화 유저 시컨즈 
 * @var 	$strAnswerKey		유저 선택 답 키
 * @var 	$strWrongNoteKey		오답노트 키
 * @var 	$strWrongNoteFileName		오답노트 이미지 파일명
 * @var 	$strWrongNoteUploadKey		오답노트 업데이트 키
 * @var 	$strQuestion		문제 내용
 */
$strMemberSeq = $_SESSION['smart_omr']['member_key'];
$strAnswerKey = $_POST['answer_key'];
$strWrongNoteKey = $_POST['wrong_note_key'];
$strWrongNoteFileName = $_POST['wrong_note_file_name'];
$strWrongNoteUploadKey = $_POST['wrong_note_upload_key'];
$strQuestion = $_POST['question'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objAnswer 					: MAnswer 객체
 * @property	object 		$objWrongNote 					: WrongNote 객체
 * @property	object			$objMember  				: Member 객체
 * @property	object			$objFileHandler  				: FileHandler 객체
 * @property	object 		$objBook 					: Book 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objMember = new Member($resMangongDB);
$objFileHandler = new FileHandler();
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);

 /**
 * Main Process
 */

$arrMember = $objMember->getMemberByMemberSeq($strMemberSeq);
$arrAnswer = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, $strAnswerKey);
$arrQuestion = $objQuestion->getQuestion($arrAnswer[0]['question_seq']);
if(count($arrMember)>0 && count($arrAnswer)>0){
	$intBookSeq = $objBook->getBookSeqFromTestSeq($arrAnswer[0]['test_seq']);
	if(trim($strWrongNoteUploadKey)){
		$arrFiles = array(array(
				'source'=>TMP_DIR.$strWrongNoteUploadKey,
				'target'=>QUESTION_FILE_DIR.DIRECTORY_SEPARATOR.$intBookSeq.DIRECTORY_SEPARATOR.$arrAnswer[0]['test_seq'].DIRECTORY_SEPARATOR.$arrAnswer[0]['question_seq'].DIRECTORY_SEPARATOR.$strWrongNoteFileName
		));
		$objFileHandler->FileCopy($arrFiles);
		$strQuestionFileName = $objFileHandler->strFileName;
	}else{
		$strQuestionFileName = $arrQuestion[0]['file_name'];
	}
	$arrWrongNote = $objWrongNote->getWrongNote($arrMember[0]['member_seq']);
	if(count($arrWrongNote)>0){
		$intNoteSeq = $arrWrongNote[0]['seq'];
	}else{
		$intNoteSeq = $objWrongNote->setWrongNote($arrMember[0]['member_seq'], "My wrong answer note");
	}
	$boolResult = $objWrongNote->setWrongNoteQuestion($intNoteSeq,$arrMember[0]['member_seq'],$arrAnswer[0]['record_seq'],$arrAnswer[0]['test_seq'],$arrAnswer[0]['question_seq'],$arrAnswer[0]['user_answer'],$strQuestionFileName,$strQuestion);
	if($boolResult){
		$intQuestionSeq = $arrAnswer[0]['question_seq'];
		$boolResult = $objQuestion->setQuestion($arrMember[0]['member_seq'], $strQuestion, $arrQuestion[0]['question_type'], $arrQuestion[0]['example_type'], null, null, null, $intQuestionSeq,$strTags,$strQuestionFileName);
	}
}else{
	$boolResult = false;
}
/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 오답 노트 저장 결과 성공 여부
 */
$arr_output = array('result'=>$boolResult);
echo json_encode($arr_output);
?>