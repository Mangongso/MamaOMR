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
$intPage = (int)$_POST['page'];
$strTEstSeq = $_POST['t'];
$intUserSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objAnswer = new MAnswer($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objWrongNote = new WrongNote($resMangongDB);

/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}
$arrPaging = array('page'=>$intPage ,'result_number'=>15,'block_number'=>10,'param'=>null);
$arrSearch = array();
$arrOrder = array();
$arrWrongNoteList = $objWrongNote->getWrongNoteList($intUserSeq, $arrSearch, $arrOrder, $_COOKIE['mg_select_teacher'], true, $arrPaging);
foreach($arrWrongNoteList as $intKey=>$arrResult){
	$arrWrongNoteList[$intKey]['test'] = $objTest->getTest($arrResult['test_seq']);
}
$strScript = "objWrongNote.getWrongNoteList";
if($arrPaging){
	$arr_output['paging'] = $arrPaging;
	$arr_output['paging']['paging']['first']['link'] = 'javascript:'.$strScript.'('.(int)$arr_output['paging']['paging']['first']['number'].');';
	$arr_output['paging']['paging']['end']['link'] = 'javascript:'.$strScript.'('.(int)$arr_output['paging']['paging']['end']['number'].');';
	$arr_output['paging']['paging']['prev']['link'] = 'javascript:'.$strScript.'('.(int)$arr_output['paging']['paging']['prev']['number'].');';
	$arr_output['paging']['paging']['next']['link'] = 'javascript:'.$strScript.'('.(int)$arr_output['paging']['paging']['next']['number'].');';
	foreach($arr_output['paging']['paging']['page'] as $page_key=>$page_value){
		$arr_output['paging']['paging']['page'][$page_key]['link'] = 'javascript:'.$strScript.'('.(int)$page_value['number'].');';
	}
}

//include
// include(CONTROLLER_NAME."/Common/include/getTeacherSeq.php");

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult
);
$arr_output['note_list'] = $arrWrongNoteList;
$arr_output['teacher_seq'] = $arrTeacherSeq;//this value  is in Common/include/getTeacherSeq.php
$arr_output['teacher_name'] = $arrTeacherName;//this value  is in Common/include/getTeacherSeq.php
?>