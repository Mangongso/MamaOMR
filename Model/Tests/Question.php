<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Answer.php");

/**
 * 문제 및 문제에 종속되는 보기를 등록, 수정, 삭제, 조회한단.
 *
 * @package      	Tests
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		public resource $resQuestionDB : DB 커넥션 리소스
 * @property 		public object $objPaging : 페이징 객체
 * @property 		public object $objAnswer : Answer 객체
 * @category     	Question
 */
class Question{
	public $resQuestionDB = null;
	public $objPaging = null;
	public $objAnswer = null;
	
	/**
	 * 생성자
	 *
	 * @param resource $resQuestionDB 리소스 형태의 DB커넥션
	 * @param resource $resAnswerDB 리소스 형태의 DB커넥션
	 * @return null
	 */
	public function __construct($resQuestionDB=null,$resAnswerDB=null){
		$this->objPaging =  new Paging();
		$this->resQuestionDB = $resQuestionDB;
		$this->objAnswer = new Answer($resAnswerDB);
	}
	
	/**
	 * 소멸자
	 */
	public function __destruct(){}
	
	/**
	 * 문제 목록을 가져온다
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestion($intQuestionSeq,$intPublishedSeq=null){
		//get question (with jimoon)
		include("Model/Tests/SQL/MySQL/Question/getQuestion.php");
		$arrQuestionResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		$arrQuestionResult[0]['arr_question_tag'] = $this->getQuestionTag($intQuestionSeq);
		$arrQuestionResult[0]['arr_question_example'] = $this->getQuestionExample($intQuestionSeq);
		foreach($arrQuestionResult[0]['arr_question_example']['type_1'] as $strRowKey=>$arrExampleRow){
			if(count($arrQuestionResult[0]['arr_question_example']['type_2'])>0){
				foreach($arrQuestionResult[0]['arr_question_example']['type_2'] as $strColKey=>$arrExampleCol){
					$arrQuestionResult[0]['arr_question_example']['answer_count'][$strRowKey][$strColKey] = $this->objAnswer->getAnswerCount($intQuestionSeq,$arrExampleRow['seq'],$arrExampleCol['seq'],$intPublishedSeq);
				}
			}else{
				$arrQuestionResult[0]['arr_question_example']['type_1'][$strRowKey]['answer_count'] = $this->objAnswer->getAnswerCount($intQuestionSeq,$arrExampleRow['seq'],null,$intPublishedSeq);
				if($arrQuestionResult[0]['question_type']==2){
					$arrQuestionResult[0]['arr_question_example']['type_1'][$strRowKey]['answer_group_count'] = $this->objAnswer->getAnswerGroupCount($intQuestionSeq,$arrExampleRow['seq'],$intPublishedSeq);
				}				
			}
		}	
		return($arrQuestionResult);
	}
	
	/**
	 * 문제예제를 예제내용을 기준으로 가져온다
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strUserAnswer 유저답내용
	 *
	 * @return array 예제 목록을 반환한다.
	 */
	public function getExampleByExampleText($intQuestionSeq,$strUserAnswer=''){
		include("Model/Tests/SQL/MySQL/Question/getExampleByExampleText.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}	
	
	/**
	 * 문제 목록을 문제내용으로 가져온다
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param string $strQuestion 문제내용
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestionByQuestionString($intTestsSeq,$strQuestion){
		include("Model/Tests/SQL/MySQL/Question/getQuestionByQuestionString.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 문제 목록을 가져온다
	 *
	 * @param integer $intWriterSeq writer 시컨즈
	 * @param integer $intQuestionType 문제 타입
	 * @param string $strQuestionTagName 문제 태그명
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestions($intWriterSeq=null,$intQuestionType=null,$strQuestionTagName=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestions.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 문제를 형식을 기준으로  가져온다
	 *
	 * @param integer $intQuestionType 문제형식
	 * @param integer $intWriterSeq writer시컨즈
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestionByType($intQuestionType,$intWriterSeq=null){
		$arrResult = $this->getQuestions($intWriterSeq,$intQuestionType);
		return($arrResult);		
	}
	
	/**
	 * 문제를 태그명을 기준으로  가져온다
	 *
	 * @param string $strQuestionTagName 문제태그명
	 * @param integer $intWriterSeq writer시컨즈
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestionByTagName($strQuestionTagName,$intWriterSeq=null){
		$arrResult = $this->getQuestions($intWriterSeq,null,$strQuestionTagName);
		return($arrResult);		
	}
	
	/**
	 * 문제 목록을 테스트 시컨즈를 기준으로 가져온다
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestionFromTests($intTestsSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestionFromTests.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrResult as $intKey=>$arrQuestion){
			$arrDummy = $this->getQuestion($arrQuestion['question_seq'],$intPublishedSeq);
			$arrResult[$intKey]['question'] = $arrDummy[0];
		}
		return($arrResult);		
	}
	
	/**
	 * 문제 결과 목록을 가져온다
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return array 문제 결과 목록을 반환한다.
	 */
	public function getQuestionResult($intTestsSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestionResult.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrResult as $intKey=>$arrQuestion){
			$arrDummy = $this->getQuestionWithResultCount($arrQuestion['question_seq'],$intPublishedSeq);
			$arrResult[$intKey]['question'] = $arrDummy[0];
		}
		return($arrResult);
	}	
	
	/**
	 * 문제 저장
	 *
	 * @param integer $intWriterSeq writer시컨즈
	 * @param integer $strContents 문제 내용
	 * @param integer $intQuestionType 문제 타입
	 * @param integer $intExampleType 예제 타입
	 * @param integer $intQuestionJimoonSeq 문제 지문
	 * @param integer $intQuestionSeq 문제 시컨즈
	 * @param integer $intRequired 필수항목 flg
	 * @param integer $intHiddenFlg 문제 숨김 flg
	 *
	 * @return boolean 문제 저장 여부 반환
	 */
	public function setQuestion($intWriterSeq,$strContents,$intQuestionType,$intExampleType,$intQuestionJimoonSeq=null,&$intQuestionSeq=null,$intRequired=0,$intHiddenFlg=0){
		include("Model/Tests/SQL/MySQL/Question/setQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if(!$intQuestionSeq){
			$intQuestionSeq = mysql_insert_id($this->resQuestionDB->res_DB);
		}
		return($boolReturn);
	}
	
	/**
	 * 문제 삭제
	 *
	 * @param integer $intTestsSeq 테스트 퍼블리시 시컨즈
	 * @param integer $intQuestionSeq 문제 시퀀즈
	 * @param integer $intPublishedSeq 문제 시퀀즈 
	 *
	 * @return boolean 문제 삭제 여부 반환
	 */
 	public function deleteQuestion($intTestsSeq,$intQuestionSeq,$intPublishedSeq=null){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		
		include("Model/Tests/SQL/MySQL/Question/deleteQuestion2.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 확장 정보 저장
	 *
	 * @param array $arrInput 문제확장저장 정보 배열
	 * @param integer $intQuestionExtendSeq 문제확장 시퀀스
	 *
	 */
	public function setQuestionExtendInfo($arrInput,&$intQuestionExtendSeq=null){
		
	}
	
	/**
	 * 문제 확장 정보 수정
	 *
	 * @param array $arrInput 문제확장저장 정보 배열
	 * @param integer $intQuestionExtendSeq 문제확장 시퀀스
	 *
	 */
	public function updateQuestionExtendInfo($arrInput,$intQuestionExtendSeq){
		
	}
	
	/**
	 * 문제 확장 정보 삭제
	 *
	 * @param integer $intQuestionExtendSeq 문제확장 시퀀스
	 *
	 */
	public function deleteQuestionExtendInfo($intQuestionExtendSeq){
		
	}
	
	/**
	 * 문제 태그를 가져온다
	 *
	 * @param integer $intQuestionTagSeq 문제 태그 시퀀스
	 *
	 * @return array 문제 태그 목록을 반환한다.
	 */
	public function getQuestionTag($intQuestionSeq){
		//get tags
		include("Model/Tests/SQL/MySQL/Question/getQuestionTag.php");
		$arrQuestoinTagResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return ($arrQuestoinTagResult);
	}
	
	/**
	 * 문제 태그를 저장
	 *
	 * @param integer $intQuestionTagSeq 문제 태그 시퀀스
	 * @param integer $strTagName 문제 태그명
	 *
	 * @return boolean 문제 태그를 저장 여부 반환
	 */
	public function seqQuestionTag($intQuestionSeq,$strTagName){
		include("Model/Tests/SQL/MySQL/Question/setQuestionTag.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 태그를 삭제
	 *
	 * @param integer $intQuestionTagSeq 문제 태그 시퀀스
	 *
	 * @return boolean 문제 태그를 삭제 여부 반환
	 */
	public function deleteQuestionTag($intQuestionTagSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionTag.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 지문 저장
	 *
	 * @param integer $strContents 지문내용
	 * @param integer $intQuestionJimoonSeq 문제 지문 시컨즈
	 *
	 * @return boolean 문제 지문 저장 여부 반환
	 */
	public function setQuestionJimoon($strContents,$intQuestionJimoonSeq=null){
		include("Model/Tests/SQL/MySQL/Question/setQuestionJimoon.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제 지문을 삭제
	 *
	 * @param integer $intQuestionJimoonSeq 문제 지문 시컨즈
	 *
	 * @return boolean 문제 지문 삭제삭제 여부 반환
	 */
	public function deleteQuestionJimoon($intQuestionJimoonSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionJimoon.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 예제 목록을 가져온다
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intExampleType 예제 형식
	 *
	 * @return array 문제 예제 목록을 반환한다.
	 */
	public function getQuestionExample($intQuestionSeq,$intExampleType=null){
		include("Model/Tests/SQL/MySQL/Question/getQuestionExample.php");
		if($intExampleType){
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryRows)
			);			
		}else{
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryRows),
					'type_2'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryCols)
					);
		}
		return($arrResult);		
	}
	
	/**
	 * 문제 예제 저장
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strContents 내용
	 * @param integer $intAnswerFlg 정답 flg
	 * @param string $strExampleType 예제 형식
	 * @param integer $intGotoQuestionSeq 문제 시퀀스
	 * @param integer $intQuestionExampleSeq 문제예제 시컨즈
	 * 
	 *
	 * @return boolean 문제 예제 저장 여부 반환
	 */
	public function setQuestionExample($intQuestionSeq,$strContents,$intAnswerFlg=0,$strExampleType=null,$intGotoQuestionSeq=0,&$intQuestionExampleSeq=null){
		if($strContents){
			// example seq 에 해당되는 example 가 있는지 확인 해서 있을 경우 update 없을 경우 insert 한다.
			if(!$this->checkQuestionExampleByExampleSeq($intQuestionSeq,$intQuestionExampleSeq)){
				$intQuestionExampleSeq = null;
			}
			include("Model/Tests/SQL/MySQL/Question/setQuestionExample.php");
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
			if(!$intQuestionExampleSeq){
				$intQuestionExampleSeq = mysql_insert_id($this->resQuestionDB->res_DB);
			}
		}else{
			$boolReturn = true;
		}
		return($boolReturn);		
	}
	
	/**
	 * 문제 예제를 예제 시컨즈를 기준으로 확인 
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 *
	 * @return integer 문제 예제를 예제 시컨즈를 기준으로 확인 결과를 반환
	 */
	public function checkQuestionExampleByExampleSeq($intQuestionSeq,$intExampleSeq){
		include("Model/Tests/SQL/MySQL/Question/checkQuestionExampleByExampleSeq.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);		
	}	
	
	/**
	 * 문제 예제 삭제
	 *
	 * @param integer $intQuestionExampleSeq 문제 예제 시컨즈
	 * @param integer $strExampleType 예제 시컨즈
	 *
	 * @return boolean 문제 예제 삭제 여부 반환
	 */
	public function deleteQuestionExample($intQuestionExampleSeq,$strExampleType=null){
		switch($strExampleType){
			case("1"):
				include("Model/Tests/SQL/MySQL/Question/deleteQuestionExampleType1.php");
				$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
				return($boolReturn);
				break;
		}
	}
	
	/**
	 * 문제 예제 그외의 문제 예제 삭제
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param array $arrExceptExampleSeq 예제외 배열
	 *
	 * @return boolean 문제 예제 그외의 문제 예제 삭제 여부 반환
	 */
	public function deleteQuestionExampleExceptExampleSeq($intQuestionSeq,$arrExceptExampleSeq){
		if(count($arrExceptExampleSeq)>0){
			include("Model/Tests/SQL/MySQL/Question/deleteQuestionExampleExceptExampleSeq.php");
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		}else{
			$boolReturn = true;
		}
		return($boolReturn);
	}
	
	/**
	 * 테스트의 문제 저장
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intSetType 저장형식
	 * @param integer $intPosition 포지션
	 *
	 * @return boolean 테스트의 문제 저장 성공 여부 반환한다.
	 */
	public function setQuestionToTests($intTestsSeq,$intQuestionSeq,$intSetType=null,$intPosition=null){
		include("Model/Tests/SQL/MySQL/Question/setQuestionToTests.php");
		if(is_null($intPosition)){
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery1);			
		}else{
// 			var_dump($intSetType);
			switch($intSetType){
				case(2):
					$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery2);
					break;
				case(1):
					$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery3);
					break;
			}
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery4);			
		}
		return($boolReturn);		
	}
	
	/**
	 * 테스트의 문제 확인
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 *
	 * @return integer 테스트의 문제 확인 성공 여부 반환한다.
	 */
	public function checkQuestionInTests($intTestsSeq,$intQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/checkQuestionInTests.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	
	/**
	 * 테스트의 문제결과 저장
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intPublishedSeq 테스트 퍼블리시 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return boolean 테스트의 문제결과 저장 성공여부 반환
	 */
	public function setQuestionResultToTests($intTestsSeq,$intPublishedSeq,$intQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/setQuestionResultToTests.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 테스트의 문제를 삭제
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param integer $intQuestionSeq 문제 시컨즈
	 *
	 * @return boolean 테스트의 문제 삭제 성공 여부 반환한다.
	 */
	public function deleteQuestionInTests($intTestsSeq,$intQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionInTests.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 포지션을 수정
	 *
	 * @param integer $intTestsSeq 테스트 시컨즈
	 * @param array $arrSortData sort 배열
	 *
	 * @return boolean 문제 포지션 수정 성공 여부 반환한다.
	 */
	public function updateQuestionPosition($intTestsSeq,$arrSortData){
		foreach($arrSortData as $intKey=>$arrSort){
			include("Model/Tests/SQL/MySQL/Question/updateQuestionPosition.php");
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		}
		return($boolReturn);
	}
	
	/**
	 * 문제 그룹 검색 쿼리를 만든다.
	 * @param array $arrSearch 검색 조건 배열
	 */
	private function buildQuestionGroupSearchQuery($arrSearch){
		$arrWhere = array();
		foreach($arrSearch as $intKey=>$arrResult){
			switch($arrResult['search_type']){
				case('all'):
					array_push($arrWhere,"group_name like '%".sprintf('%s',$arrResult['search_keyword'])."%'");
				break;
				default:
					array_push($arrWhere,$arrResult['search_type']." like '%".sprintf('%s',$arrResult['search_keyword'])."%'");
				break;
			}
		}
		if(count($arrWhere)>0){
			$strWhere = join(' and ',$arrWhere);
		}
	}
	
	/**
	 * 문제그룹목록 count를 가져온다
	 *
	 
	 *
	 * @return integer 문제그룹목록 count를 반환한다.
	 */
	public function getQuestionGroupListCount($arrSearch){
		include("Model/Tests/SQL/MySQL/Question/getQuestionGroupListCount.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn[0]['cnt']);
	}
	
	/**
	 * 문제그룹목록을 가져온다
	 *
	 * @param array $arrSearch 검색 조건 배열
	 * @param array $arrOrder order 조건 배열
	 * @param array $arrPaging 페이징 정보 배열
	 *
	 * @return array 문제그룹목록을 반환한다.
	 */
	public function getQuestionGroupList($arrSearch=array(),$arrOrder,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getQuestionGroupListCount($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		include("Model/Tests/SQL/MySQL/Question/getQuestionGroupList.php");
		$arrQuestionGroup = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		foreach($arrQuestionGroup as $intKey=>$arrResult){
			$arrQuestionGroup[$intKey]['node_cnt'] = $this->getNodeByQuestionGroupSeq($arrResult['seq'],1);
			$arrQuestionGroup[$intKey]['test_cnt'] = $this->getTestsByQuestionGroupSeq($arrResult['seq'],1);
			$arrQuestionGroup[$intKey]['question_cnt'] = $this->getQuestionByQuestionGroupSeq($arrResult['seq'],1);
		}
		return($arrQuestionGroup);		
	}
	
	/**
	 * 문제를 문제그룹시컨즈를 기준으로가져온다
	 *
	 * @param integer $intGroupSeq 그룹 시퀀스
	 * @param integer $intContFlg cont flg
	 *
	 * @return array 문제 목록을 반환한다.
	 */
	public function getQuestionByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		include("Model/Tests/SQL/MySQL/Question/getQuestionByQuestionGroupSeq.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	
	/**
	 * 노드를 문제 그룹 시컨즈를 기준으로 가져온다
	 *
	 * @param integer $intGroupSeq 그룹 시퀀스
	 * @param integer $intContFlg cont flg
	 *
	 * @return array 노드 목록을 반환한다.
	 */
	public function getNodeByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		include("Model/Tests/SQL/MySQL/Question/getNodeByQuestionGroupSeq.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	
	/**
	 * 테스트를 문제그룹시컨즈를 기준으로 가져온다.
	 *
	 * @param integer $intGroupSeq 그룹 시퀀스
	 * @param integer $intContFlg cont flg
	 *
	 * @return array 테스트 목록을 반환한다.
	 */
	public function getTestsByQuestionGroupSeq($intGroupSeq,$intContFlg=0){
		include("Model/Tests/SQL/MySQL/Question/getTestsByQuestionGroupSeq.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intContFlg==0){
			return($arrReturn);
		}else{
			return($arrReturn[0]['cnt']);
		}		
	}
	
	/**
	 * 문제 목록을 문제그룹시컨즈를 기준으로 가져온다
	 * @param integer $intQuestionGroupSeq 문제그룹 시퀀스
	 */
	public function getQuestionListFormQuestionGroup($intQuestionGroupSeq){}
	
	/**
	 * 문제 그룹 저장
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param integer $intQuestionSeq 문제 시퀀스
	 *
	 * @return boolean 문제 그룹 저장 여부 반환
	 */
	public function setQuestionGroup($strGroupName,$arrQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/setQuestionGroup.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($boolReturn && count($arrQuestionSeq)>0){
			$intGroupSeq = mysql_insert_id($this->resQuestionDB->res_DB);
			$boolReturn = $this->setQuestionToGroup($intGroupSeq,$arrQuestionSeq);
		}
		return($boolReturn);
	}
	
	/**
	 * 문제 그룹 목록 저장
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param array $arrQuestionSeq 문제 시퀀스 배열
	 *
	 * @return boolean 문제 그룹 목록 저장 여부 반환
	 */
	public function setQuestionToGroup($intGroupSeq,$arrQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/setQuestionToGroup.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제 그룹 수정
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param string $strGroupName 그룹명
	 *
	 * @return boolean 문제 그룹 수정 여부 반환
	 */
	public function updateQuestionGroup($intGroupSeq,$strGroupName){
		include("Model/Tests/SQL/MySQL/Question/updateQuestionGroup.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제 그룹을 가져온다
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 *
	 * @return array 문제 그릅목록을 반환한다.
	 */
	public function getQuestionGroup($intGroupSeq){
		include("Model/Tests/SQL/MySQL/Question/getQuestionGroup.php");
		$arrReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrReturn);
	}
	
	/**
	 * 문제 그룹을 삭제한다.
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 *
	 * @return boolean 문제 그룹을 삭제 여부 반환
	 */
	public function deleteQuestionGroup($arrGroupSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionGroup.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 그룹별 목록을 삭제한다.
	 *
	 * @param integer $intGroupSeq 그룹 시컨즈
	 * @param integer $intQuestionSeq 문제 시퀀스
	 *
	 * @return boolean 문제 그룹별 목록을 삭제 여부 반환
	 */
	public function deleteQuestionInGroup($intGroupSeq,$intQuestionSeq){
		include("Model/Tests/SQL/MySQL/Question/deleteQuestionInGroup.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
}
?>