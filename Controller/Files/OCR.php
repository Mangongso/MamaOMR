<?php
/**
 * @Controller OCR
 * @subpackage   	Core/DBmanager/DBmanager
 * @package   	Member/MAnswer
 * @subpackage   	OCR/OCR
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/MAnswer.php');
require_once('Model/OCR/OCR.php');

/**
 * Variable 세팅
 * @var 	$strImgType 이미지 타입
 * @var 	$arrURLInfo image
 * @var 	$arrFileInfo 파일Path
 * @var 	$arrVar Query
 * @var 	$strDocImageFile doc이미지파일 path
 */
$strImgType = $_POST['img_type'];
$strDocImageUrl = $_POST['image'];
$arrURLInfo = parse_url($_POST['image']);
$arrFileInfo = pathinfo($arrURLInfo['path']);
$arrVar = parse_str($arrURLInfo['query']);
if($strImgType=="tmp"){
	$strDocImageFile = TMP_DIR.DIRECTORY_SEPARATOR.$k;
}else{
	//$strDocImageFile = QUESTION_IMAGE_DIR.DIRECTORY_SEPARATOR.
}

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objAnswer 	: Answer 객체
 * @property	object			$objOCR  		: OCR 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objAnswer = new MAnswer($resMangongDB);
$objOCR = new OCR();

 /**
 * Main Process
 */
$strQuestion = $objOCR->convert($strDocImageFile,$strDocImageUrl);


/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: OCR 결과 성공 여부
 * @property	string 			$strQuestion 		: 문제
 */
$arr_output = array('result'=>true,'question'=>$strQuestion);
echo json_encode($arr_output);
?>