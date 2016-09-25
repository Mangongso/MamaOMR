<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Group{
	private $resGroupDB = null;
	private $objPaging = null;
	public function __construct($resGroupDB=null){
		$this->objPaging =  new Paging();
		$this->resGroupDB = $resGroupDB;
	}
	public function __destruct(){}
	public function setGroup($strGroupName,&$intGroupSeq=null){
		if(is_null($intGroupSeq)){
			$strQuery = sprintf("INSERT INTO test_group (group_name, create_date, delete_flg) VALUES ('%s', now(), default)",$strGroupName);
		}else{
			$strQuery = sprintf("update test_group set group_name='%s' where seq=%d",$strGroupName,$$intGroupSeq);
		}
		$boolReturn = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$intGroupSeq = mysql_insert_id($this->resGroupDB->res_DB);
		return($boolReturn);		
	}
 	public function deleteGroup($intGroupSeq){
		$strQuery = sprintf("update test_group set delete_flg=1 where seq=%d",$intGroupSeq);
		$boolReturn = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($boolReturn);
	}
	public function setGroupUser($intGroupSeq,$intMemberSeq){
		$strQuery = sprintf("INSERT INTO test_group_user (test_group_seq, member_seq, delete_flg) VALUES (%d, %d, default)",$intGroupSeq,$intMemberSeq);
		$boolReturn = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($boolReturn);		
	}
 	public function deleteGroupUser($intGroupSeq,$intMemberSeq){
		$strQuery = sprintf("update test_group_user set delete_flg=1 where test_group_seq=%d and member_seq=%d",$intGroupSeq,$intMemberSeq);
		$boolReturn = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($boolReturn);
	}
}
?>