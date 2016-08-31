<?
class DataHandler{
	var $str_domain;
	function __construct(){
		$arr_domain = explode(".",getenv("HTTP_HOST"));
		$this->str_domain = ".".$arr_domain[count($arr_domain)-2].".".$arr_domain[count($arr_domain)-1];
	}
	function __destruct(){}
	function getUserCookie($arr_cookie=null){
		if($arr_cookie){
			$arr_return = array();
			if(!is_array($arr_cookie)){
				$arr_cookie = array($arr_cookie);
			}
			foreach($arr_cookie as $value){
				// 차후 쿠키 암호화 및 암호 복화 프로세스 추가 할것
				$arr_return[$value] = $_COOKIE[$value];
			}
			return($arr_return);
		}else{
			return(false);
		}
	}
	function setUserCookie($arr_cookie,$time=0){
		foreach($arr_cookie as $key=>$value){
			$result=setcookie($key, $value, $time, "/", $this->str_domain);
			if(!$result){break;}
		}
		return($result);
	}
	function delUserCookie($arr_cookie){
		foreach($arr_cookie as $key=>$value){
			$result=setcookie($key, "", time() - 3600, "/", $this->str_domain);
			if(!$result){break;}
		}
		return($result);
	}
	function getUserSession($arr_session=null){
		if($arr_session){
			$arr_return = array();
			if(!is_array($arr_session)){
				$arr_session = array($arr_session);
			}
			foreach($arr_session as $value){
				// 차후 쿠키 암호화 및 암호 복화 프로세스 추가 할것
				if($_SESSION[$value]){
					$arr_return[$value] = $_SESSION[$value];
				}
			}
			return($arr_return);
		}else{
			return(false);
		}
	}	
	function setUserSession($arr_session){	
		session_start();			
		foreach($arr_session as $key=>$value){
			$result = $_SESSION[$key] = $value;
			if(!$result){break;}
		}
		return($result);		
	}
	function delUserSession($arr_session){
		session_start();
		foreach($arr_session as $value){
			unset($_SESSION[$value]);
		}
		return(true);			
	}
}
?>