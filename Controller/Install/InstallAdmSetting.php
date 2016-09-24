<?
/**
 * Install 여부를 확인
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 2. SNS API Key 학인
 * 3. DB Conn check
 * */
$intConfStatus = 1;
$intSnsStatus = 1;
$intDBStatus = 1;
/**
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * */
if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php")){
//if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf_test.php")){
	$intConfStatus = 0;
}

/**
 * 2. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * */
global $API_key;
if( (!$API_key['naver']['client_secret'] || $API_key['naver']['client_secret']=="") && (!$API_key['facebook']['app_id'] || $API_key['facebook']['app_id']=="") && (!$API_key['kakao']['client_secret'] || $API_key['kakao']['client_secret']=="") ){
	$intSnsStatus = 0;
}

/**
 * 3. DB Conn check
 * */
global $DB_info;
$servername = $DB_info ['MAIN_SERVER']['host'];
$username = $DB_info ['MAIN_SERVER']['user'];
$password = $DB_info ['MAIN_SERVER']['pass'];
$dbname = $DB_info ['MAIN_SERVER']['database'];

/*
$conn = mysqli_connect($servername, $username, $password, $dbname);
echo "<pre>";var_dump($conn);echo "<pre>";
exit;
*/
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	//die("Connection failed: " . $conn->connect_error);
	$intDBStatus = 0;
}else{
	$result = $conn->query("SHOW TABLES");
	$intTableCnt = $result->num_rows;
	//echo $intTableCnt;
}

if(!$intConfStatus || !$intSnsStatus || !$intDBStatus){
	header('Location: /smart_omr/install');
	exit;
}

$arr_output['$API_key'] = $API_key;
?>