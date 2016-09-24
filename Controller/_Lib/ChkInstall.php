<?
/**
 * Install 여부를 확인
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 2. sns key 체크
 * 3. DB Conn check 및 테이블전재 확인 
 * 4. 관리자 계정 등록 여부 확인 (admin_level=100)
 * 
 * 4가지 모두 적용 완료시 session에 인스톨 체크 flg 값을 1로 설정
 * 4가지중 하나라도 적용이 안되어 있으면 /smart_omr/install redirect
 * */
if(!$_SESSION['mama_install']['install_complete'] || !$_SESSION['mama_install']['admin_complete']){
	//check install 
	if(!file_exists(ini_get("include_path")."/Controller/_Config/MamaOMR.conf.php")){
		header('Location: /smart_omr/install');
		exit;
	}else{
		require_once(CONTROLLER_NAME."/_Config/MamaOMR.conf.php");
		global $API_key;
		if( (!$API_key['naver']['client_secret'] || $API_key['naver']['client_secret']=="") && (!$API_key['facebook']['app_id'] || $API_key['facebook']['app_id']=="") && (!$API_key['kakao']['client_secret'] || $API_key['kakao']['client_secret']=="") ){
			header('Location: /smart_omr/install');
			exit;
		}
		global $DB_info;
		$servername = $DB_info ['MAIN_SERVER']['host'];
		$username = $DB_info ['MAIN_SERVER']['user'];
		$password = $DB_info ['MAIN_SERVER']['pass'];
		$dbname = $DB_info ['MAIN_SERVER']['database'];
		
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			header('Location: /smart_omr/install');
			exit;
		}else{
			$result = $conn->query("SHOW TABLES");
			$intTableCnt = $result->num_rows;
			if(!$intTableCnt){
				header('Location: /smart_omr/install');
				exit;
			}else{
				//관지자 등록 여부 확인
				$result = $conn->query(" select * from member_basic_info where admin_level=100 and del_flg='0' ");
				$intQueryResultCnt = $result->num_rows;
				if(!$intQueryResultCnt){
					header('Location: /smart_omr/install');
					exit;
				}
			}
		}
	}
	/**
	 * 인슬톨이 정상적으로 확인되여 세션에 해당 기록
	 * */
	$_SESSION['mama_install'] = array("install_complete"=>1,"admin_complete"=>1);
}
?>