<?php
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/OCR/OCR.php');

/* set variable */
$strImgType = $_POST['img_type'];
$arrURLInfo = parse_url($_POST['image']);
$arrFileInfo = pathinfo($arrURLInfo['path']);
$arrVar = parse_str($arrURLInfo['query']);
if($strImgType=="tmp"){
	$strDocImageFile = "/tmp".DIRECTORY_SEPARATOR.$k;
}else{
	//$strDocImageFile = QUESTION_IMAGE_DIR.DIRECTORY_SEPARATOR.
}

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
// $objWrongNote = new WrongNote($resMangongDB);
$objOCR = new OCR();

/*main process*/
// $arrAnswer = $objAnswer->getUserAnswerByAnswerSeq($strMemberSeq, (int)$intAnswerSeq);
// $arrWrongNote = $objWrongNote->getWrongNoteFromQuestion($strMemberSeq,$arrAnswer[0]['record_seq'],$arrAnswer[0]['test_seq'],$arrAnswer[0]['question_seq']);
$strQuestion = $objOCR->convert($strDocImageFile);
/* make output */
$arr_output = array('result'=>true,'question'=>$strQuestion);
echo json_encode($arr_output);
?>