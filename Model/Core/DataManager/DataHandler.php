<?
/**
 * 쿠키 또는 세션 데이터를 컨트롤 함
 * @property		string $str_domain http host 도메인의 정보 담는다.
 * @category     	DataHandler
 */
class DataHandler{
	var $str_domain;
	
	/**
	 * 생성자
	 * @return null
	 */
	function __construct(){
		$arr_domain = explode(".",getenv("HTTP_HOST"));
		$this->str_domain = ".".$arr_domain[count($arr_domain)-2].".".$arr_domain[count($arr_domain)-1];
	}
	function __destruct(){}
	
	/**
	 * 유저의 쿠키정보를 가져온다.
	 * @param array $arr_cookie 쿠키
	 * @return array $arr_return 변수에 쿠키 정보를 담아서 반환
	 */
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
	
	/**
	 * 유저의 쿠키정보를 저장
	 * @param array $arr_cookie 쿠키
	 * @param integer $time 시간
	 * @return boolean $result 쿠키 저장 성공 여부
	 */
	function setUserCookie($arr_cookie,$time=0){
		foreach($arr_cookie as $key=>$value){
			$result=setcookie($key, $value, $time, "/", $this->str_domain);
			if(!$result){break;}
		}
		return($result);
	}
	
	/**
	 * 유저의 쿠키정보를 삭제
	 * @param array $arr_cookie 쿠키
	 * @return boolean $result 쿠키 삭제 성공 여부
	 */
	function delUserCookie($arr_cookie){
		foreach($arr_cookie as $key=>$value){
			$result=setcookie($key, "", time() - 3600, "/", $this->str_domain);
			if(!$result){break;}
		}
		return($result);
	}
	
	/**
	 * 유저의 세션정보를 가져옴
	 * @param array $arr_session 세션
	 * @return array $arr_return 변수에 세션 정보를 담아서 반환
	 */
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
	
	/**
	 * 유저의 세션정보를 저장
	 * @param array $arr_session 세션
	 * @return boolean $result 세션 저장 성공 여부
	 */
	function setUserSession($arr_session){	
		session_start();			
		foreach($arr_session as $key=>$value){
			$result = $_SESSION[$key] = $value;
			if(!$result){break;}
		}
		return($result);		
	}
	
	/**
	 * 유저의 세션정보를 삭제
	 * @param array $arr_session 세션
	 * @return boolean $result 세션 삭제 성공 여부
	 */
	function delUserSession($arr_session){
		session_start();
		foreach($arr_session as $value){
			unset($_SESSION[$value]);
		}
		return(true);			
	}
}
?>