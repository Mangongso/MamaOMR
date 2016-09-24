<?
/**
 * Mysql DB 매니져
 * @property		resource $res_DB : DB 커넥션 리소스
 * @property		string $str_selected_DB : 선택 DB
 * @property		string $query : 쿼리문
 * @property		string $strErrorLocation : 에러위치
 * @category     	DB_MySQL_manager
 */
class DB_MySQL_manager{
	/**
	DB 연결시 DB_id 를 인자로 받아 해당 연결정보를 처리한다.
	$arr_db_conn_info[database] 값이 있으면 $arr_db_conn_info[database] DB 를 선택한다.
	*/
	var $res_DB = array();
	var $str_selected_DB = "";
	var $query = array();
	var $strErrorLocation = "";
	
	/**
	 * 생성자
	 * DB_conn함수를 통해 DB 커넥션을 맺음
	 * utf8로 인코딩을 세팅함.
	 * 
	 * @param string $DB_id : DB ID
	 * @return null
	 */
	function __construct($DB_id){
		//$this->DB_conn($DB_id);
		if($this->DB_conn($DB_id)){
			$this->setEncoding("utf8");
		}
	}
	
	/**
	 * DB 인코딩 세팅
	 *
	 * @param string $str_encoding : 인코딩
	 * @return null
	 */
	function setEncoding($str_encoding){
		$query = sprintf("set NAMES %s",$str_encoding);
		$this->DB_access($this,$query);
		if(!preg_match("/^(http:\/\/".$_SERVER["HTTP_HOST"].")/i",$_SERVER["HTTP_REFERER"]) && $_SESSION['referer']!=$_SERVER["HTTP_REFERER"]){
			$_SESSION['referer'] = $_SERVER["HTTP_REFERER"];
			// $strQuery = sprintf("insert into _autopub.ap_referer set create_date='%s',referer='%s',create_time=now(),ip_address='%s'",date("Y-m-d"),$_SERVER["HTTP_REFERER"],$_SERVER["REMOTE_ADDR"]);
			// $this->DB_access($this,$strQuery);			
		}	
	}
	
	/**
	 * DB 연결
	 *
	 * @param string $DB_id : DB link 정보
	 * @return boolean DB 연결 성공 여부 반환
	 */
	function DB_conn($DB_id){
		global $DB_info;
		$arr_db_conn_info = array();
		$arr_db_conn_info = $DB_info[$DB_id];
		$this->res_DB = mysql_connect($arr_db_conn_info['host'],$arr_db_conn_info['user'],$arr_db_conn_info['pass'],true);
		if(!$this->res_DB){
			//echo "DB Connect fail"; // DB 연결 실패 했을 경우 처리
			//exit;
			return false;
		}
		if(trim($arr_db_conn_info['database'])){
			mysql_select_db($arr_db_conn_info['database']);
			$this->DB_name = $arr_db_conn_info['database'];
		}
		return(true);
	}
	
	/**
	 * DB 접속
	 *
	 * @param resource $DB_link : DB link 정보
	 * @param string $query : 쿼리문
	 * @param string $strArrayKey : key 정보
	 * @return boolean DB 접속 성공 여부 반환
	 */
	function DB_access($DB_link,$query,$strArrayKey = false){
		// mysql_select_db($DB_link->DB_name);
		$result=mysql_query($query,$DB_link->res_DB);
		if(DEBUG_MODE == 1){
			$strLogfile = $_SERVER['DOCUMENT_ROOT']."/../Logs/DBQueryLogs.txt"; 
			$resLogFile = fopen($strLogfile,"a+");
			fwrite($resLogFile, "\r\n-------".date("Y.m.d H:i:s")."----------\r\n");
			fwrite($resLogFile, $query);
		}
		if(!$result){
			if(DEBUG_MODE == 1){
				fwrite($resLogFile,mysql_errno($DB_link->res_DB).mysql_error($DB_link->res_DB));
				fclose($resLogFile);
			}else{
				die(mysql_error($DB_link->res_DB)."[".mysql_errno($DB_link->res_DB)."]"."<p>".$query."</p>");
			}
		}
		else{
			if(DEBUG_MODE == 1){
				fclose($resLogFile);
			}			
			if(preg_match("/^select/i",trim($query)) or preg_match("/^show/i",trim($query))){
				if($strArrayKey===false){
					return($this->ResultFetchArray($result));
				}else{
					return($this->ResultFetchArray($result,"ASSOC",$strArrayKey));
				}
			}else{
				return($result);
			}
		}		
	}
	
	/**
	 * DB에서 가져온 DATA를 array로 가공해줌.
	 *
	 * @param resource $res_result : DB 조회 결과값
	 * @param string $fetch_mode : 쿼리문
	 * @param string $strArrayKey : key 정보
	 *
	 * @return boolean DB 커넥션 성공 여부 반환
	 */
	function ResultFetchArray($res_result,$fetch_mode = "ASSOC",$strArrayKey = false){
		// fetch mode : ASSOC, NUM, BOTH
		switch($fetch_mode){
			default:
				$fetch_mode = MYSQL_ASSOC;
			break;
			case("NUM"):
				$fetch_mode = MYSQL_NUM;
			break;
			case("BOTH"):
				$fetch_mode = MYSQL_BOTH;
			break;			
		}
		$arr_result = array();
		while($arr_value = mysql_fetch_array($res_result,$fetch_mode)){
			if($strArrayKey===false){
				array_push($arr_result,$arr_value);
			}else{
				$arr_result[$arr_value[$strArrayKey]] = $arr_value;
			}
		}
		return($arr_result);
	}
}
?>
