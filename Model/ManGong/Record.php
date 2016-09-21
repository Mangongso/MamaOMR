<?
/**
 * 유저의 성적을 등록, 수정, 삭제, 조회한다
 *
 * @package      	Mangong/Record
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resRecordDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @category     	Record
 */

require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Record{
	private $resRecordDB = null;
	private $objPaging = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resRecordDB = $resMangongDB;
	}
	public function __destruct(){}

	/**
	 * 성적을 저장한다.
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param string $strUserName 유저이름
	 * @param string $strSex 성별
	 * @param string $intUserScore 점수
	 * @param string $intTotalScore 총점
	 * @param string $intRightCount 맞은 개수
	 * @param integer $intWrongCount 틀린 개수
	 * @param integer &$intRecordSeq 성적 Insert 시컨스 담는 변수
	 *
	 * @return boolean 성적 저장 성공 여부. (false 또는 true)
	 */
	public function setUserRecord($intMemberSeq,$intTestsSeq,$strUserName,$strSex,$intUserScore,$intTotalScore,$intRightCount,$intWrongCount,&$intRecordSeq=0){
		include("Model/ManGong/SQL/MySQL/Record/setUserRecord.php");
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		$intRecordSeq = mysql_insert_id($this->resRecordDB->res_DB);
		return($boolResult);
	}
	
	/**
	 * 마지막으로 저장된 성적을 조회 
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return array record table 참조
	 */
	public function getLastRecord($intMemberSeq,$intTestsSeq){
		include("Model/ManGong/SQL/MySQL/Record/getLastRecord.php");
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 마지막으로 저장된 성적 시컨즈 조회
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return integer 성적 시컨즈를 반환
	 */
	public function getLastRecordSeq($intMemberSeq,$intTestsSeq){
		include("Model/ManGong/SQL/MySQL/Record/getLastRecordSeq.php");
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult[0]['seq']);
	}
	
	/**
	 * 끝나지 않은 유저의 성적을 확인 
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 *
	 * @return boolean 끝나지 않은 성적이 있는지 여부를 반환 true 또는 false
	 */
	public function checkNotFinishedUserRecord($intMemberSeq,$intTestsSeq){
		include("Model/ManGong/SQL/MySQL/Record/checkNotFinishedUserRecord.php");
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		if($arrResult[0]['cnt']>0){
			$boolReturn = true;
		}else{
			$boolReturn = false;
		}
		return($boolReturn);
	}
	
	/**
	 * 유저의 성적을 조회
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intRevisionFlg 성적이 저장된 회차(테스트를 푼 회차와 같다)
	 * @param integer $intSortFlg 순서 조건 1:내림차순,0:오름차순
	 *
	 * @return array record table 참조
	 */
	public function getMemberRecords($mixMemberSeq,$intTestsSeq=null,$intRevisionFlg=null,$intSortFlg = 0){
		include("Model/ManGong/SQL/MySQL/Record/getMemberRecords.php");
		$arrResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 유저의 성적을 업데이트
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intUserScore 점수
	 * @param integer $intRightCount 맞은 개수
	 * @param integer $intWrongCount 틀린 개수
	 * @param string $testingTime 테스트 걸린 시간
	 * @param string $intStartDate 시작시간
	 * @param string $intEndDate 종료시간
	 *
	 * @return boolean 유저 성적 업데이트 성공 여부 반환 true 또는 false
	 */
	public function updateUserRecord($intMemberSeq,$intTestsSeq,$intUserScore,$intRightCount,$intWrongCount,$testingTime,$intStartDate=null,$intEndDate=null){
		include("Model/ManGong/SQL/MySQL/Record/updateUserRecord.php");
		$boolResult = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($boolResult);
	}
	
	/**
	 * 유저의 총 성적을 조회
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈 (하나의 테스트 조회)
	 * @param array $arrTestsSeq 테스트 시컨즈 배열 (여러게의 테스트 조회)
	 * @param string $strTestsSeqGroup 테스트 시컨즈 콤마구분 (여러게의 테스트 조회)
	 * @param integer $strUserSeq md5암호화 유저 시컨즈
	 *
	 * @return array  record table 참조
	 */
	public function getTotalUserRecord($intTestsSeq=null,$arrTestsSeq=array(),$strTestsSeqGroup=null,$strUserSeq=null){
		include("Model/ManGong/SQL/MySQL/Record/getTotalUserRecord.php");
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * tag별 테스트 성적 리포트 조회
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈 (하나의 테스트 조회)
	 * @param integer $intRecordSeq 성적 시컨즈
	 *
	 * @return array  question_tag,user_answer table 참조
	 */
	public function getTestsRecordReportByTags($intTestsSeq,$intRecordSeq){
		include("Model/ManGong/SQL/MySQL/Record/getTestsRecordReportByTags.php");
		$arrReturn = $this->resRecordDB->DB_access($this->resRecordDB,$strQuery);
		return($arrReturn);
	}
}
?>