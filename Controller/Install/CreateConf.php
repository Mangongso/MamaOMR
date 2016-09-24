<?
/**
 * Install 여부를 확인
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 2. SNS API Key 학인
 * 3. DB Conn check
 * */

/**
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 미존재 시 copy MamaOMR.conf.default.php 파일
 * */
if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php")){
//if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf_test.php")){
	//copy copy MamaOMR.conf.default.php 파일
	copy(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.default.php", ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php");
	///copy(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.default_test.php", ini_get("include_path")."/Controller/_Config/MamaOMR.conf_test.php");
	if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php")){
		$boolResult = false;
	}else{
		$boolResult = true;
	}
	$arr_output = array('result'=>$boolResult);
	echo json_encode($arr_output);
	exit;
}else{
	$boolResult = true;
	$arr_output = array('result'=>$boolResult);
	echo json_encode($arr_output);
	exit;
}
?>