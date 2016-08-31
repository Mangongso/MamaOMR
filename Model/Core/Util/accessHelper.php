<?
require_once("Model/Core/DataManager/DataHandler.php");

class accessHelper{
	public $arrArrowedIp;
	public function __construct(){}
	public function __destruct(){}
	public function checkArrowHost($strHost){
		$boolReturn = in_array($strHost,$this->arrArrowedIp);
		return($boolReturn);
	}
	public function getAccessToken(){
		$int = rand(10000,99999);
		$arr_session = array('accessToken'=>md5($int));
		$oDataHandler = new DataHandler();
		$oDataHandler->setUserSession($arr_session);
		return($arr_session[accessToken]);
	}
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
	public function get_mb_session(){
		session_start();
		$session_name = "z_".substr(session_id(),0,10);
		${$session_name} = $_SESSION[$session_name];
		return ${$session_name};
	}
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