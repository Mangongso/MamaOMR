<?
/**
 * @Controller Book 확인
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Book
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');

function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}

/**
 * Variable 세팅
 * @var 	$strIsbnCode	 	isbn 코드
 */   
$strIsbnCode = trim($_REQUEST['isbn_code']);

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objBook  				: Book 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);

/**
 * Main Process
 */
//1. get book info isbn code
$arrSearch = array();
$arrSearch['isbn_code'] = $strIsbnCode;
$arrBook = $objBook->getBook($arrSearch);

$boolResult = true;
$strIsbnCode = str_replace("-","",$strIsbnCode);
if($strIsbnCode && $strIsbnCode!='' && is_numeric($strIsbnCode)){
	if(!count($arrBook)){
		
		include($_SERVER['DOCUMENT_ROOT'].'/../Controller/SOMR/ExerciseBook/_Elements/GetBookInfo.php');
		
		if(!$strTitle && !$strPubName){
			$boolResult = false;
			$err_code = 1;
			$err_msg = "ISBN 코드번호를 확인하세요";
		}
	}else{
		$boolResult = false;
		$err_code = 2;
		$err_msg = "이미등록된 ISBN 코드번호 입니다.";
	}
	
}else{
	$boolResult = false;
	$err_code = 1;
	$err_msg = "ISBN 코드번호를 확인하세요";
}

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$strTitle 			: 제목
 * @property	string 			$strPubName 		: 출반사
 * @property	string 			$strPubDate 		: 출판일
 * @property	string 			$strCoverUrl 		: 커버이미지Url
 * @property	string 			$strAuthor 		: 저자
 * @property	string 			$boolResult 		: isb 코드 확인 결과 
 * @property	string 			$err_code 		: 에러코드
 * @property	string 			$err_msg 		: 에러 메세지
 */
$arr_output = array(
	'title'=>$strTitle,
	'pub_name'=>$strPubName,
	'pub_date'=>$strPubDate,
	'cover_url'=>$strCoverUrl,
	'author'=>$strAuthor,
	'boolResult'=>$boolResult,
	'err_code'=>$err_code,
	'err_msg'=>$err_msg
);
echo json_encode($arr_output);
?>