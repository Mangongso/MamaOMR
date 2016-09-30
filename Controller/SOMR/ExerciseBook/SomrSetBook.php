<?
/**
 * @Controller Book 저장
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 * @subpackage      	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/Member/Member.php');

/**
 * Variable 세팅
 * @var 	$strSubWriterSeq		암호화 유저 시컨즈
 * @var 	$intWriterSeq		마마 omr 마스터 시컨즈 
 * @var 	$strTitle	제목
 * @var 	$strPubName		출판사
 * @var 	$strIsbnCode		isbn코드
 * @var 	$intCategorySeq		카테고리 시컨즈
 */ 
$strSubWriterSeq = $_SESSION['smart_omr']['member_key'];//student seq
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strTitle = $_REQUEST['title'];
$strPubName = $_REQUEST['pub_name'];
$strIsbnCode = trim($_REQUEST['isbn_code']);
$intCategorySeq = $_REQUEST['category_seq'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 * @property	object 		$objMember 					: Member 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objMember = new Member($resMangongDB);

/**
 * Main Process
 */	
$intAuthRedirectFlg = 0;
include(CONTROLLER_NAME."/Auth/checkAuth.php");
if($intAuthFlg == AUTH_FALSE){
	$arrResult = array(
			'boolResult'=>false
	);
	echo json_encode($arrResult);
	exit;
}

//1. get book info isbn code
$boolResult = true;
$strIsbnCode = str_replace("-","",$strIsbnCode);
if($strIsbnCode && $strIsbnCode!='' && is_numeric($strIsbnCode)){
	$arrSearch = array();
	$arrSearch['isbn_code'] = $strIsbnCode;
	$arrBook = $objBook->getBook($arrSearch);
	if(!count($arrBook)){
		include($_SERVER['DOCUMENT_ROOT'].'/../Controller/SOMR/ExerciseBook/_Elements/GetBookInfo.php');
		/*
		$strIsbnUrl = "http://nl.go.kr/kolisnet/openApi/open.php?gubun1=ISBN&code1=".$strIsbnCode."&page=1&collection_set=1";
		//get book info
		$xmlstr = $objBook->get_xml_from_url($strIsbnUrl);
		$xmlobj = new SimpleXMLElement($xmlstr);
		$xmlobj = json_decode(json_encode((array)$xmlobj), TRUE);
		$strTitle = $xmlobj['RECORD']['TITLE']?$xmlobj['RECORD']['TITLE']:$xmlobj['RECORD'][0]['TITLE'];
		$strPubName = $xmlobj['RECORD']['PUBLISHER']?$xmlobj['RECORD']['PUBLISHER']:$xmlobj['RECORD'][0]['PUBLISHER'];
		$strPubYear = $xmlobj['RECORD']['PUBYEAR']?$xmlobj['RECORD']['PUBYEAR']:$xmlobj['RECORD'][0]['PUBYEAR'];
		$strCoverUrl = $xmlobj['RECORD']['COVER_URL']?$xmlobj['RECORD']['COVER_URL']:$xmlobj['RECORD'][0]['COVER_URL'];
		$strCoverUrl = $strCoverUrl?$strCoverUrl:"/smart_omr/_images/default_cover.png";
		*/
	}else{
		$boolResult = false;
	}
}

if($strTitle && $strPubName && $boolResult){
	//get sub writer info
	$arrMember = $objMember->getMemberByMemberSeq($strSubWriterSeq);
	$boolResult = $objBook->setBook($intWriterSeq,$arrMember[0]['member_seq'],$strTitle,$strPubName,$strPubDate,$strCoverUrl,$strIsbnCode,$intBookSeq,$intCategorySeq,$strAuthor);
}else{
	$boolResult = false;
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 책 저장 성공 여부
 * @property	string 			$strTitle 		: 제목
 * @property	string 			$strPubDate 		: 출판일
 * @property	string 			$strCoverUrl 		: 커버 이미지 url
 * @property	string 			md5($intBookSeq) 		: 암호화 책 시컨즈
 */
$arr_output = array(
	'title'=>$strTitle,
	'pub_name'=>$strPubName,
	'pub_date'=>$strPubDate,
	'cover_url'=>$strCoverUrl,
	'str_book_seq'=>md5($intBookSeq),
	'boolResult'=>$boolResult
);
echo json_encode($arr_output);

?>