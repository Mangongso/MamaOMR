<?
/**
 * @Controller 마이페이지 출력 정보
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @package      	Mangong/MQuestion
 * @package      	Mangong/Record
 * @package      	Mangong/StudentMG
 * @subpackage      	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/StudentMG.php');
require_once('Model/Member/Member.php');

/**
 * Variable 세팅
 * @var 	$intWriterSeq		마마OMR 마스터 시컨즈
 * @var 	$strMemberSeq		암호화 유저 시컨즈 
 */
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_SESSION['smart_omr']['member_key'];//student seq

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objBook 					: Book 객체
 * @property	object 		$objQuestion 					: MQuestion 객체
 * @property	object			$objRecord  				: Record 객체
 * @property	object 		$objStudentMG 					: StudentMG 객체
 * @property	object 		$objMember 					: Member 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objStudentMG = new StudentMG($resMangongDB);
$objMember = new Member($resMangongDB);


 /**
 * Main Process
 */

include(CONTROLLER_NAME."/Auth/checkAuth.php");

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
	$arrMyJoinBooks[$intKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/no_cover.png";
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
			$arrJoinBooks[$intSubKey]['book_cover_img'] = $arrBook['cover_url']?$arrBook['cover_url']:"/smart_omr/_images/no_cover.png";
		}
		$arrManagerStudentList[$intKey]['join_book'] = $arrJoinBooks;
		$arrManagerStudentList[$intKey]['student_info'] = $objMember->getMemberByMemberSeq($strStudentSeq);
	}
}

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	array 		$arr_output['book_list'] 					: 책 목록
 * @property	array 		$arr_output['manager_student_list'] 		: 학습매니저 학생 목록
 */
$arr_output['book_list'] = $arrMyJoinBooks;
$arr_output['manager_student_list'] = $arrManagerStudentList;
?>