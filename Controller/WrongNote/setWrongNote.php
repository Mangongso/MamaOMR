<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MAnswer.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/WrongNote.php');

/* set variable */ 
$strWrongAnswerKey = $_POST['wrong_note_key'];
$strWrongNoteFileName = $_POST['wrong_note_file_name'];
$strWrongNoteUploadKey = $_POST['wrong_note_upload_key'];

$intUserSeq = $_SESSION['smart_omr']['member_key'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);
$objTicket = new Ticket($resMangongDB);

/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
$arrAnswerInfo = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, $intAnswerSeq)

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult,
		'bool_apply_ticket'=>$boolAppliedTicket,
		'err_code'=>$err_code,
		'teacher_name'=>$arrMyTeacherInfo[0]['name']
);
echo json_encode($arrResult);
?>