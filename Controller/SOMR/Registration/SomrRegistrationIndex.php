<?
/**
 * @Controller 메일 발송 
 *
 * @package      	Mangong/Tag
 * @package      	Mangong/StudentMG
 * @subpackage   	Core/DataManager/DataHandler
 * @subpackage   	Core/Mail/MailHandler
 * @property		private resource $resTagDB : DB 커넥션 리소스
 * @category     	Tag
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');

/**
 * Variable 세팅
 * @var 	$strMemberSeq		md5암호화 유저 시컨즈
 * @var 	$strSenderEmail		발송자 이메일
 * @var 	$strReceiverEmail	수신자 이메일
 * @var 	$strContents		메일내용
 * @var 	$strMailType		메일 형식
 */
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);

/*main process*/
//1. get main header
$intQueId = 1;//main header
//$arrHeader = $objTest->getTestByQue($intQueId);

//2. get test list
//$arrTest = $objTest->getTestListBy($intMemberSeq, $strMemberType);


//$arrPaging = array('page'=>$intPage ,'result_number'=>15,'block_number'=>10,'param'=>null);
if($arrPaging){
	$arr_output['paging'] = $arrPaging;
	if(!is_null($intCategorySeq) && !is_null($intGroupSeq)){
		$arr_output['paging']['paging']['prev']['link'] = '/teacher/test/list?page='.(int)$arr_output['paging']['paging']['prev']['number'].'&category='.$intCategorySeq.'&group='.$intGroupSeq;
		$arr_output['paging']['paging']['next']['link'] = '/teacher/test/list?page='.(int)$arr_output['paging']['paging']['next']['number'].'&category='.$intCategorySeq.'&group='.$intGroupSeq;
	}else{
		$arr_output['paging']['paging']['prev']['link'] = '/teacher/test/list?page='.(int)$arr_output['paging']['paging']['prev']['number'];
		$arr_output['paging']['paging']['next']['link'] = '/teacher/test/list?page='.(int)$arr_output['paging']['paging']['next']['number'];
	}
	foreach($arr_output['paging']['paging']['page'] as $page_key=>$page_value){
		if($intCategorySeq || $intGroupSeq){
			$arr_output['paging']['paging']['page'][$page_key]['link'] = '/teacher/test/list?page='.(int)$page_value['number'].'&category='.$intCategorySeq.'&group='.$intGroupSeq;
		}else{
			$arr_output['paging']['paging']['page'][$page_key]['link'] = '/teacher/test/list?page='.(int)$page_value['number'];
		}
	}
}

/* make output */
$arr_output['test_list'] = $arrTestListResult;
$arr_output['category'] = $arrCategoryResult;
$arr_output['category_name'] = $arrCategoryName;
$arr_output['select_category'] = $intCategorySeq;
?>