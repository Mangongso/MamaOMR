<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
/**
 * 문제 및 문제에 종속되는 보기를 등록, 수정, 삭제, 조회한다.
 *
 * @package      	Tests
 * @subpackage   	Core\Util\Paging
 * @subpackage   	Core\DataManager\DataHandler
 * @property		private resource $resAnswerDB : DB 커넥션 리소스
 * @property 		private object $objPaging : 페이징 객체
 * @category     	Answer
 */

class Answer{ 
	private $resAnswerDB = null;
	private $objPaging = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resAnswerDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resAnswerDB=null){
		$this->objPaging =  new Paging();
		//$this->objMember =  new Member($resAnswerDB);
		$this->resAnswerDB = $resAnswerDB;
	}
	
	/**
	 * 소멸자
	 */
	public function __destruct(){}
	
	/**
	 * 답을 저장
	 *
	 * @param string  $strUserEmail 유저 이메일
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intUserAnswerExampleSeq 유저답예제시컨즈
	 * @param integer $intUserAnswerMatrixExampleSeq 유저답 메트릭스 예제 시컨즈
	 * @param string  $strUserAnswer 유저 선택답
	 * @param integer $intAnswerType 답 형식
	 * 
	 * @return mix  답안 저장 성공여부 또는 답안저장 Insert 시컨스 번호 반화  boolean 또는 integer
	 */
	public function setAnswer($strUserEmail,$intTestsSeq,$intPublishedSeq,$intQuestionSeq,$intUserAnswerExampleSeq,$intUserAnswerMatrixExampleSeq,$strUserAnswer,$intAnswerType=0){
		include("Model/Tests/SQL/MySQL/Answer/setAnswer.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		if($boolResult){
			$mixReturn = mysql_insert_id($this->resAnswerDB->res_DB);
		}else{
			$mixReturn = $boolResult;
		}
		return($mixReturn);		
	}
	
	/**
	 * 모든 답을 삭제
	 *
	 * @param string  $strUserEmail 유저 이메일
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 *
	 * @return boolean 답안 삭제 성공여부
	 */
	public function deleteAllAnswer($strUserEmail, $intTestsSeq, $intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Answer/deleteAllAnswer.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);
	}	
	
	/**
	 * 모든 답을 테스트 시컨즈와 퍼블리쉬 시컨즈를 기준으로 삭제 
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return boolean 답안 삭제 성공여부
	 */
	public function deleteAnswerInPublished($intTestsSeq, $intPublishedSeq, $intQuestionSeq = 0){
		include("Model/Tests/SQL/MySQL/Answer/deleteAnswerInPublished.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);
	}	
	
	/**
	 * 답안 시컨즈를 기준으로 답안 삭제
	 *
	 * @param integer $intAnswerSeq 답안 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return boolean 답안 삭제 성공여부
	 */
 	public function deleteAnswer($intAnswerSeq){
 		include("Model/Tests/SQL/MySQL/Answer/deleteAnswer.php");
 		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
	}
	
	/**
	 * 유저 선택답 이유 저장
	 *
	 * @param integer $intAnswerSeq 유저 선택답 시컨즈
	 * @param integer $strReason 답에대한 이유
	 * @param integer $intReasonSeq 이유 시컨즈
	 * 
	 * @return boolean  이유 저장 성공여부
	 */
	public function setAnswerReason($intAnswerSeq,$strReason,$intReasonSeq=null){
		include("Model/Tests/SQL/MySQL/Answer/setAnswerReason.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		if($boolResult){
			if(is_null($intReasonSeq)){
				$mixReturn = mysql_insert_id($this->resAnswerDB->res_DB);
			}else{
				$mixReturn = $intReasonSeq;
			}
		}else{
			$mixReturn = $boolResult;
		}
		return($mixReturn);
	}
	
	/**
	 * 유저 선택답 이유 삭제
	 *
	 * @param integer $intReasonSeq 이유 시컨즈
	 *
	 * @return boolean  이유 삭제 성공여부
	 */
	public function deleteAnswerReason($intReasonSeq){
		include("Model/Tests/SQL/MySQL/Answer/deleteAnswerReason.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);	
		return($boolResult);
	}
	
	/**
	 * 유저 답 Summary Count
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 퍼블리시 시컨즈
	 *
	 * @return array 유저 답 Summary Count를 각가 array에 답아서 반환
	 */
	public function getAnswerCountSummary($intTestsSeq,$intPublishedSeq=null){
		$arrReturn = array();
		include("Model/Tests/SQL/MySQL/Answer/getAnswerCountSummary.php");
		// all count
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery1);
		$arrReturn['total'] = $arrResult[0]['cnt'];
		
		// question count
		
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery2);
		$arrReturn['summary_total'] = $arrResult[0]['cnt'];

		if($intPublishedSeq){
			// published count
			$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery3);
		}
		$arrReturn['published_total'] = $arrResult[0]['cnt'];
				
		return($arrReturn);
	}	
	
	/**
	 * 유저 선택답 count 
	 *
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intExampleSeq 예제 시컨즈
	 * @param integer $intMatrixExampleSeq 메트릭스 예제 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 *
	 * @return integer  유저 선택답 count를 반환
	 */
	public function getAnswerCount($intQuestionSeq,$intExampleSeq,$intMatrixExampleSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Answer/getAnswerCount.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult[0]['cnt']);		
	}
	
	/**
	 * 문제 시컨즈를 기준으로 유저 선택답 count
	 *
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 *
	 * @return integer  유저 선택답 count를 반환
	 */
	public function getAnswerCountByQuestionSeq($intQuestionSeq,$intTestsSeq=null,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Answer/getAnswerCountByQuestionSeq.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult[0]['cnt']);		
	}
	
	/**
	 * 그룹별 유저 선택답 count
	 *
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intPublishedSeq 테스트 publish 시컨즈
	 *
	 * @return array  그럽별 유저 선택답 count를 반환
	 */
	public function getAnswerGroupCount($intQuestionSeq,$intExampleSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Answer/getAnswerGroupCount.php");
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);
	}
}
?>