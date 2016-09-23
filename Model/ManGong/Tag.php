<?
/**
 * 문제의 태그를 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Mangong/Tag
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resTagDB : DB 커넥션 리소스
 * @category     	Tag
 */

require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Tag{
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resTagDB = $resMangongDB;
	}
	
	public function __destruct(){}
	
	/**
	 * 태그 정보 저장
	 *
	 * @param string $strTagName 태그명
	 * @param integer $intTagType 태그 타입
	 * @param integer $intMemberSeq 유저 시컨즈
	 *
	 * @return boolean  태그 정보 저장 성공 여부 반환 true 또는 false
	 */
	public function setTag($strTagName,$intTagType=2,$intMemberSeq){
		include("Model/ManGong/SQL/MySQL/Tag/setTag.php");
		$boolResult = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolResult);
	}
	
	/**
	 * 테스트 태그 정보 저장
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param string $strTag 태그명
	 *
	 * @return boolean  테스트 태그 정보 저장 성공 여부 반환 true 또는 false
	 */
	public function setTestsTag($intTestsSeq,$strTag){
		include("Model/ManGong/SQL/MySQL/Tag/setTestsTag.php");
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	
	/**
	 * 문제의 태그 정보 저장
	 *
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param string $strTag 태그명
	 *
	 * @return boolean  문제의 태그 정보 저장 성공 여부 반환 true 또는 false
	 */
	public function setQuestionTag($intQuestionSeq,$strTag){
		include("Model/ManGong/SQL/MySQL/Tag/setQuestionTag.php");
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	
	/**
	 * 문제의 태그 정보 삭제
	 *
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param string $strTag 태그명
	 *
	 * @return boolean  문제의 태그 정보 삭제 성공 여부 반환 true 또는 false
	 */
	public function deleteQuestionTag($intQuestionSeq,$strTag=null){
		include("Model/ManGong/SQL/MySQL/Tag/deleteQuestionTag.php");
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
}
?>