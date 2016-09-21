<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Test.php');
require_once('Model/Member/Member.php');

/* set variable */ 
$intMemberSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$strAuthKey = $_SESSION[$_COOKIE['member_token']]['auth_key'];
$strMemberType = $_SESSION[$_COOKIE['member_token']]['member_type'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objTest = new Test($resMangongDB);
$objMember = new Member($resMangongDB);

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