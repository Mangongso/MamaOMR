<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Question.php");

class MQuestion extends Question{
	public $arrQuestion;
	public $arrExampleStyle;
	public function __construct($resMangongDB=null){
		$this->objPaging =  new Paging();
		$this->resQuestionDB = $resMangongDB;
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
	public function getQuestion($intQuestionSeq,$intTestsSeq=0,$intExampleNumberingStyle=0){
		//get question (with jimoon)
		include("Model/Tests/SQL/MySQL/Question/getQuestion.php");
		$arrQuestionResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		$arrQuestionResult[0]['arr_question_tag'] = $this->getQuestionTag($intQuestionSeq);
		$arrQuestionResult[0]['arr_question_example'] = $this->getQuestionExample($intExampleNumberingStyle,$intQuestionSeq,$arrQuestionResult[0]['example_type']);
		$arrQuestionResult[0]['example_count'] = $this->getExampleCountByQuestionType($arrQuestionResult[0]['question_type']);
		if($intTestsSeq){
			$arrQuestionResult[0]['question_extend_info'] = $this->getQuestionExtendInfo($intTestsSeq,$intQuestionSeq);
		}
		return($arrQuestionResult);
	}
	public function getQuestionCountInTest($intTestSeq=null,$arrTestsSeq=array(),$strTestsSeqGroup=null){
		if(count($arrTestsSeq)){
			$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq in (".join(',',$arrTestsSeq).")");
		}else if(!is_null($strTestsSeqGroup)){
			$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq in (".$strTestsSeqGroup.")");
		}else{
			$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq=%d",$intTestSeq);
		}
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult[0]['question_count']);
	}
	public function getQuestionExtendInfo($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("select * from test_question_list where test_seq=%d and question_seq=%d",$intTestsSeq,$intQuestionSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function getQuestionList($intTestsSeq,$intQuestionSeq){
		$strQuery = sprintf("select * from test_question_list where test_seq=%d and question_seq=%d",$intTestsSeq,$intQuestionSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function checkAnswerCorrect($intQuestionSeq,$intQuestionType,$arrQuestionExample,$mixAnswer){
		$arrReturn = array();
		switch($intQuestionType){
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
			case(5):
			case(6):
			case(7):
			case(8):
			case(9):
				$boolReturn = $this->checkSubjectiveQuestionAnswer($arrQuestionExample,$mixAnswer);
				/*
				$arrReturn = array(
						'user_answer'=>json_encode($mixAnswer,JSON_UNESCAPED_UNICODE),
						'result'=>$boolReturn
				);
				*/
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
	public function disableQuestionExample($intQuestionSeq){
		$strQuery = sprintf("update question_example set delete_flg=1 where question_seq=%d",$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	/*
	 * $boolMatchFlg 가 true 일 경우 공백을 포함한 완전 일치여부를 판단하며 false 일 경우 공잭을 제거한 단어의 일치성을 검사한다
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
	public function setQuestionHistory($intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionHistory.php");
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function deleteQuestion($intTestsSeq,$intQuestionSeq,$intPublishedSeq=null){
		if(file_exists(ini_get('include_path')."Model/ManGong/SQL/MySQL/MQuestion/deleteQuestion.php")){
			include("Model/ManGong/SQL/MySQL/MQuestion/deleteQuestion.php");
		}else{
			include("Model/TechQuiz/SQL/MySQL/MQuestion/deleteQuestion.php");
		}		
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		if($intPublishedSeq){
			$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and published_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intPublishedSeq,$intQuestionSeq);
		}else{
			$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intQuestionSeq);
		}
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function updateTagFromQuestion($intQuestoinSeq,$mixQuestionTag){
		if(is_array($mixQuestionTag)){
			$strQuery = sprintf("update question set tags='%s' where seq=%d",join(',',$mixQuestionTag),$intQuestoinSeq);
		}else{
			$strQuery = sprintf("update question set tags='%s' where seq=%d",$mixQuestionTag,$intQuestoinSeq);
		}
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function updateQuestionByStudent($intQuestionSeq,$strContents){
		$strQuery = sprintf("update question set contents='%s' where seq=%d",quote_smart(trim($strContents)),$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function updateQuestionExampleByStudent($intQuestionSeq,$intExampleSeq,$strContents){
		$strQuery = sprintf("update question_example set contents='%s' where question_seq=%d and seq=%d",quote_smart(trim($strContents)),$intQuestionSeq,$intExampleSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}	
	public function updateQuestionOrderNumber($intTestsSeq,$intOrderNumber){
		$strQuery = sprintf("update test_question_list set order_number=order_number+1 where test_seq=%d and order_number>=%d",$intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function setQuestionToTests($intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber=null){
		$strQuery = sprintf("insert into test_question_list set test_seq=%d,question_seq=%d,question_number=%d,question_score=%d,order_number=%d",$intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);
	}
	public function updateQuestionToTests($intTestsSeq,$intQuestionSeq, $intQuestionNumber, $intQuestionScore, $intOrderNumber){
		$strQuery = sprintf("update test_question_list set question_number=%d,question_score=%d,order_number=%d where test_seq=%d and question_seq=%d",$intQuestionNumber,$intQuestionScore,$intOrderNumber,$intTestsSeq,$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function getQuestionExampleByExampleSeq($intExampleSeq){
		$strQuery = sprintf("select * from question_example where seq=%d",$intExampleSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);
	}
	public function getQuestionExample($intExampleNumberingStyle,$intQuestionSeq,$intExampleType=null,$intLimit=null){
		if(file_exists(ini_get('include_path')."/Model/ManGong/SQL/MySQL/MQuestion/getQuestionExample.php")){
			include("Model/ManGong/SQL/MySQL/MQuestion/getQuestionExample.php");
		}else{
			include("Model/TechQuiz/SQL/MySQL/MQuestion/getQuestionExample.php");
		}		
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
	public function setQuestionExampleAll($intQuestionSeq,$strContents,$intAnswerFlg=0,$strExampleType=null,$intQuestionExampleSeq=null,$intExampleNumber=null){
		if(file_exists(ini_get('include_path')."Model/ManGong/SQL/MySQL/MQuestion/setQuestionExampleAll.php")){
			include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionExampleAll.php");
		}else{
			include("Model/TechQuiz/SQL/MySQL/MQuestion/setQuestionExampleAll.php");
		}		
		
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function updateExampleAnswerFlg($intQuestionSeq,$intExampleNumber,$intExampleSeq=null){
		//update answer flg 0 all
		$strQuery = sprintf("update question_example set answer_flg=0 where question_seq=%d",$intQuestionSeq);
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		//update answer flg 1
		if(!$intExampleSeq){
			$strQuery = sprintf("update question_example set answer_flg=1 where question_seq=%d and example_number=%d",$intQuestionSeq,$intExampleNumber);
		}else{
			$strQuery = sprintf("update question_example set answer_flg=1 where question_seq=%d and seq=%d",$intQuestionSeq,$intExampleSeq);
		}
		$boolReturn = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolReturn);		
	}
	public function getQuestionAnswerByQuestoinSeq($intQuestionSeq){
		$strQuery = sprintf("SELECT * FROM question_example WHERE answer_flg=1 AND delete_flg=0 AND question_seq=%d",$intQuestionSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function getQuestionTagsToTestsSeq($intTestsSeq){
		$strQuery = sprintf("SELECT * FROM question_tag 
							where question_seq in (SELECT sq.question_seq 
													FROM test_question_list sq,question q  
													WHERE sq.question_seq = q.seq 
													AND test_seq=%d 
													AND q.delete_flg=0)",$intTestsSeq);
		$arrResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($arrResult);		
	}
	public function setQuestionTags($intQuestionSeq,$strQuestionTag){
		$strQuery = sprintf("insert into question_tag set question_seq=%d,tag='%s',create_date=now()",$intQuestionSeq,$strQuestionTag);
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
	public function deleteQuestionTags($intTestsSeq,$arrCompareTagResult=null){
		$strQuery = sprintf("delete from question_tag 
							where question_seq in (SELECT sq.question_seq 
												FROM test_question_list sq,question q  
												WHERE sq.question_seq = q.seq 
												AND test_seq=%d 
												AND q.delete_flg=0) ",$intTestsSeq);
		if($arrCompareTagResult){
			$strQuery .= " and tag in ('".join("','",$arrCompareTagResult)."')";
		}
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
	public function setQuestionElement($intQuestonSeq,$arr_input=array()){
		include("Model/ManGong/SQL/MySQL/MQuestion/setQuestionElement.php");
		$boolResult = $this->resQuestionDB->DB_access($this->resQuestionDB,$strQuery);
		return($boolResult);		
	}
}
?>