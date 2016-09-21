<?
/**
 * @Controller 테스트 정답 제출
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/Test
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');

/**
 * Variable 세팅
 * @var 	$intMemberSeq		md5암호화 유저 시컨즈
 * @var 	$strSenderEmail		발송자 이메일
 * @var 	$strReceiverEmail	수신자 이메일
 * @var 	$strContents		메일내용
 * @var 	$strMailType		메일 형식
 */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			Member  				: Member 객체
 * @property	object 		StudentMG 					: StudentMG 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);

/**
 * Main Process
 */
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

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 메일 발송 결과 성공 여부
 * @property	string 			$strMessage 		: 발송 결과 메세지
 */
$arr_output['test_list'] = $arrTestListResult;
$arr_output['category'] = $arrCategoryResult;
$arr_output['category_name'] = $arrCategoryName;
$arr_output['select_category'] = $intCategorySeq;
?>