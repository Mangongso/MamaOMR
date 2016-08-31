<?php
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/Member/Member.php');
require_once('Model/Core/DataManager/FileHandler.php');

/* set variable */
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strMemberSeq = $_SESSION['smart_omr']['member_key'];
$strAnswerKey = $_POST['answer_key'];
$strWrongNoteKey = $_POST['wrong_note_key'];
$strWrongNoteFileName = $_POST['wrong_note_file_name'];
$strWrongNoteUploadKey = $_POST['wrong_note_upload_key'];
$strQuestion = $_POST['question'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objMember = new Member($resMangongDB);
$objFileHandler = new FileHandler();
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);

/*main process*/
$arrMember = $objMember->getMemberByMemberSeq($strMemberSeq);
$arrAnswer = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, $strAnswerKey);
$arrQuestion = $objQuestion->getQuestion($arrAnswer[0]['question_seq']);
if(count($arrMember)>0 && count($arrAnswer)>0){
	$intBookSeq = $objBook->getBookSeqFromTestSeq($arrAnswer[0]['test_seq']);
	if(trim($strWrongNoteUploadKey)){
		$arrFiles = array(array(
				'source'=>"/tmp/".$strWrongNoteUploadKey,
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
/* make output */
$arr_output = array('result'=>$boolResult);
echo json_encode($arr_output);
?>