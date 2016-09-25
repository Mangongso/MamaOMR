<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Tests/Tests.php");
require_once("Model/Tests/Question.php");
//require_once("Model/Member/Member.php");

class Report{
	private $resReportDB = null;
	private $objPaging = null;
	public function __construct($resReportDB=null){
		$this->objPaging =  new Paging();
		$this->resReportDB = $resReportDB;
		$this->objTests = new Tests($resReportDB);
		$this->objQuestion = new Question($resReportDB);
	}
	public function __destruct(){}
	
	/*
	 * 설문 사용자의 문제애 대한 답변 리포트 - 서버이 기준
	 * */
	public function getUserAnswerReportByTests($intTestsSeq,$intUserSeq=null){
		$arrUsers = $this->objTests->getTestsUser($intTestsSeq);
		$arrQuestions = $this->objQuestion->getTestsQuestionList($intTestsSeq);
		if(count($arrUsers)>0){
			foreach($arrUsers as $intKey=>$arrUser){
				foreach($arrQuestions as $intSubKey=>$arrQuestion){
					$arrUser[$intKey]['report'] = array(
							'question'=>$arrQuestion['contents'],
							'answer'=>$this->getUserAnswer($arrQuestion['test_seq'],$arrQuestion['question_seq'],$arrUser['user_seq'])
							);
				}
			}
		}
		return($arrUser);
	}
	/*
	 * 각 문제에 대한 사용자들의 답번 리포트 - 서버이 기준
	 */
	public function getQuestionReportByTests($intTestsSeq,$arrUsers=null){
		$arrQuestions = $this->objQuestion->getQuestion($intTestsSeq);
		if(!$arrUsers){
			$arrUsers = $this->objTests->getTestsUser($intTestsSeq);
		}else{
			if(!is_array($arrUsers)){
				$arrUsers = array($arrUsers);
			}
			$arrDummy = array();
			foreach($arrUsers as $intUserKey=>$intUserSeq){
				array_push($arrDummy,array('user_seq'=>$intUserSeq));
			}
			$arrUsers = $arrDummy;
		}
		if(count($arrQuestions)>0){
			foreach($arrQuestions as $intKey=>$arrQuestion){
				foreach($arrUsers as $intSubKey=>$arrUser){
					$arrUser[$intKey]['report'] = array(
							'user'=>$arrResult,
							'answer'=>$this->getUserAnswer($arrQuestion['test_seq'],$arrQuestion['question_seq'],$arrUser['user_seq'])
					);
				}				
				$strQuery = sprintf("select user_answer,count(user_answer) as answer_cnt from user_answer where test_seq=%d and question_seq=%d group by user_answer",$intTestsSeq,$arrResult['question_seq']);
				$arrAnswerCount = $this->resReportDB->DB_access($this->resAnswerDB,$strQuery);
				$arrQuestion[$intKey]['answer_cnt'] = $arrAnswerCount; 
			}
		}
		return($arrQuestion);		
	}
	public function getUserAnswer($intTestsSeq,$intQuestionSeq,$intUserSeq){
		$strQuery = sprintf("select * from user_answer where test_seq=%d and question_seq=%d and user_seq=%d",$intTestsSeq,$intQuestionSeq,$intUserSeq);
		$arrReturn = $this->resReportDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrReturn);
	}
	public function getAnswerReportByUser($intUserSeq){
		$arrTestss = $this->objTests->getTestsListByUser($intUserSeq);
		if(count($arrTestss)>0){
			foreach($arrTestss as $intKey=>$arrTests){
				$arrTestss[$intKey]['report'] = $this->getQuestionReportByTests($arrTests['test_seq'],$intUserSeq);
			}
		}
		return($arrTestss);		
	}
}
?>