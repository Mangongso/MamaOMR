<?php
/**
 * @Controller 오답노트 목록
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/MAnswer
 * @package      	Mangong/WrongNote
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Book
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/WrongNote.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Book.php');

/**
 * Variable 세팅
 * @var 	$intWriterSeq		마마OMR 마스터 시컨즈
 * @var 	$strMemberSeq		암호화 유저 시컨즈 
 * @var 	$intAnswerSeq	유저 정답 시컨즈
 */
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_POST['student_key']?$_POST['student_key']:$_SESSION['smart_omr']['member_key'];
$intAnswerSeq = $_POST['answer_seq'];
$strEditble = $_POST['editble'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objAnswer  				: MAnswer 객체
 * @property	object 		$objWrongNote 					: WrongNote 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object 		$objBook 					: Book 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objBook = new Book($resMangongDB);

 /**
 * Main Process
 */
$arrAnswer = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, (int)$intAnswerSeq);
$arrWrongNote = $objWrongNote->getWrongNoteFromQuestion($strMemberSeq,$arrAnswer[0]['record_seq'],$arrAnswer[0]['test_seq'],$arrAnswer[0]['question_seq']);
$arrQuestion = $objQuestion->getQuestion($arrAnswer[0]['question_seq']);
$intBookSeq = $objBook->getBookSeqFromTestSeq($arrAnswer[0]['test_seq']);

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	array 		$arr_output['answer']			: 정답 정보
 * @property	array			$arr_output['wrong_note'] 		: 오답 노트
 * @property	array 			$arr_output['question'] 		: 문제 정보
 * @property	integer 			$arr_output['book_seq'] 		: 책 시컨즈
 * @property	integer 			$arr_output['test_seq'] 		: 테스트 시컨즈
 */
$arr_output['editble'] = ($strEditble=="no")?false:true;
$arr_output['answer'] = $arrAnswer;
$arr_output['wrong_note'] = $arrWrongNote;
$arr_output['question'] = $arrQuestion;
$arr_output['book_seq'] = $intBookSeq;
$arr_output['test_seq'] = $arrAnswer[0]['test_seq'];
?>