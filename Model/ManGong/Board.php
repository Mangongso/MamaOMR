<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/BBS/Post.php");

class Board extends Post{
	private $resBoardDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resBoardDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setProfile($intMemberSeq,$strContents,$strCareer){
		$strQuery = sprintf("insert into teacher_profile set teacher_seq=%d,contents='%s',career='%s',create_date=now(),modify_date=now()",$intMemberSeq,$strContents,$strCareer);
		$arrResult = $this->resBoardDB->DB_access($this->resBoardDB,$strQuery);
		return($arrResult);		
	}
	public function updateProfile($intMemberSeq,$strContents,$strCareer){
		$strQuery = sprintf("update teacher_profile set contents='%s',career='%s',modify_date=now() where teacher_seq=%d",$strContents,$strCareer,$intMemberSeq);
		$arrResult = $this->resBoardDB->DB_access($this->resBoardDB,$strQuery);
		return($arrResult);		
	}
	public function getProfile($intMemberSeq){
		$strQuery = sprintf("select * from teacher_profile where teacher_seq=%d ",$intMemberSeq);
		$arrResult = $this->resBoardDB->DB_access($this->resBoardDB,$strQuery);
		return($arrResult);		
	}
	public function setRightWrongMessage($intMemberSeq,$intTestSeq,$intQuestionSeq,$intExampleSeq,$strMessage){
		$strQuery = sprintf("insert into rignt_wrong_board set writer_seq=%d,test_seq=%d,question_seq=%d,example_seq=%d,contents='%s',create_date=now(),modify_date=now()",$intMemberSeq,$intTestSeq,$intQuestionSeq,$intExampleSeq,$strMessage);
		$arrResult = $this->resBoardDB->DB_access($this->resBoardDB,$strQuery);
		return($arrResult);		
	}
	public function setPostQAInfo($intPostSeq,$intTestSeq,$intQuestionSeq,$intTeacherSeq){
		$strQuery = sprintf("insert into post_qa_info set post_seq=%d,test_seq=%d,question_seq=%d,teacher_seq=%d",$intPostSeq,$intTestSeq,$intQuestionSeq,$intTeacherSeq);
		$arrResult = $this->resBoardDB->DB_access($this->resBoardDB,$strQuery);
		return($arrResult);		
	}
}
?>