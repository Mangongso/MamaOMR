<?
/**
 * 학습매니져 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Mangong/StudentMG
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resStudentMGDB : DB 커넥션 리소스
 * @category     	StudentMG
 */
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class StudentMG{
	private $resStudentMGDB = null;
	private $objPaging = null;
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resStudentMGDB = $resMangongDB;  
	}
	public function __destruct(){}
	
	/**
	 * 학습매니져 수정
	 *
	 * @param integer $intManagerSeq 학습매니져 시컨즈
	 * @param string $strStudentSeq md5유저 시컨즈
	 * @param string $strAuthKey 인증키
	 *
	 * @return boolean  학습매니져 수정 성공 여부 반환 (false|true)
	 */
	public function updateManagerStudent($intManagerSeq,$strStudentSeq,$strAuthKey){
		include("Model/Mangong/SQL/MySQL/StudentMG/updateManagerStudent.php");
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 학습매니져 목록 조회
	 *
	 * @param string $strManagerSeq md5학습매니져 시퀀스
	 * @param string $strStudentSeq md5유저 시컨즈
	 *
	 * @return array  user_table table 참조
	 */
	public function getManagerStudentList($strManagerSeq,$strStudentSeq=null){
		include("Model/Mangong/SQL/MySQL/StudentMG/getManagerStudentList.php");
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 학습매니져를 인증키를 통해 조회
	 *
	 * @param string $strAuthKey 인증키
	 *
	 * @return array  student_manager table 참조
	 */
	public function getManagerStudentByAuthKey($strAuthKey){
		include("Model/Mangong/SQL/MySQL/StudentMG/getManagerStudentByAuthKey.php");
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 학습매니져 저장
	 *
	 * @param integer $intStudentSeq 유저 시컨즈
	 * @param string $strAuthKey 인증키
	 *
	 * @return boolean  학습매니져 저장 성공 여부 반환 (false|true)
	 */
	public function setManagerStudentAuthKey($intStudentSeq,$strAuthKey){
		include("Model/Mangong/SQL/MySQL/StudentMG/setManagerStudentAuthKey.php");
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 학습매니져 인증키 삭제
	 *
	 * @param string $strStudentSeq md5암호화 유저시컨즈
	 * @param string $strAuthKey 인증키
	 * 
	 * @return boolean  학습매니져 인증키 삭제 성공 여부 반환 (false|true)
	 */
	public function deleteManagerStudentAuthKey($strStudentSeq,$strAuthKey){
		include("Model/Mangong/SQL/MySQL/StudentMG/deleteManagerStudentAuthKey.php");
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	
}
?>