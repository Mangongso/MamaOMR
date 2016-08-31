<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');

function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}

/* set variable */ 
$strIsbnCode = trim($_REQUEST['isbn_code']);

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);

/*main process*/
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

/* make output */
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