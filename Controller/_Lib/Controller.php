<?
/**
 * View 와 Controller를 Mapping 해줌
 * @param string	$viewID 컨트롤러 구분
 * */
if( $viewID=="INSTALL" || $_REQUEST['viewID']=="CREATE_CONF" || $viewID=="INSTALL_ADM_SETTING" ){
}else{
	if(($viewID!="SOCIAL_AUTH"&&$_REQUEST['admin_flg']!=1)){
		include(CONTROLLER_NAME."/_Lib/ChkInstall.php");
	}
	require_once(CONTROLLER_NAME."/_Config/MamaOMR.conf.php");
}
if(empty($viewID)){
	$viewID=$_POST['viewID']?$_POST['viewID']:$_GET['viewID'];
}
ini_set("register_globals","off");
$arr_output = array();

// 업로드 디렉터리 정의
if(version_compare(phpversion(),"5.0","<")){
	mb_internal_encoding("UTF-8");
}
require_once("Model/Core/Functions/Functions.php");
// 메핑 적용
if(empty($viewID)){
	$viewID = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');	
}
if(!empty($viewID)){
	require_once("Model/Core/Mapper/ControllerMapping.php");
	$obj_ControllerMapping = new ControllerMapping($XML_mapping_file);
	$arr_output = $obj_ControllerMapping->Connect($viewID);
}
//$arr_output[login_info] = $arr_login_info;
$arr_output['browser'] = browser_check();
$arr_output['device'] = checkDevice();
// 오류 확인 및 디버깅
//if($obj_ControllerMapping->arr_mapping_info['controller'][0]['presentation-name']){
//	 require_once(CONTROLLER_NAME."/Lib/Debug.php");
//}
?>