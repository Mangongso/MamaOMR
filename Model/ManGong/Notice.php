<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Notice{
	private $resNoticeDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resNoticeDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setNotice($intMemberSeq,$intCategory,$intUserGroupSeq,$strTitle,$strContents,$file){
		$strQuery = sprintf("insert into notice set writer_seq=%d,category_seq=%d,user_group_seq=%d,notice_title='%s',notice_contents='%s',file_name='%s',create_date=now(),modify_date=now()",$intMemberSeq,$intCategory,$intUserGroupSeq,$strTitle,$strContents,$file);
		$arrResult = $this->resNoticeDB->DB_access($this->resNoticeDB,$strQuery);
		return($arrResult);		
	}
	public function getNotice($intNoticeSeq){
		$strQuery = sprintf("select * from notice where seq=%d ",$intNoticeSeq);
		$arrResult = $this->resNoticeDB->DB_access($this->resNoticeDB,$strQuery);
		return($arrResult);		
	}
	public function getNoticeList($intLimitNumber=null){
		$strQuery = "select * from notice ";
		if($intLimitNumber){
			$strQuery .= "where create_date desc";
		}
		$strQuery .= "order by create_date desc";
		$arrResult = $this->resNoticeDB->DB_access($this->resNoticeDB,$strQuery);
		return($arrResult);		
	}
}
?>