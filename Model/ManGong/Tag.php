<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Tag{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resTagDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getTags($intTagType=null,$intMemberSeq=null){
		$strQuery = sprintf("select * from tag ",$intTestsSeq);
		if($intTagType){
			$strQuery .= sprintf(" where type=%d ",$intTagType);
		}
		if($intMemberSeq){
			$strQuery .= sprintf(" and member_seq=%d ",$intMemberSeq);
		}
		$arrReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($arrReturn);	
	}	
	public function getTestsTag($intTestsSeq){
		$strQuery = sprintf("select * from test_tag where test_seq=%d",$intTestsSeq);
		$arrReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($arrReturn);	
	}	
	public function getQuestionTag($intQuestionSeq){
		$strQuery = sprintf("select * from test_tag where test_seq=%d",$intTestsSeq);
		$arrReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($arrReturn);	
	}	
	//get Tag
	public function setTag($strTagName,$intTagType=2,$intMemberSeq){
		//$strQuery = sprintf("INSERT IGNORE INTO tag (tag, type, create_date) VALUES ('%s', %d, now())",$strTagName,$intTagType);
		$strQuery = sprintf("REPLACE INTO tag (tag, type, member_seq, create_date) VALUES ('%s', %d, %d, now())",quote_smart($strTagName),$intTagType,$intMemberSeq);
		$boolResult = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolResult);
	}
	public function setTestsTag($intTestsSeq,$strTag){
		//$strQuery = sprintf("insert IGNORE into test_tag (test_seq,tag,create_date) values (%d,'%s',now())",$intTestsSeq,$strTag);
		$strQuery = sprintf("REPLACE into test_tag (test_seq,tag,create_date) values (%d,'%s',now())",$intTestsSeq,quote_smart($strTag));
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	public function setQuestionTag($intQuestionSeq,$strTag,$boolDeleteFlg = false){
		//$strQuery = sprintf("insert IGNORE into question_tag (question_seq,tag,create_date) values (%d,'%s',now())",$intQuestionSeq,$strTag);
		$strQuery = sprintf("REPLACE into question_tag (question_seq,tag,create_date) values (%d,'%s',now())",$intQuestionSeq,quote_smart($strTag));
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	public function deleteTag($strTag,$intTagType){
		$strQuery = sprintf("delete from tags where tag='%s' and type=%d ",quote_smart($strTag),$intTagType);
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	public function deleteTestsTag($intTestsSeq,$strTag=null){
		$strQuery = sprintf("delete from test_tag where test_seq=%d ",$intTestsSeq);
		if($strTag){
			$strQuery .= sprintf(" and tag='%s' ",quote_smart($strTag));
		}
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
	public function deleteQuestionTag($intQuestionSeq,$strTag=null){
		$strQuery = sprintf("delete from question_tag where question_seq=%d ",$intQuestionSeq);
		if($strTag){
			$strQuery .= sprintf(" and tag='%s' ",quote_smart($strTag));
		}
		$boolReturn = $this->resTagDB->DB_access($this->resTagDB,$strQuery);
		return($boolReturn);
	}	
}
?>