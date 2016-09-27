<?
/**
 * 회원의 접근 권한을 관리
 * @subpackage 	Core/DataManager/DataHandler
 * @property		public 	array 		$arrArrowedIp 	: 허용 IP
 * @category     	accessHelper
 *
 */
require_once("Model/Core/DataManager/DataHandler.php");

class accessHelper{
	public $arrArrowedIp;
	public function __construct(){}
	public function __destruct(){}
	
	/**
	 * 허용 host 확인
	 *
	 * @param string 	$strHost : 호스트
	 * @return boolean 	$boolReturn  허용 가능 여부를 반환 true 또는 false
	 */
	public function checkArrowHost($strHost){
		$boolReturn = in_array($strHost,$this->arrArrowedIp);
		return($boolReturn);
	}
	
	/**
	 * 접근 토큰을 가져온다.
	 *
	 * @return string 생성된 토큰을 반환
	 */
	public function getAccessToken(){
		$int = rand(10000,99999);
		$arr_session = array('accessToken'=>md5($int));
		$oDataHandler = new DataHandler();
		$oDataHandler->setUserSession($arr_session);
		return($arr_session[accessToken]);
	}
	
	/**
	 * 접근 토큰 확인
	 * 세션에 저장된 accessToken 과 비교
	 *
	 * @param string 		$token : 접근 토큰 
	 * @return boolean	세션에 저장된 accessToken 과 비교하여 같으면 true 를 반환  
	 */
	public function checkAccessToken($token){
		if($_SESSION['accessToken']===$token){
			$oDataHandler = new DataHandler();
			$arr_session = array("accessToken");
			$oDataHandler->delUserSession($arr_session);	
			return(true);
		}else{
			return(false);
		}
	}	
	
	/**
	 * get_mb_session
	 * @return session
	 */
	public function get_mb_session(){
		session_start();
		$session_name = "z_".substr(session_id(),0,10);
		${$session_name} = $_SESSION[$session_name];
		return ${$session_name};
	}
	
	/**
	 * 로그인 레벨 확인 1
	 * 세션에 저장된 accessToken 과 비교
	 *
	 * @param string 		$member_id : 유저 아이디
	 * @return boolean	$ret_value	확인결과 반환 true | false
	 */
	public function login_check_level_1($member_id){
			session_start();
			$session_name = "z_".substr(session_id(),0,10);
			${$session_name} = $_SESSION[$session_name];
			if(is_array(${$session_name})){
				$ret_value=true;
			}
			else{
				$ret_value=false;
			}

		return($ret_value);
	}
	
	/**
	 * 로그인 레벨 확인 2
	 *
	 * @param 	string 			$member_id : 접근 토큰
	 * @param	resource 		$obj_DB : DB 커넥션 리소스
	 * 
	 * @return boolean	$ret_value	확인결과 반환 true | false
	 */
	public function login_check_level_2($obj_DB,$member_id){
			$str_session = md5(session_id());
			$query = "select count(member_id) from MEMBERS where member_id='$member_id' and session='$str_session'";
			$result = mysql_fetch_array($obj_DB->db_access($query,$obj_DB->res_DB));
			$mb_chk = $result[0];
			$r_addr = getenv("REMOTE_ADDR");
			$query = "select count(member_id) from MEMBERS where member_id='$member_id' and session='$str_session' and ip_address='$r_addr'";
			$result = mysql_fetch_array($obj_DB->db_access($query,$obj_DB->res_DB));
			if($result[0]>0){
				$ret_value=true;
			}
			else{
				$ret_value=false;
			}
		return($ret_value);
	}	
	
	/**
	 * http referer를 쿠키에 저장저장
	 *
	 * @return string	$a_info_fr	 http_referer 정보를 반환
	 */
	public function set_from_ref(){
		$a_info_fr=$_COOKIE[a_info_fr];
		if(!$a_info_fr){
			$a_info_fr = getenv("HTTP_REFERER");
			$arr_cookie_host = explode(".",getenv("HOSTNAME"));
			$cookie_host = ".".$arr_cookie_host[count($arr_cookie_host)-2].".".$arr_cookie_host[count($arr_cookie_host)-1];
			setcookie("a_info_fr", $a_info_fr, 0, "/" , $cookie_host);
		}
		return($a_info_fr);
	}
}
?>