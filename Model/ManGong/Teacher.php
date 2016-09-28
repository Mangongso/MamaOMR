<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Member/Member.php");
/**
 * 마마 OMR 선생님 정보를 등록, 수정, 삭제, 조회한다.
 * 본 클레스틑 Member/Member 클레스를 확장한다.
 *
 * @package      	Mangong/Teacher
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resTeacherDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Member
 */

class Teacher extends Member{
	private $resTeacherDB = null;
	private $objPaging = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resTeacherDB = $resMangongDB;
	}
	public function __destruct(){}
	
	/**
	 * 선생님 정보를 조회
	 *
	 * @param integer $intTeacherSeq 마마OMR 대표 선생님 시컨즈 ("/Controller/_Lib/Constant.php" 파일에 define되어 있음)
	 *
	 * @return array member_basic_info,member_extend_info tablel 참조
	 */
	public function getTeacher($intTeacherSeq){
		include("Model/ManGong/SQL/MySQL/Teacher/getTeacher.php");
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
}
?>