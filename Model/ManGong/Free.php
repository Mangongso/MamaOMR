<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Free{
	private $resFreeDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resFreeDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getFreeBoard(){
		$strQuery = sprintf("select * from  _board where delete_flg=0",$intTestSeq,$intQuestionSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);
	}
	public function getFreeBoardAll(&$arrPaging){
		$intStartNumber = $arrPaging['page']?$arrPaging['page']*$arrPaging['result_number']:0;
		$intFreeBoardLastSeq = $arrPaging['free_board_last_seq']?$arrPaging['free_board_last_seq']:0;
		$strQuery .= sprintf("SELECT * FROM free_board WHERE delete_flg=0 ");
		if($intFreeBoardLastSeq){
			$strQuery .= sprintf(" and seq < %d",$intFreeBoardLastSeq);
		}
		$strQuery .= sprintf(" ORDER BY seq DESC limit 0,%d ",$arrPaging['result_number']+1);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		if(count($arrResult)<$arrPaging['result_number']+1){//next page is not
			$arrPaging['next_page']=0;//last page
		}else{// next page is 
			$arrPaging['next_page']=$arrPaging['page']?$arrPaging['page']+1:1;
			unset($arrResult[count($arrResult)-1]);
		}
		return($arrResult);
	}
	public function setFreeBoard($intMemberSeq, $strMemberName, $strMemberType, $strContents){
		$strQuery = sprintf("insert into free_board set writer_seq=%d,writer_name='%s',writer_type='%s',contents='%s',create_date=now()",$intMemberSeq, $strMemberName, $strMemberType, quote_smart($strContents));
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function updateFreeBoard($intMemberSeq,$intFreeSeq,$strContents){
		$strQuery = sprintf("update free_board set contents='%s',modify_date=now() where seq=%d",$strContents,$intFreeSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function updateFreeBoardCount($intFreeSeq,$intFreeCount){
		$strQuery = sprintf("update free_board set comment_count=%d where seq=%d",$intFreeCount,$intFreeSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function deleteFreeBoard($intFreeSeq){
		$strQuery = sprintf("update free_board set delete_flg=1 where seq=%d",$intFreeSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function getFreeBoardBySeq($intFreeBoardSeq){
		$strQuery = sprintf("select * from  free_board where seq=%d and delete_flg=0",$intFreeBoardSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);
	}
	/******** comment ********/
	public function getFreeBoardComment($intFreeSeq,$intDelFlg=null){
		$strQuery = sprintf("select * from  free_board_comment where free_board_seq=%d and delete_flg=0",$intFreeSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);
	}
	public function setFreeBoardComment($intFreeSeq,$intMemberSeq,$strMemberName,$strMemberType,$strContents){
		$strQuery = sprintf("insert into free_board_comment set free_board_seq=%d, writer_seq=%d,writer_name='%s',writer_type='%s',contents='%s',create_date=now()",$intFreeSeq,$intMemberSeq,$strMemberName,$strMemberType,quote_smart($strContents));
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function deleteFreeBoardComment($intFreeCommentSeq){
		$strQuery = sprintf("update free_board_comment set delete_flg=1 where seq=%d",$intFreeCommentSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	public function updateFreeBoardComment($intFreeCommentSeq,$strContents){
		$strQuery = sprintf("update free_board_comment set contents='%s',modify_date=now() where seq=%d",$strContents,$intFreeCommentSeq);
		$arrResult = $this->resFreeDB->DB_access($this->resFreeDB,$strQuery);
		return($arrResult);		
	}
	/******** end comment ********/
}
?>