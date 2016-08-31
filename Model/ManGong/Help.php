<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Help{
	private $resHelpDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resHelpDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getHelpBoard($intTestSeq=null,$intQuestionSeq=null,$intHelpBoardSeq=null){
		$strQuery = "select * from  help_board where delete_flg=0";
		if($intTestSeq){
			$strQuery .= sprintf(" and test_seq=%d ",$intTestSeq);
		}
		if($intQuestionSeq){
			$strQuery .= sprintf(" and question_seq=%d ",$intQuestionSeq);
		}
		if($intHelpBoardSeq){
			$strQuery .= sprintf(" and seq=%d ",$intHelpBoardSeq);
		}
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);
	}
	public function getHelpBoardAll($intMemberSeq,$strMemberType,$mixTeacherSeq=null,$boolMD5=false,&$arrPaging){
		$intStartNumber = $arrPaging['page']?$arrPaging['page']*$arrPaging['result_number']:0;
		$intHelpBoardLastSeq = $arrPaging['help_board_last_seq']?$arrPaging['help_board_last_seq']:0;
		if($strMemberType=='T'){
			/*
			$strQuery = sprintf("SELECT h.*,s.subject,q.contents AS question_contents FROM
									(SELECT * FROM help_board WHERE delete_flg=0 
										AND teacher_seq=%d
									) AS h,	
									(SELECT * FROM test WHERE delete_flg=0) AS s,
									(SELECT * FROM question WHERE delete_flg=0) AS q
								WHERE h.question_seq = q.seq
								AND h.test_seq = s.seq
								ORDER BY h.seq DESC"
						,$intMemberSeq);
			*/
			//get total count
			$strQuery = sprintf("SELECT * FROM help_board WHERE teacher_seq=%d AND delete_flg=0 ",$intMemberSeq);
			if($intHelpBoardLastSeq){
				$strQuery .= sprintf(" and seq < %d ",$intHelpBoardLastSeq);
			}
			$strQuery .= sprintf(" ORDER BY seq DESC limit 0,%d",$arrPaging['result_number']+1);
		}else{
			/*
			$strQuery = sprintf("SELECT h.*,s.subject,q.contents AS question_contents FROM
									(SELECT * FROM help_board WHERE delete_flg=0 
												AND test_seq IN(SELECT DISTINCT(test_seq) FROM help_board WHERE writer_seq=38314179 AND delete_flg=0)
												AND question_seq IN(SELECT DISTINCT(question_seq) FROM help_board WHERE writer_seq=38314179 AND delete_flg=0)
									) AS h,	
									(SELECT * FROM test WHERE delete_flg=0) AS s,
									(SELECT * FROM question WHERE delete_flg=0) AS q
								WHERE h.question_seq = q.seq
								AND h.test_seq = s.seq
								ORDER BY h.seq DESC"
						,$intMemberSeq);
						*/
			//$strQuery = sprintf("SELECT * FROM help_board WHERE writer_seq=%d AND delete_flg=0 ORDER BY seq DESC limit %d,%d",$intMemberSeq,$intStartNumber,$arrPaging['result_number']+1);
			$strQuery = sprintf("SELECT * FROM help_board WHERE writer_seq=%d AND delete_flg=0 ",$intMemberSeq);
			if($mixTeacherSeq && $boolMD5){
				$strQuery .= sprintf(" AND md5(teacher_seq)='%s' ",$mixTeacherSeq);
			}else if($mixTeacherSeq && !$boolMD5){
				$strQuery .= sprintf(" AND teacher_seq=%d ",$mixTeacherSeq);
			}
			if($intHelpBoardLastSeq){
				$strQuery .= sprintf(" AND seq < %d ",$intHelpBoardLastSeq);
			}
			$strQuery .= sprintf(" ORDER BY seq DESC limit 0,%d ",$arrPaging['result_number']+1);
		}
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		if(count($arrResult)<$arrPaging['result_number']+1){//next page is not
			$arrPaging['next_page']=0;//last page
		}else{// next page is 
			$arrPaging['next_page']=$arrPaging['page']?$arrPaging['page']+1:1;
			unset($arrResult[count($arrResult)-1]);
		}
		return($arrResult);
	}
	public function getHelpBoardBySeq($intHelpBoardSeq){
		$strQuery = sprintf("select * from  help_board where seq=%d and delete_flg=0",$intHelpBoardSeq);
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);
	}
	public function setHelpBoard($intMemberSeq, $intTeacherSeq, $strMemberName, $strMemberType, $intTestSeq=null, $intQuestionSeq=null, $strTestSubject=null, $intQuestionNumber=null, $strContents){
		$strQuery = sprintf("insert into help_board set writer_seq=%d,teacher_seq=%d,writer_name='%s',writer_type='%s',test_seq=%d,question_seq=%d, test_subject='%s', question_number=%d,contents='%s',create_date=now()",$intMemberSeq, $intTeacherSeq, $strMemberName, $strMemberType, $intTestSeq, $intQuestionSeq, $strTestSubject, $intQuestionNumber, quote_smart($strContents));
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	public function updateHelpBoard($intMemberSeq,$intHelpBoardSeq,$strContents){
		$strQuery = sprintf("update help_board set contents='%s',modify_date=now() where writer_seq=%d and seq=%d",quote_smart($strContents),$intMemberSeq,$intHelpBoardSeq);
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	public function updateHelpBoardCount($intHelpBoardSeq,$intHelpCount){
		$strQuery = sprintf("update help_board set comment_count=%d where seq=%d",$intHelpCount,$intHelpBoardSeq);
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	public function deleteHelpBoard($intMemberSeq=null,$intHelpBoardSeq){
		$strQuery = sprintf("update help_board set delete_flg=1 where seq=%d",$intHelpBoardSeq);
		if(!is_null($intMemberSeq)){
			$strQuery .= sprintf(" and writer_seq=%d ",$intMemberSeq);
		}
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	/******** comment ********/
	public function getHelpComment($intHelpBoardSeq,$intDelFlg=null){
		$strQuery = sprintf("select * from  help_comment where help_board_seq=%d and delete_flg=0",$intHelpBoardSeq);
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);
	}
	public function setHelpComment($intHelpBoardSeq,$intMemberSeq,$strMemberName,$strMemberType,$strContents){
		$strQuery = sprintf("insert into help_comment set help_board_seq=%d, writer_seq=%d,writer_name='%s',writer_type='%s',contents='%s',create_date=now()",$intHelpBoardSeq,$intMemberSeq,$strMemberName,$strMemberType,quote_smart($strContents));
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	public function deleteHelpComment($intMemberSeq,$intHelpCommentSeq){
		$strQuery = sprintf("update help_comment set delete_flg=1 where seq=%d ",$intHelpCommentSeq);
		if(!is_null($intMemberSeq) && $_SESSION[$_COOKIE['member_token']]['member_type']=="S"){
			$strQuery .= sprintf(" and writer_seq='%s' ",$intMemberSeq);
		}
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	public function updateHelpComment($intMemberSeq,$intHelpCommentSeq,$strContents){
		$strQuery = sprintf("update help_comment set contents='%s',modify_date=now() where writer_seq=%d and seq=%d",quote_smart($strContents),$intMemberSeq,$intHelpCommentSeq);
		$arrResult = $this->resHelpDB->DB_access($this->resHelpDB,$strQuery);
		return($arrResult);		
	}
	/******** end comment ********/
}
?>