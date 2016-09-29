<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/ManGong/MQuestion.php");
/**
 * 테스트 정보를 등록, 수정, 삭제, 조회한다.
 * 본 클레스틑 Tests/Tests 클레스를 확장한다.
 *
 * @package      	Mangong
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resTestsDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @property 		public object $objQuestion : 문제 객체
 * @category     	Tests
 */


class Test{
	public $objPaging;
	public $resTestsDB;
	public $objQuestion;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resTestsDB = $resMangongDB;
		$this->objQuestion = new MQuestion($resMangongDB);
	}
	public function __destruct(){}
	
	/**
	 * 테스트를 등록한다.
	 *
	 * @param integer $intWriterSeq 테스트 등록 유저 시컨즈
	 * @param integer $intTestsType 테스트 타입
	 * @param string $strSubject 테스트 제목
	 * @param string $strContents 테스트 내용
	 * @param string $intExampleNumberingStyle 예제 넘버림 스타일
	 * @param string &$intTestsSeq 저장된 테스트의 시컨즈 번호를 담는 변수
	 * @param string $strTags 태그
	 * @param integer $intMasterSeq 마스터 시컨즈
	 *
	 * @return mix 테스트 저장 성공 여부. (false 또는 true) 또는 저장된 테스트의 시컨즈 번호를 반환
	 */
	public function setTests($intWriterSeq,$intTestsType,$strSubject,$strContents,$intExampleNumberingStyle,&$intTestsSeq=null,$strTags='',$intMasterSeq=null){
		if(!is_null($intMasterSeq)){
			if($intMasterSeq==''){
				$intSubMasterSeq = '';
			}else{
				$intSubMasterSeq = $intWriterSeq;
				$intWriterSeq = $intMasterSeq;
			}
		}
		include("Model/ManGong/SQL/MySQL/Test/setTests.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		if($boolReturn){
			if(!$intTestsSeq){
				$mixReturn = $intTestsSeq = mysql_insert_id($this->resTestsDB->res_DB);
			}else{
				$mixReturn = $intTestsSeq;
			}
		}else{
			$mixReturn = $boolReturn;
		}
		return($mixReturn);
	}
	
	/**
	 * 테스트 목록을 조회.
	 *
	 * @param mixed $mixTestsSeq md5암호화 테스트 시쿼즈 또는 integer 테스트 시컨즈
	 * @param integer $intWriterSeq 테스트 등록 유저 시컨즈
	 * @param string $passCheckTests false:자신의 테스트일경우만 조회, .true 자신의 테스트가 아니어도 조회가능
	 *
	 * @return array test ,test_published table 참조
	 */
	public function getTests($mixTestsSeq,$intWriterSeq=null,$passCheckTests=null){
		include("Model/ManGong/SQL/MySQL/Test/getTests.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$intTestsSeq = $arrReturn[0]['seq'];
		$arrReturn[0]['publish'] = $this->getTestsPublishInfo($intTestsSeq);
		return ($arrReturn);
	}
	
	/**
	 * 테스트 총 점수를 업데이트.
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈 
	 * @param integer $intPublishedSeq 테스트 Published 시컨즈
	 *
	 * @return boolean 테스트 총 점수 업데이트 성공 여부 반환 true 또는 false
	 */
	public function updateTestTotalScore($intTestSeq,$intPublishedSeq){
		include("Model/ManGong/SQL/MySQL/Test/updateTestTotalScore.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 테스트 문제 목록 과 유저 정답을 함께 조회.
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈 
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param array $arrQuestionType 문제 유형
	 *
	 * @return array test_question_list,question,user_answer table 참조
	 */
	public function getTestQuestionListWithUserAnswer($intTestSeq,$intUserSeq,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11)){
		include("Model/ManGong/SQL/MySQL/Test/getTestQuestionListWithUserAnswer.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 테스트 문제 목록 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param array $arrQuestionType 문제 유형
	 *
	 * @return array test_question_list,question table 참조
	 */
	public function getTestsQuestionList($intTestsSeq,$arrQuestionType=array(1,2,3,4,5,6,7,8,9,11)){
		include("Model/ManGong/SQL/MySQL/Test/getTestsQuestionList.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 테스트 문제 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return array test_question_list,question table 참조
	 */
	public function getTestsQuestion($intTestSeq,$intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/Test/getTestsQuestion.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 테스트 문제 개수 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return integer 테스트 문제 개수를 반환
	 */
	public function getTestsQuestionCount($intTestSeq){
		include("Model/ManGong/SQL/MySQL/Test/getTestsQuestionCount.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	
	/**
	 * 테스트 문제 목록과 문제의 보기를 함께 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intUserSeq 유저 시컨즈
	 * @param array $arrQuestionType 문제 유형
	 * @param integer $intExampleNumberingStyle 문제의 보기 스타일 지정 
	 * @param boolean $existUserAnswerLog 유저정답의 로그존재여부 
	 *
	 * @return array question,question_example table 참조
	 */
	public function getTestQuestionListWithExample($intTestSeq,$intUserSeq=false,$arrQuestionType = array(1,2,3,4,5,6,7,8,9,11),$intExampleNumberingStyle=0,$existUserAnswerLog=false){
		if($intUserSeq && $existUserAnswerLog){
			$arrQuestions = $this->getTestQuestionListWithUserAnswerLog($intTestSeq,$intUserSeq,$arrQuestionType);
		}else if($intUserSeq){
			$arrQuestions = $this->getTestQuestionListWithUserAnswer($intTestSeq,$intUserSeq,$arrQuestionType);
		}else{
			$arrQuestions = $this->getTestsQuestionList($intTestSeq,$arrQuestionType);
		}

		foreach($arrQuestions as $intKey=>$arrQuestion){
			$intExampleCount = constant("QUESTION_TYPE_".$arrQuestion['question_type']."_EXAMPLE_COUNT");
			$arrQuestions[$intKey]['example'] = $this->objQuestion->getQuestionExample($intExampleNumberingStyle,$arrQuestion['question_seq'],$arrQuestion['example_type'],$intExampleCount);
		}
		return($arrQuestions);
	}
	
	/**
	 * 테스트 published 저장
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intPublishSeq 저장된 테스트 published 시컨즈를 담는 변수
	 * @param string $strStartDate 테스트 시작일시
	 * @param string $strFinishDate 테스트 종료일시
	 * @param string $time 테스트 시간
	 * @param integer $intCategorySeq 카테고리 시컨즈
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param integer $intTotalScore 총점 
	 * @param integer $intTestsProgflg 테스트 진행중 여부 설정
	 * @param integer $intTestsPaperType 테스트 페이퍼 타입
	 * @param integer $intRecordViewFlg 성적 보여지기 여부 설정
	 * @param integer $intRepeatFlg 테스트 다시 풀기 가능 여부 (1:가능,0:불가능)
	 * @param integer $intTestViewType 테스트 보여주기 형식 (1: 한문제씩 보여주기,2:전체문제 한번에 보여주기)
	 * @param integer $intDeadlineFlg 테스트 마감일 설정 (1:마감일설정,0:마감일 미설정)
	 * @param integer $intDisplayFlg 테스트를 목록에 노출 설정 (1:노출, 0:비노출) 
	 *
	 * @return boolean  테스트 published 저장 성공여부 반환 true 또는 false
	 */
	public function publishTests($intTestsSeq,$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,&$intPublishSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg=1,$intTestViewType=1,$intDeadlineFlg=1,$intDisplayFlg=1){
		include("Model/ManGong/SQL/MySQL/Test/publishTests.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		$intPublishSeq = mysql_insert_id($this->resTestsDB->res_DB);
		return($boolReturn);
	}
	
	/**
	 * 테스트 published 수정
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intPublishSeq 테스트 published 시컨즈
	 * @param string $strStartDate 테스트 시작일시
	 * @param string $strFinishDate 테스트 종료일시
	 * @param string $time 테스트 시간
	 * @param integer $intCategorySeq 카테고리 시컨즈
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param integer $intTotalScore 총점 
	 * @param integer $intTestsProgflg 테스트 진행중 여부 설정
	 * @param integer $intTestsPaperType 테스트 페이퍼 타입
	 * @param integer $intRecordViewFlg 성적 보여지기 여부 설정
	 * @param integer $intRepeatFlg 테스트 다시 풀기 가능 여부 (1:가능,0:불가능)
	 * @param integer $intTestViewType 테스트 보여주기 형식 (1: 한문제씩 보여주기,2:전체문제 한번에 보여주기)
	 * @param integer $intDeadlineFlg 테스트 마감일 설정 (1:마감일설정,0:마감일 미설정)
	 * @param integer $intDisplayFlg 테스트를 목록에 노출 설정 (1:노출, 0:비노출) 
	 *
	 * @return boolean  테스트 published 수정 성공여부 반환 true 또는 false
	 */
	public function updateTestsPublish($intTestsSeq,$intPublishSeq,$strStartDate,$strFinishDate,$time,$intCategorySeq,$intGroupSeq,$intTotalScore,$intTestsProgflg,$intTestsPaperType,$intRecordViewFlg,$intRepeatFlg=1,$intTestViewType=1,$intDeadlineFlg=1,$intDisplayFlg=1){
		include("Model/ManGong/SQL/MySQL/Test/updateTestsPublish.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 테스트 published 정보를 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intPublishedType published 타입
	 *
	 * @return boolean 자신의 테스트 확인 true 또는 false
	 */
	public function getTestsPublishInfo($intTestsSeq,$intPublishedType=0){
		include("Model/ManGong/SQL/MySQL/Test/getTestsPublishInfo.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 테스트 참여자 총 수를 조회
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param array $arrTestsSeq 테스트 시컨즈 배열
	 *
	 * @return integer 테스트 참여자 수를 반환
	 */
	public function getTestsJoinUserTotalCount($intTestsSeq=null,$arrTestsSeq=array()){
		include("Model/ManGong/SQL/MySQL/Test/getTestsJoinUserTotalCount.php");
		$arrReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($arrReturn[0]['count']);
	}
	
	/**
	 * 유저 시컨즈를 기준으로 테스트 참여자 상태를 저장
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param integer $intPublishSeq 테스트 published 시컨즈
	 * @param integer $intMemberSeq 유저 시컨즈
	 * @param integer $intUserGroupSeq 유저 그룹 시컨즈
	 * @param integer $intStatusFlg 테스트 진행 상태
	 *
	 * @return boolean  테스트 참여자 정보 저장 성공 여부 반환 true 또는 false
	 */
	public function setTestsJoinUserStatusByUserSeq($intTestsSeq,$intPublishSeq,$intMemberSeq,$intUserGroupSeq,$intStatusFlg){
		include("Model/ManGong/SQL/MySQL/Test/setTestsJoinUserStatusByUserSeq.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 테스트 태그를 수정
	 *
	 * @param integer $intTestSeq 테스트 시쿼즈
	 * @param string $strTags 테스트 published 시컨즈
	 *
	 * @return boolean  테스트 태그를 수정 성공 여부 반환 true 또는 false
	 */
	public function updateTestsTags($intTestsSeq,$strTags){
		include("Model/ManGong/SQL/MySQL/Test/updateTestsTags.php");
		$boolReturn = $this->resTestsDB->DB_access($this->resTestsDB,$strQuery);
		return($boolReturn);
	}
	
}
?>