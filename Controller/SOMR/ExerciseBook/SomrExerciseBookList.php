<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/Teacher.php');

/* set variable */ 
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$inSearchFlg = $_REQUEST['search_flg'];
$strSearchKey = $_REQUEST['search_key']?$_REQUEST['search_key']:'';
$intCategorySeq = $_REQUEST['category_seq']?$_REQUEST['category_seq']:'';
$intPage = $_REQUEST['page']?$_REQUEST['page']:1;

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objTeacher = new Teacher($resMangongDB);

/*main process*/
$arrPaging = array('page'=>$intPage ,'result_number'=>8,'block_number'=>10,'param'=>null);
if($strSearchKey!='' || $intCategorySeq!=''){
	$arrSearch = array(
		'search_type'=>'title',
		'search_keyword'=>$strSearchKey,
		'category_seq'=>$intCategorySeq
	);
}

$arrBooks = $objBook->getBook($arrSearch,array(),$arrPaging);
foreach($arrBooks as $intKey=>$arrBook){
	//get all test info 
	$strTestSeqGroup = $objBook->getTestSeqByBook($arrBook['seq']);
	//get book's question count 
	$arrBooks[$intKey]['question_count'] = $objQuestion->getQuestionCountInTest(null,null,$strTestSeqGroup);
	//get book's record
	$arrBooks[$intKey]['total_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup);
	$arrBooks[$intKey]['avarage_score'] = $arrBooks[$intKey]['total_record'][0]['user_count']?round($arrBooks[$intKey]['total_record'][0]['total_user_score']/$arrBooks[$intKey]['total_record'][0]['user_count'],1):0;
	//$arrBooks[$intKey]['writer_info'] = $objTeacher->getTeacher($arrBook['sub_writer_seq']);
	$arrBooks[$intKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/default_cover.png";
}

/* make output */
$arr_output['book_list'] = $arrBooks;
?>