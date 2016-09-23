<?php
/**
 * 유저 정보를 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Member/Membert
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resMemberDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Member
 */
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Member{
	private $objPaging;
	public $resMemberDB;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMemberDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	function __construct($resMemberDB=null){
		define("JOIN_HANBNC", 1);
		define("JOIN_FACEBOOK", 2);
		define("JOIN_GOOGLE", 3);
		define("JOIN_NAVER", 4);
		define("JOIN_KAKAO", 5);
		define("JOIN_TWITTER", 6);
		
		
		define("JOIN_ERROR_DUPLICATE_ID", 1);
		define("JOIN_ERROR_DUPLICATE_NICKNAME", 2);
		define("JOIN_ERROR_DUPLICATE_EMAIL", 3);
		
		if(!is_null($resMemberDB)){
			$this->resMemberDB = $resMemberDB;
		}
		$this->objPaging = new Paging();
	}
	function __destruct(){}	
	
	/**
	 * 유저 정보 저장
	 *
	 * @param resource $res_DB 리소스 형태의 DB커넥션
	 * @param array $arr_input 배열 형태의 유저 저장 정보 데이터
	 * @param integer $intMemberSeq Insert된 유저 시컨즈를 담는 변수
	 *
	 * @return mix 유저 저장 성공 여부. (false 또는 true) 또는 저장된 유저의 시컨즈 번호를 반환
	 */
	function setMember($res_DB=null,$arr_input=null,&$intMemberSeq=null){
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/MemberRegisterBasic.php");
		}
		if(!$res_DB->DB_access($res_DB,$this->quary["insert_member_basic_info"])){
			$this->result = false;
		}
		$arr_input['member_seq'] = mysql_insert_id($res_DB->res_DB);
		$intMemberSeq = mysql_insert_id();
		include("Model/Member/SQL/MySQL/MemberRegisterExtend.php");
		if($res_DB->DB_access($res_DB,$this->quary["insert_member_extend_info"])){
			$this->result = $arr_input['member_seq'];
		}else{
			$this->result = false;
		}
		return($this->result);
	}
	
	/**
	 * 유저 정보를 유저 ID를 기준으로 조회
	 *
	 * @param string $strMemberID 유저 아이디
	 *
	 * @return array 유저 조회 member_basic_info,member_extend_info table 참조
	 */
	public function getMemberByMemberID($strMemberID){
		include("Model/Member/SQL/MySQL/getMemberByMemberID.php");
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
	/**
	 * 유저 정보를 유저 시컨즈를 기준으로 조회
	 *
	 * @param mixed $mixMemberSeq 유저 시컨즈 또는 md5암호화된 유저 시컨즈
	 *
	 * @return array 유저 조회 member_basic_info,member_extend_info table 참조
	 */
	public function getMemberByMemberSeq($mixMemberSeq){
		include("Model/Member/SQL/MySQL/getMemberByMemberSeq.php");
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
}
?>