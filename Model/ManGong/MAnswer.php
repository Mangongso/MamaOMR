<?
/**
 * 문제 및 문제에 종속되는 보기를 등록, 수정, 삭제, 조회한다.
 * 본 클레스틑 Test/Answer 클레스를 확장한다.
 *
 * @package      	Mangong/MAnswer
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resAnswerDB : DB 커넥션 리소스
 * @category     	Answer
 */

require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class MAnswer{
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resAnswerDB = $resMangongDB;
	}
	/**
	 * 답안 제출 시 유저의 답을 저장
	 *
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $strQuestionAnswer 문제 시퀀스
	 * 
	 * @return mix  답안 저장 성공여부 또는 답안저장 Insert 시컨스 번호 반화  boolean 또는 integer
	 */
	public function setUserAnswer($intMemberSeq,$intTestsSeq,$intQuestionSeq,$strQuestionAnswer,$userAnswer,$result_flg,$strUserName,$sex,$intScore){
		include("Model/ManGong/SQL/MySQL/MAnswer/setUserAnswer.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		if($boolResult){
			$mixReturn = mysql_insert_id($this->resAnswerDB->res_DB);
		}else{
			$mixReturn = $boolResult;
		}
		return($mixReturn);		
	}
	
	/**
	 * 서술식 답안 저장
	 *
	 * @param integer $intUserAnswerSeq 유저정답 시컨즈
	 * @param integer $intTestSeq 테스트 시컨즈
	 * @param integer $intRecordSeq 기록 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intMemberSeq 유저 시퀀스
	 * @param string $strUserAnswer 유저 서술답
	 * 
	 * @return boolean  서술식 답안 저장 
	 */
	public function setUserAnswerDiscus($intUserAnswerSeq,$intTestSeq,$intRecordSeq,$intQuestionSeq,$intMemberSeq,$strUserAnswer){
		include("Model/ManGong/SQL/MySQL/MAnswer/setUserAnswerDiscus.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 유저 답안을 가져온다
	 *
	 * @param integer $intMemberSeq 유저 시퀀스
	 * @param integer $intTestSeq 테스트 시컨즈
	 * @param integer $intQuestionCount 문제 개수
	 * @param integer $intRecordSeq 기록 시퀀스
	 * @param array $arrQuestionType 문제 유형
	 *
	 * @return array  user_table table 참조
	 */
	public function getUserAnswer($intMemberSeq,$intTestsSeq,$intQuestionCount=null,$intRecordSeq=null,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11,10,20)){
		include("Model/ManGong/SQL/MySQL/MAnswer/getUserAnswer.php");
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 유저 답안을 답안 시컨즈를 기준으로 가져온다
	 *
	 * @param integer $intMemberSeq 유저 시퀀스
	 * @param integer $mixAnswerSeq 답안 시퀀스 (암호화 string 또는 integer )
	 *
	 * @return array  user_table table 참조
	 */
	public function getUserAnswerByAnswerSeq($strMemberSeq,$mixAnswerSeq){
		include("Model/ManGong/SQL/MySQL/MAnswer/getUserAnswerByAnswerSeq.php");
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 전체 유저 답안을 가져온다
	 *
	 * @param integer $intMemberSeq 유저 시퀀스
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intRecodeSeq 기록 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * 
	 * @return array  user_table , test_question_list table 참조
	 */
	public function getUserAnswerTotal($intMemberSeq,$intTestsSeq,$intRecodeSeq=0,$intQuestionSeq=null){
		include("Model/ManGong/SQL/MySQL/MAnswer/getUserAnswerTotal.php");
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);		
	}
}
?>