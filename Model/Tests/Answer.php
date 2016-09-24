<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
//require_once("Model/Member/Member.php");

class Answer{ 
	private $resAnswerDB = null;
	private $objPaging = null;
	public function __construct($resAnswerDB=null){
		$this->objPaging =  new Paging();
		//$this->objMember =  new Member($resAnswerDB);
		$this->resAnswerDB = $resAnswerDB;
	}
	public function __destruct(){}
	// $intAnswerType - 0:user answer,1:from cart2:uploaded
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
	public function deleteAllAnswer($strUserEmail, $intTestsSeq, $intPublishedSeq=null){
		$strQuery = sprintf("update user_answer set delete_flg=1 where user_email='%s' and test_seq=%d",$strUserEmail, $intTestsSeq);
		if($intPublishedSeq){$strQuery = $strQuery.sprintf(" and published_seq=%d",$intPublishedSeq);}
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);
	}	
	public function deleteAnswerInPublished($intTestsSeq, $intPublishedSeq, $intQuestionSeq = 0){
		$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and published_seq=%d",$intTestsSeq, $intPublishedSeq);
		if($intQuestionSeq){
			$strQuery = $strQuery.sprintf(" and question_seq=%d",$intQuestionSeq);
		}
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult);
	}	
 	public function deleteAnswer($intAnswerSeq){
 		include("Model/Tests/SQL/MySQL/Answer/deleteAnswer.php");
 		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
	}
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
	public function deleteAnswerReason($intReasonSeq){
		include("Model/Tests/SQL/MySQL/Answer/deleteAnswer.php");
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);	
		return($boolResult);
	}
	public function getAnswerCountSummary($intTestsSeq,$intPublishedSeq=null){
		$arrReturn = array();
		
		// all count
		$strQuery = "select count(distinct(user_email)) as cnt from user_answer";
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		$arrReturn['total'] = $arrResult[0]['cnt'];
		
		// question count
		$strQuery = sprintf("select count(distinct(user_email)) as cnt from user_answer where test_seq=%d",$intTestsSeq);
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		$arrReturn['summary_total'] = $arrResult[0]['cnt'];

		if($intPublishedSeq){
			// published count
			$strQuery = sprintf("select count(distinct(user_email)) as cnt from user_answer where test_seq=%d and published_seq=%d",$intTestsSeq,$intPublishedSeq);
			$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		}
		$arrReturn['published_total'] = $arrResult[0]['cnt'];
				
		return($arrReturn);
	}	
	public function getAnswerCount($intQuestionSeq,$intExampleSeq,$intMatrixExampleSeq,$intPublishedSeq=null){
		if($intMatrixExampleSeq){
			$strQuery = sprintf("select count(*) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=%d",$intQuestionSeq,$intExampleSeq,$intMatrixExampleSeq);
		}else{
			$strQuery = sprintf("select count(*) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0",$intQuestionSeq,$intExampleSeq);
		}
		if($intPublishedSeq){
			$strQuery = $strQuery.sprintf(" and published_seq=%d",$intPublishedSeq);
		}
// 		echo "<p>";
// 		echo $strQuery;
// 		echo "</p>";
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult[0]['cnt']);		
	}
	public function getAnswerCountByQuestionSeq($intQuestionSeq,$intTestsSeq=null,$intPublishedSeq=null){
		$arrWhere = array();
		if($intTestsSeq){
			array_push($arrWhere,sprintf('test_seq=%d',$intTestsSeq));
		}
		if($intPublishedSeq){
			array_push($arrWhere,sprintf('published_seq=%d',$intPublishedSeq));
		}
		$strQuery = sprintf("select count(distinct(user_email)) as cnt from user_answer where question_seq=%d",$intQuestionSeq);
		if(count($arrWhere)>0){
			$strQuery = $strQuery." and ".join(" and ",$arrWhere);
		}
		$boolResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($boolResult[0]['cnt']);		
	}
	public function getAnswerGroupCount($intQuestionSeq,$intExampleSeq,$intPublishedSeq=null){
		if($intPublishedSeq){
			$strQuery = sprintf("select user_answer,count(user_answer) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0 and published_seq=%d group by user_answer",$intQuestionSeq,$intExampleSeq,$intPublishedSeq);
		}else{
			$strQuery = sprintf("select user_answer,count(user_answer) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0 group by user_answer",$intQuestionSeq,$intExampleSeq);
		}
		$arrResult = $this->resAnswerDB->DB_access($this->resAnswerDB,$strQuery);
		return($arrResult);
	}
}
?>