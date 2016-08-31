<?php
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Book.php');

/* set variable */
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_SESSION['smart_omr']['member_key'];
$intAnswerSeq = $_POST['answer_seq'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objBook = new Book($resMangongDB);

/*main process*/
$arrAnswer = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, (int)$intAnswerSeq);
$arrWrongNote = $objWrongNote->getWrongNoteFromQuestion($strMemberSeq,$arrAnswer[0]['record_seq'],$arrAnswer[0]['test_seq'],$arrAnswer[0]['question_seq']);
$arrQuestion = $objQuestion->getQuestion($arrAnswer[0]['question_seq']);
$intBookSeq = $objBook->getBookSeqFromTestSeq($arrAnswer[0]['test_seq']);

/* make output */
$arr_output['answer'] = $arrAnswer;
$arr_output['wrong_note'] = $arrWrongNote;
$arr_output['question'] = $arrQuestion;
$arr_output['book_seq'] = $intBookSeq;
?>