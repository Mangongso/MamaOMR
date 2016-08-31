<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/StudentMG.php');
require_once('Model/Member/Member.php');

/* set variable */ 
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_SESSION['smart_omr']['member_key'];//student seq

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objStudentMG = new StudentMG($resMangongDB);
$objMember = new Member($resMangongDB);


/*main process*/
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/smart_omr');
	exit;
}

$arrMyJoinBooks = $objBook->getUserJoinBookList($strMemberSeq);
// print "<pre style='margin-left:300px;'>";
// var_dump($arrMyJoinBooks);
// print "</pre>";
foreach($arrMyJoinBooks as $intKey=>$arrBook){
	$arrTestListByBook = $objBook->getTestListByBook(md5($arrBook['seq']));
	//get all test info 
	$strTestSeqGroup = $objBook->getTestSeqByBook($arrBook['seq']);
	//get book's question count 
	$arrMyJoinBooks[$intKey]['test_count'] = count($arrTestListByBook);
	$arrMyJoinBooks[$intKey]['question_count'] = $objQuestion->getQuestionCountInTest(null,null,$strTestSeqGroup);
	//get book's record
	$arrMyJoinBooks[$intKey]['total_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup);
	$arrMyJoinBooks[$intKey]['my_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup,$strMemberSeq);
	$arrMyJoinBooks[$intKey]['avarage_score'] = $arrMyJoinBooks[$intKey]['total_record'][0]['user_count']?round($arrMyJoinBooks[$intKey]['total_record'][0]['total_user_score']/$arrMyJoinBooks[$intKey]['total_record'][0]['user_count'],1):0;
	$arrMyJoinBooks[$intKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/default_cover.png";
}

//get manager_student 
$arrManagerStudentList = $objStudentMG->getManagerStudentList($strMemberSeq);
if(count($arrManagerStudentList)){
	foreach($arrManagerStudentList as $intKey=>$arrManagerStudent){
		$strStudentSeq = md5($arrManagerStudent['student_seq']);
		$arrJoinBooks = $objBook->getUserJoinBookList($strStudentSeq);
		foreach($arrJoinBooks as $intSubKey=>$arrBook){
			$arrTestListByBook = $objBook->getTestListByBook(md5($arrBook['seq']));
			//get all test info 
			$strTestSeqGroup = $objBook->getTestSeqByBook($arrBook['seq']);
			//get book's question count 
			$arrJoinBooks[$intSubKey]['test_count'] = count($arrTestListByBook);
			$arrJoinBooks[$intSubKey]['question_count'] = $objQuestion->getQuestionCountInTest(null,null,$strTestSeqGroup);
			//get book's record
			$arrJoinBooks[$intSubKey]['total_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup);
			$arrJoinBooks[$intSubKey]['my_record'] = $objRecord->getTotalUserRecord(null,null,$strTestSeqGroup,$strStudentSeq);
			$arrJoinBooks[$intSubKey]['avarage_score'] = $arrJoinBooks[$intSubKey]['total_record'][0]['user_count']?round($arrJoinBooks[$intSubKey]['total_record'][0]['total_user_score']/$arrJoinBooks[$intSubKey]['total_record'][0]['user_count'],1):0;
			$arrJoinBooks[$intSubKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/default_cover.png";
		}
		$arrManagerStudentList[$intKey]['join_book'] = $arrJoinBooks;
		$arrManagerStudentList[$intKey]['student_info'] = $objMember->getMemberByMemberSeq($strStudentSeq);
	}
}

/* make output */
$arr_output['book_list'] = $arrMyJoinBooks;
$arr_output['manager_student_list'] = $arrManagerStudentList;
?>