<?
/**
 * Install 여부를 확인
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 2. sns key 체크
 * 3. DB Conn check 및 테이블전재 확인 
 * 4. 관리자 계정 등록 여부 확인 (admin_level=100)
 * 
 * */
/**
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * */
$boolResult = true;
if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php")){
//if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf_test.php")){
	$boolResult = false;
	$err_code = 1;
	$arr_output = array('result'=>$boolResult,"err_code"=>$err_code);
	echo json_encode($arr_output);
	exit;
}
/**
 * 2. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * */
global $API_key;
if( (!$API_key['naver']['client_id'] || $API_key['naver']['client_id']=="") && (!$API_key['facebook']['app_id'] || $API_key['facebook']['app_id']=="") && (!$API_key['kakao']['client_id'] || $API_key['kakao']['client_id']=="") ){
	$boolResult = false;
	$err_code = 2;
	$arr_output = array('result'=>$boolResult,"err_code"=>$err_code);
	echo json_encode($arr_output);
	exit;
}
if(!$API_key['book']['book_key'] || $API_key['book']['book_key']==""){
	$boolResult = false;
	$err_code = 4;
	$arr_output = array('result'=>$boolResult,"err_code"=>$err_code);
	echo json_encode($arr_output);
	exit;
}


/**
 * 3. DB Conn check
 * */
global $DB_info;
$servername = $DB_info ['MAIN_SERVER']['host'];
$username = $DB_info ['MAIN_SERVER']['user'];
$password = $DB_info ['MAIN_SERVER']['pass'];
$dbname = $DB_info ['MAIN_SERVER']['database'];

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	//die("Connection failed: " . $conn->connect_error);
	$boolResult = false;
	$err_code = 3;
	$arr_output = array('result'=>$boolResult,"err_code"=>$err_code);
	echo json_encode($arr_output);
	exit;
}else{
	include(CONTROLLER_NAME."/Install/CreateTableQuery.php");
	//echo $sqlQuery;
	foreach($arrTable as $strKey=>$strQuery){
		//create table 
		if ($conn->query($strQuery) === TRUE) {
			//echo "Table MyGuests created successfully";
		} else {
			//echo "Error creating table: " . $conn->error;
		}
	}
	$conn->close();
	$boolResult = true;
	$arr_output = array('result'=>$boolResult);
	echo json_encode($arr_output);
	exit;
}
exit;
?>