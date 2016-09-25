<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
//require_once("Model/Member/Member.php");

class Comment{
	private $resCommentDB = null;
	private $objPaging = null;
	public function __construct($resCommentDB=null){
		$this->objPaging =  new Paging();
		//$this->objMember =  new Member($resCommentDB);
		$this->resCommentDB = $resCommentDB;
	}
	public function __destruct(){}
	public function setComment($arrInput,&$intCommentSeq=null,$strType="INSERT"){
		include("Model/PMS/SQL/MySQL/Comment/setComment.php");
		$boolReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		$intCommentSeq = mysql_insert_id($this->resCommentDB->res_DB);
		return($boolReturn);		
	}
 	public function deleteComment($intCommentSeq,$strType="DELETE"){
		include("Model/PMS/SQL/MySQL/Comment/setComment.php");
		$boolReturn = $this->resCommentDB->DB_access($this->resCommentDB,$strQuery);
		return($boolReturn);
	}
	public function updateComment($intCommentSeq,$arrInput,$strType="UPDATE"){
		include("Model/PMS/SQL/MySQL/Comment/setComment.php");
		$arrComment = $this->getComment($intCommentSeq);
		return($boolReturn);		
	}
	public function updateCommentStatus($intCommentSeq,$intStatus){
		
	}
	public function setCommentEntry($intCommentSeq,$intEntryEmail){
		// email 을 사용하는 이유는 클라우드 서비스를 위한 entry 의 Unique 한 값이기 떄문임
	}
	public function deleteCommentEntry($intCommentSeq,$intEntryEmail){
	}	
}
?>