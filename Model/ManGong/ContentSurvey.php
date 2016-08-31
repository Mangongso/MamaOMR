<?
require_once("Model/Core/DataManager/DataHandler.php");

class ContentTests{
	private $resContentTestsDB = null;
	public function __construct($resProjectDB=null){
		$this->resContentTestsDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setContentTests($intTeasherSeq,$intStudentSeq,$intTempleteId,$arrQuestion,$arrAnswer){
		$arrSetValue = array();
		foreach($arrQuestion as $intKey=>$strQuestion){
			if(is_array($arrAnswer[$intKey])){
				foreach($arrAnswer[$intKey] as $intSubKey=>$strSubResult){
					if(!$intSubKey){
						$strAnswer = $strSubResult;
					}else{
						$strAnswer .= ' | '.$strSubResult;
					}
				}
			}else{
				$strAnswer = $arrAnswer[$intKey];
			}
			$strSetValue = sprintf("(%d, %d, %d, '%s', '%s', now())",$intTempleteId, $intTeasherSeq, $intStudentSeq, $strQuestion, $strAnswer);
			array_push($arrSetValue,$strSetValue);
		}
		$strQuery = "INSERT INTO content_test (templete_id, teacher_seq, student_seq, question, answer, create_date) VALUES ".join(',', $arrSetValue);
		$boolReturn = $this->resContentTestsDB->DB_access($this->resContentTestsDB,$strQuery);
		//$intContentTestsSeq = mysql_insert_id($this->resContentTestsDB->res_DB);
		return($boolReturn);		
	}
	public function getContentTests($intTempleteId,$intTeasherSeq=null,$intStudentSeq=null){
		$strQuery = sprintf(" select * from content_test where templete_id=%d and delete_flg=0 ",$intTempleteId);
		if(!is_null($intTeasherSeq)){
			$strQuery = sprintf(" and teacher_seq=%d ",$intTeasherSeq);
		}
		if(!is_null($intStudentSeq)){
			$strQuery = sprintf(" and student_seq=%d ",$intStudentSeq);
		}
		$boolReturn = $this->resContentTestsDB->DB_access($this->resContentTestsDB,$strQuery);
		$intContentTestsSeq = mysql_insert_id($this->resContentTestsDB->res_DB);
		return($boolReturn);		
	}
}
?>