<?
require_once(dirname ( __file__)."/ChkInstall.php");
if (count($arrConfFailCnt) > 0) {
	$boolResult = false;
} else {
	// DB Create QUERY 설정
	include (CONTROLLER_NAME . "/Install/CreateTableQuery.php");
	
	// check installed
	$arrResult = $resMangongDB->DB_access($resMangongDB, "show tables");
	if(count($arrResult)==0){
		// Table 생성
		foreach($arrTable as $strKey=>$strQuery){
			//create table
			if (!$resMangongDB->DB_access($resMangongDB, $strQuery)){
				$boolResult = false;
				break;
			}else{
				$boolResult = true;
			}
			$flgInstalled = false;
		}		
	}else{
		$boolResult = true;
		$flgInstalled = true;
	}
}
$arr_output = array('installed'=>$flgInstalled,'error_cnt'=>count($arrConfFailCnt),'error_no'=>join(',',$arrConfFailCnt),'result'=>$boolResult);
echo json_encode($arr_output);
?>