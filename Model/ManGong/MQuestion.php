<?
/**
 * 문제 및 문제에 종속되는 보기를 등록, 수정, 삭제, 조회한단.
 * 본 클레스틑 Test/Question 클레스를 확장한다.
 *
 * @package      	Mangong/MQuestion
 * @subpackage   	Core/Util/Paging
 * @subpackage   	Core/DataManager/DataHandler
 * @property		private resource $resQuestionDB : DB 커넥션 리소스
 * @property 		public array $arrQuestion : 배열형식의 문제
 * @property 		public array $arrExampleStyle :배열형태의 보기형식
 * @category     	Question
 */

require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class MQuestion{
	public $resQuestionDB;
	public $arrQuestion;
	public $arrExampleStyle;
	
	/**
	 * 생성자
	 *
	 * @param resource $resMangongDB 리소스 형태의 DB커넥션 
	 * @return null 
	 */	
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resQuestionDB = $resMangongDB;
		// arrExampleStyle 속성에 보기 유형 설정
		$this->arrExampleStyle = array(
				0=>array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10'),
				1=>array(1=>'가',2=>'나',3=>'다',4=>'라',5=>'마',6=>'바',7=>'사',8=>'아',9=>'자',10=>'차'),
				2=>array(1=>'ㄱ',2=>'ㄴ',3=>'ㄷ',4=>'ㄹ',5=>'ㅁ',6=>'ㅂ',7=>'ㅅ',8=>'ㅇ',9=>'ㅈ',10=>'ㅊ'),
				3=>array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J'),
				4=>array(1=>'a',2=>'b',3=>'c',4=>'d',5=>'e',6=>'f',7=>'g',8=>'h',9=>'i',10=>'j'),
				5=>array(1=>'①',2=>'②',3=>'③',4=>'④',5=>'⑤',6=>'⑥',7=>'⑦',8=>'⑧',9=>'⑨',10=>'⑩'),
				6=>array(1=>'ⓐ',2=>'ⓑ',3=>'ⓒ',4=>'ⓓ',5=>'ⓔ',6=>'ⓕ',7=>'ⓖ',8=>'ⓗ',9=>'ⓘ',10=>'ⓙ'),
				7=>array(1=>'㉠',2=>'㉡',3=>'㉢',4=>'㉣',5=>'㉤',6=>'㉥',7=>'㉦',8=>'㉧',9=>'㉨',10=>'㉩'),
				8=>array(1=>'㉮',2=>'㉯',3=>'㉰',4=>'㉱',5=>'㉲',6=>'㉳',7=>'㉴',8=>'㉵',9=>'㉶',10=>'㉷')
				);
	}
	
	/**
	 * 문제 타입에 따른 보기 개수를 가져온다.
	 *
	 * @param integer $intQuestionType 문제 타입
	 * @return integer 보기 개수
	 */	
	public function getExampleCountByQuestionType($intQuestionType){
		switch($intQuestionType){
			case(1):
			case(11):
				$intExampleCount = 2;
				break;
			case(2):
				$intExampleCount = 3;
				break;
			case(3):
				$intExampleCount = 4;
				break;
			case(4):
				$intExampleCount = 5;
				break;
			case(5):
				$intExampleCount = 1;
				break;
			case(6):
				$intExampleCount = 2;
				break;
			case(7):
				$intExampleCount = 3;
				break;
			case(8):
				$intExampleCount = 4;
				break;
			case(9):
				$intExampleCount = 5;
				break;
		}
		return($intExampleCount);
	}
	
	/**
	 * 문제 상세 정보 조회.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intExampleNumberingStyle 보기 넘버링 타입
	 * @return array 문제 상세정보를 답고 있는 배열
	 */	
	public function getQuestion($intQuestionSeq,$intTestsSeq=0,$intExampleNumberingStyle=0){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestion.php");
		$arrQuestionResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		$arrQuestionResult[0]['arr_question_tag'] = $this->getQuestionTag($intQuestionSeq);
		$arrQuestionResult[0]['arr_question_example'] = $this->getQuestionExample($intExampleNumberingStyle,$intQuestionSeq,$arrQuestionResult[0]['example_type']);
		$arrQuestionResult[0]['example_count'] = $this->getExampleCountByQuestionType($arrQuestionResult[0]['question_type']);
		if($intTestsSeq){
			$arrQuestionResult[0]['question_extend_info'] = $this->getQuestionExtendInfo($intTestsSeq,$intQuestionSeq);
		}
		return($arrQuestionResult);
	}

	/**
	 * 테스트에 포함된 문제의 개수를 반환
	 *
	 * @param integer $intTestSeq 테스트 시퀀스
	 * @param array $arrTestsSeq 테스트 시퀀스 (여러개의 테스트에 해당하는 문제의 개수를 구할때 사용)
	 * @param string $strTestsSeqGroup 테스터 그룹 고유값
	 * @return integer 문제 개수
	 */	
	public function getQuestionCountInTest($intTestSeq=null,$arrTestsSeq=array(),$strTestsSeqGroup=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionCountInTest.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult[0]['question_count']);
	}
	
	/**
	 * 테스트에 포함된 문제의 부가 정보를 가져온다.
	 *
	 * @param integer $intTestSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @return array 문제정보
	 */	
	public function getQuestionExtendInfo($intTestsSeq,$intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionExtendInfo.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 테스트에 포함된 문제목록을 가져온다.
	 *
	 * @param integer $intTestSeq 테스트 시퀀스
	 * @return array 문제 목록
	 */	
	public function getQuestionList($intTestsSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionList.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 문제 태그 조회
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * 
	 * @return array question_tag table 참조
	 */
	public function getQuestionTag($intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionTag.php");
		$arrQuestoinTagResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return ($arrQuestoinTagResult);
	}
	
	/**
	 * 문제에 대한 답이 정답 또는 오답여부 확인.
	 *
	 * @param integer $intQuestionSeq 테스트 시퀀스
	 * @param integer $intQuestionType 테스트 타입
	 * @param array $arrQuestionExample 테스트 보기
	 * @param mixed $mixAnswer 테스터 입력 선택 정답
	 * @return array 문제 목록 user_answer(테스터 입력 답안), question_answer(문제 정답), result(정,오답 여부)
	 */	
	public function checkAnswerCorrect($intQuestionSeq,$intQuestionType,$arrQuestionExample,$mixAnswer){
		$arrReturn = array();
		switch($intQuestionType){
			// 객관식 문제의 정답 확인
			case(1):
			case(2):
			case(3):
			case(4):
			case(11):
				// 객관식 정답 구하기 - 보다 효율적인 방법으로 수정할 필요성 있음
				$intQuestionCorrectAnswer = 0;
				foreach($arrQuestionExample as $intKey=>$arrExample){
					if($arrExample['answer_flg']==1){
						$intQuestionCorrectAnswer = $arrExample['seq'];
						break;
					}
				}
				$arrReturn['question_answer'] = $mixAnswer;
				if(!$intQuestionCorrectAnswer){
					$boolReturn = false;
				}else{
					$boolReturn = ((int)$intQuestionCorrectAnswer==(int)$mixAnswer);
				}
				$arrReturn = array(
						'question_answer'=>$intQuestionCorrectAnswer,
						'user_answer'=>$mixAnswer,
						'result'=>$boolReturn
						);
				break;
			// 주관식 문제의 정답 확인
			case(5):
			case(6):
			case(7):
			case(8):
			case(9):
				$boolReturn = $this->checkSubjectiveQuestionAnswer($arrQuestionExample,$mixAnswer);
				$arrReturn = array(
						'user_answer'=>h_json_encode($mixAnswer),
						'result'=>$boolReturn
				);	
				break;
			case(10):
			case(20):
				// result 2 is not marking
				$arrReturn = array(
						'user_answer'=>h_json_encode($mixAnswer),
						'result'=>2
				);				
				break;
		}
		return($arrReturn);
	}
	
	/**
	 * 문제의 보기를 비 활성화 시킨다.
	 *
	 * @param integer $intQuestionSeq 테스트 시퀀스
	 * @return boolean True 일 경우 비활성화 성공 false 일 경우 비활성화 오류
	 */	
	public function disableQuestionExample($intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/disableQuestionExample.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 주관식 정답 확인.
	 *
	 * @param array $arrExamples 문제의 보기
	 * @param array $arrUserAnswer 사용자 입력 답안
	 * @param boolean $boolMatchFlg 주관신 답의 정답 확인 타입(true 일 경우 공백을 포함한 완전 일치여부를 판단하며 false 일 경우 공잭을 제거한 단어의 일치성을 검사한다)
	 * @return boolean True 일 경우 정답 false 일 경우 오답
	 */
	public function checkSubjectiveQuestionAnswer($arrExamples,$arrUserAnswer,$boolMatchFlg=false){
		foreach($arrExamples as $intKey=>$arrExample){
			$boolReturn = false;
			$strUserAnswer = $arrUserAnswer[$arrExample['seq']];
			$strUserAnswerForCheck = str_replace(" ","",trim($strUserAnswer));
			$arrQuestionAnswer = explode(",",$arrExample['subjective_answer']);
			foreach($arrQuestionAnswer as $intKey=>$strQuestionAnswer){
				if(!$boolMatchFlg){
					$strQuestionAnswerForCheck = str_replace(" ","",trim($strQuestionAnswer));
					if($strQuestionAnswerForCheck == $strUserAnswerForCheck){
						$boolReturn = true;
					}
				}else{
					if(trim($strQuestionAnswer) != trim($strUserAnswer)){
						$boolReturn = true;
					}					
				}
			}
			if(!$boolReturn){
				return(false);
			}
		}
		return(true);
	}
	
	/**
	 * 문제 저장하기.
	 *
	 * @param integer $intWriterSeq 문제의 보기
	 * @param string $strContents 사용자 입력 답안
	 * @param integer $intQuestionType 문제 타입
	 * @param integer $intExampleType 보기 타입
	 * @param string $strQuestionHint 문제 힌트
	 * @param string $strQuestionCommentary 문제 코멘트
	 * @param integer $intQuestionJimoonSeq 지문이 있는 문제의 경우 등록된 지문의 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스(시퀀스가 있으면 update 없으면 insert)
	 * @param string $strTags 문제에 대한 유형테그
	 * @param string $strFileName 문제에 첨부되는 파일명
	 * @return boolean 문제 등록 성공여부 반환 true or false
	 */
	public function setQuestion($intWriterSeq,$strContents,$intQuestionType,$intExampleType,$strQuestionHint,$strQuestionCommentary,$intQuestionJimoonSeq=null,&$intQuestionSeq=null,$strTags=null,$strFileName=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestion.php");	
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if(!$intQuestionSeq){
			$intQuestionSeq = mysql_insert_id($this->resQuestionDB->res_DB);
		}
		if($boolReturn){
			$boolReturn = $this->setQuestionHistory($intQuestionSeq);
		}
		return($boolReturn);		
	}
	
	/**
	 * 문제 정보가 변경될 경우 히스토리를 남긴다.
	 *
	 * @param integer $intQuestionSeq 문제의 시퀀스
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function setQuestionHistory($intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionHistory.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제 삭제.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intPublishedSeq 문제 발행 시퀀스
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function deleteQuestion($intTestsSeq,$intQuestionSeq,$intPublishedSeq=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/deleteQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($boolReturn){
			include("Model/TechQuiz/SQL/MySQL/MQuestion/deleteQuestion1.php");
			$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		}
		return($boolReturn);
	}
	
	/**
	 * 문제 유형테그 갱신.
	 * Question 테이블에 컬럼 형식으로 들어가며 별도의 유형테그 테이블로 처리하도록 메소드 추가됨
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param mixed $mixQuestionTag 문제 유형 태그
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function updateTagFromQuestion($intQuestoinSeq,$mixQuestionTag){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateTagFromQuestion.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 오답노트를 입력할 경우 등의 테스터에 의해 문제를 갱신.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strContents 문제
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function updateQuestionByStudent($intQuestionSeq,$strContents){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateQuestionByStudent.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 오답노트를 입력할 경우 등의 테스터에 의해 문제의 보기를 갱신.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intExampleSeq 보기 시퀀스
	 * @param string $strContents 보기 내용
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function updateQuestionExampleByStudent($intQuestionSeq,$intExampleSeq,$strContents){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateQuestionExampleByStudent.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}	
	
	/**
	 * 문제의 노출 순서를 변경.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intOrderNumber 추가되는 문제의 정렬 번호
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function updateQuestionOrderNumber($intTestsSeq,$intOrderNumber){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateQuestionOrderNumber.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제를 테스트에 할당한다.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intQuestionNumber 문제 번호
	 * @param integer $intQuestionScore 문제 점수
	 * @param integer $intOrderNumber 문제 정렬 순서
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function setQuestionToTests($intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionToTests.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	
	/**
	 * 문제에 할당된 테스트를 변경한다.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intQuestionNumber 문제 번호
	 * @param integer $intQuestionScore 문제 점수
	 * @param integer $intOrderNumber 문제 정렬 순서
	 * @return boolean 문제 히스토리 등록 성공여부 반환 true or false
	 */	
	public function updateQuestionToTests($intTestsSeq,$intQuestionSeq, $intQuestionNumber, $intQuestionScore, $intOrderNumber){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateQuestionToTests.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제 보기 시퀀스에 해당하는 보기를 구한다.
	 *
	 * @param integer $intExampleSeq 문제 보기 시퀀스
	 * @return array question_example table 참조
	 */	
	public function getQuestionExampleByExampleSeq($intExampleSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionExampleByExampleSeq.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);
	}
	
	/**
	 * 문제 보기를 구한다.
	 *
	 * @param integer $intExampleNumberingStyle 문제 보기 타입(__construct 참조)
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intExampleType 보기 형식 (1,2,3,4,11 객관식, 5,6,7,8,9 주관식, 10,20 추가예정 - 논술 등)
	 * @param integer $intLimit 보기 개수
	 * @return array 문제보기
	 */	
	public function getQuestionExample($intExampleNumberingStyle,$intQuestionSeq,$intExampleType=null,$intLimit=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionExample.php");		
		if($intExampleType){
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery)
			);			
		}else{
			$arrResult = array(
					'type_1'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryRows),
					'type_2'=>$this->resQuestionDB->DB_access($this->resQuestionDB,$strQueryCols)
					);
		}
		foreach($arrResult['type_1'] as $intKey=>$arrExample){
			$arrResult['type_1'][$intKey]['example_number'] = $this->arrExampleStyle[$intExampleNumberingStyle][$arrExample['example_number']];
		}
		return($arrResult);		
	}
	
	/**
	 * 문제에 해당하는 단일 보기를 등록한다.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strContents 보기 내용
	 * @param integer $intAnswerFlg 정답 플래그
	 * @param string $strExampleType 보기 형식
	 * @param integer $intQuestionExampleSeq 보기 시퀀스
	 * @param integer $intExampleNumber 보기 번호
	 * @param string $strSubjectiveAnswer 주관식 정답
	 * @param integer $intQuestionType 문제 타입
	 * @param integer $intExampleSeq Insert 일 참조반환할 보기 시퀀스
	 * @return boolean 문제 보기 등록 성공여부 반환 true or false
	 */	
	public function setQuestionExample($intQuestionSeq,$strContents,$intAnswerFlg=0,$strExampleType=null,$intQuestionExampleSeq=null,$intExampleNumber=null,$strSubjectiveAnswer=null,$intQuestionType=0,&$intExampleSeq=null){
		if($intExampleNumber<=constant("QUESTION_TYPE_".$intQuestionType."_EXAMPLE_COUNT")){
			$intDeleteFlg = 0;
		}else{
			$intDeleteFlg = 1;
		}		
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionExample.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($boolReturn){
			$intExampleSeq = mysql_insert_id($this->resQuestionDB->res_DB);
		}
		return($boolReturn);		
	}
	
	/**
	 * 문제에 해당하는 전체 보기를 등록한다.
	 * 최초 문제를 등록시 보기의 입력 값이 없는 상태로 보기가 등록될때 사용됨
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strContents 보기 내용
	 * @param integer $intAnswerFlg 정답 플레그
	 * @param string $strExampleType 보기타입
	 * @param integer $intQuestionExampleSeq 보기 시퀀스
	 * @param integer $intExampleNumber 보기 번호
	 * @return boolean 문제 보기 등록 성공여부 반환 true or false
	 */	
	public function setQuestionExampleAll($intQuestionSeq,$strContents,$intAnswerFlg=0,$strExampleType=null,$intQuestionExampleSeq=null,$intExampleNumber=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionExampleAll.php");	
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	
	/**
	 * 문제에 보기의 정답을 수정한다.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param integer $intExampleNumber 보기 번호
	 * @param integer $intExampleSeq 보기 시퀀스
	 * @return boolean 문제 보기의 정답 수정 성공여부 반환 true or false
	 */	
	public function updateExampleAnswerFlg($intQuestionSeq,$intExampleNumber,$intExampleSeq=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/updateExampleAnswerFlg.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery1);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery2);
		return($boolReturn);		
	}
	
	/**
	 * 특정 문제에 정답에 해당하는 보기를 구한다.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @return array 정답에 해당하는 보기 정보
	 */	
	public function getQuestionAnswerByQuestoinSeq($intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionAnswerByQuestoinSeq.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 테스트에 포함된 전체 문제의 유형태그를 구한다.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @return array 스트에 포함된 전체 문제의 유형태그
	 */	
	public function getQuestionTagsToTestsSeq($intTestsSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionTagsToTestsSeq.php");
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	
	/**
	 * 문제의 유형태그를 등록한다.
	 *
	 * @param integer $intQuestionSeq 문제 시퀀스
	 * @param string $strQuestionTag 문제 유형태그
	 * @return boolean 문제의 유형태그를 등록 성공여부 반환 true or false
	 */	
	public function setQuestionTags($intQuestionSeq,$strQuestionTag){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionTags.php");
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 문제의 유형태그를 삭제한다.
	 *
	 * @param integer $intTestsSeq 테스트 시퀀스
	 * @param array $arrCompareTagResult 삭제한 유형태그
	 * @return boolean 문제의 유형태그 삭제 성공여부 반환 true or false
	 */	
	public function deleteQuestionTags($intTestsSeq,$arrCompareTagResult=null){
		include("Model/ManGong/SQL/MySQL/MQuestion/deleteQuestionTags.php");
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
	
	/**
	 * 문제의 정보요소를 배열형태로 받아 수정한다.
	 *
	 * @param integer $intQuestonSeq 문제 시퀀스
	 * @param array $arr_input 문제 정보 요소
	 * @return boolean 문제의 정보요소 수정 성공여부 반환 true or false
	 */	
	public function setQuestionElement($intQuestonSeq,$arr_input=array()){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionElement.php");
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
}
?>