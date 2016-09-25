<?
/**
 * Mysql DB 매니져
 * @property		resource $res_DB : DB 커넥션 리소스
 * @property		string $str_selected_DB : 선택 DB
 * @property		string $query : 쿼리문
 * @property		string $strErrorLocation : 에러위치
 * @category     	PDOmanager
 */
class PDOmanager{
	/**
	DB 연결시 DB_id 를 인자로 받아 해당 연결정보를 처리한다.
	$arr_db_conn_info[database] 값이 있으면 $arr_db_conn_info[database] DB 를 선택한다.
	*/
	var $res_DB = array();
	var $str_selected_DB = "";
	var $query = array();
	var $strErrorLocation = "";
	public $mysqliConn;
	
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
		$this->res_DB = new PDO('mysql:host='.$arr_db_conn_info['host'].';dbname='.$arr_db_conn_info['database'],$arr_db_conn_info['user'],$arr_db_conn_info['pass']);
		$this->res_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!$this->res_DB){
			echo "DB Connect fail"; // DB 연결 실패 했을 경우 처리
			//exit;
			return false;
		}
		if(trim($arr_db_conn_info['database'])){
			$this->DB_name = $arr_db_conn_info['database'];
			global $globalMysqliConn;
			$globalMysqliConn = mysqli_connect($arr_db_conn_info['host'],$arr_db_conn_info['user'],$arr_db_conn_info['pass'],$arr_db_conn_info['database']);
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
		try{
			$res = $DB_link->res_DB->prepare($query);
			$boolReturn = $res->execute();
		} catch (PDOException $e) {
			echo 'Query : ' . $query;
			echo 'Connection failed : ' . $e->getMessage();
			exit;
		}
		if(preg_match("/^select/i",trim($query)) or preg_match("/^show/i",trim($query))){
			if($strArrayKey===false){
				return($this->ResultFetchArray($res));
			}else{
				return($this->ResultFetchArray($res,"ASSOC",$strArrayKey));
			}
		}else{
			return($boolReturn);
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
				$fetch_mode = PDO::FETCH_ASSOC;
			break;
			case("NUM"):
				$fetch_mode = PDO::FETCH_NUM;
			break;
			case("BOTH"):
				$fetch_mode = PDO::FETCH_BOTH;
			break;			
		}
		$arrReturnResult = array();
		while($arr_value = $res_result->fetch($fetch_mode)){
			if($strArrayKey===false){
				array_push($arrReturnResult,$arr_value);
			}else{
				$arrReturnResult[$arr_value[$strArrayKey]] = $arr_value;
			}
		}
		return($arrReturnResult);
	}
}
?>
