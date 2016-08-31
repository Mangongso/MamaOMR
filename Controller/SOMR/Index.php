<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/Teacher.php');

/* set variable */ 
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$intPage = $_REQUEST['page']?$_REQUEST['page']:1;

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objTeacher = new Teacher($resMangongDB);

/*main process*/
//1. get main header
$arrQueId = array(
	1=>array(25)//main header
);

$arrHeader = $objBook->getBook(array(),$arrQueId[1]);
foreach($arrHeader as $intKey=>$arrBook){
	//get all test info 
	$strTestSeqGroup = $objBook->getTestSeqByBook($arrBook['seq']);
	//get book's question count 
	$arrHeader[$intKey]['question_count'] = $objQuestion->getQuestionCountInTest(null,null,$strTestSeqGroup);
	//get book's record
	$arrHeader[$intKey]['total_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup);
	$arrHeader[$intKey]['avarage_score'] = $arrHeader[$intKey]['total_record'][0]['user_count']?round($arrHeader[$intKey]['total_record'][0]['total_user_score']/$arrHeader[$intKey]['total_record'][0]['user_count'],1):0;
	//$arrHeader[$intKey]['writer_info'] = $objTeacher->getTeacher($arrBook['sub_writer_seq']);
	$arrHeader[$intKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/default_cover.png";
	$arrHeader[$intKey]['book_test_list'] = $objBook->getTestListByBook(md5($arrBook['seq']));
}

$arrPaging = array('page'=>$intPage ,'result_number'=>8,'block_number'=>10,'param'=>null);
$arrBooks = $objBook->getBook(null,null,$arrPaging);
foreach($arrBooks as $intKey=>$arrBook){
	//get all test info 
	$strTestSeqGroup = $objBook->getTestSeqByBook($arrBook['seq']);
	//get book's question count 
	$arrBooks[$intKey]['question_count'] = $objQuestion->getQuestionCountInTest(null,null,$strTestSeqGroup);
	//get book's record
	$arrBooks[$intKey]['total_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup);
	$arrBooks[$intKey]['avarage_score'] = $arrBooks[$intKey]['total_record'][0]['user_count']?round($arrBooks[$intKey]['total_record'][0]['total_user_score']/$arrBooks[$intKey]['total_record'][0]['user_count'],1):0;
	$arrBooks[$intKey]['writer_info'] = $objTeacher->getTeacher($arrBook['sub_writer_seq']);
	$arrBooks[$intKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/default_cover.png";
}

if($_REQUEST['mat'] && $_SESSION['smart_omr']){
	include($_SERVER['DOCUMENT_ROOT'].'/../Controller/SOMR/Auth/AuthManager.php');
}

/* make output */
$arr_output['header'] = $arrHeader;
$arr_output['book_list'] = $arrBooks;
?>