<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class WrongNote{
	private $resWrongDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resWrongDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getWrongNote($intUserSeq,$intNoteSeq=null){
		if($intNoteSeq){
			$strQuery = sprintf("select * from wrong_note where user_seq=%d and seq=%d",$intUserSeq,$intNoteSeq);
		}else{
			$strQuery = sprintf("select * from wrong_note where user_seq=%d ",$intUserSeq);
		}
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	public function getWrongNoteFromQuestion($strMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq){
		include("Model/ManGong/SQL/MySQL/WrongNode/getWrongNoteFromQuestion.php");
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	public function getWrongNoteListCount($intUserSeq,$arrSearch,$mixTeacherSeq,$boolMD5){
		//$strQuery = sprintf("select count(*) as cnt from wrong_note_list where delete_flg=0 and user_seq=%d",$intUserSeq);
		$strQuery = sprintf("SELECT count(*) as cnt FROM wrong_note_list wn, test su WHERE wn.test_seq=su.seq AND wn.delete_flg=0 AND wn.user_seq=%d ",$intUserSeq);
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(su.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND su.writer_seq=%d ",$mixTeacherSeq);
		}
		if(count($arrSearch)>0){
			$strQuery .= " AND (wn.note like '%".$arrSearch['note']."%' or su.subject like '%".$arrSearch['subject']."%') ";
		}
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getWrongAnswer($intUserSeq,$intNoteSeq){
		$strQuery = sprintf("select * from wrong_note_list where user_seq=%d and seq=%d",$intUserSeq,$intNoteSeq);
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	public function updateWrongNoteList($intNoteSeq,$intUserSeq,$strNote){
		$strQuery = sprintf("update wrong_note_list set note='%s' where seq=%d and user_seq=%d",$strNote,$intNoteSeq,$intUserSeq);
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);
	}
	public function getWrongNoteList($intUserSeq,$arrSearch,$arrOrder,$mixTeacherSeq=null,$boolMD5=false,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getWrongNoteListCount($intUserSeq,$arrSearch,$mixTeacherSeq,$boolMD5);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}		
		//$strQuery = sprintf("select * from wrong_note_list where delete_flg=0 and user_seq=%d order by create_date desc ",$intUserSeq);
		$strQuery = sprintf("SELECT *,wn.seq as wrong_note_list_seq FROM wrong_note_list wn, test su WHERE wn.test_seq=su.seq AND wn.delete_flg=0 AND wn.user_seq=%d AND su.delete_flg=0 ",$intUserSeq);
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(su.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND su.writer_seq=%d ",$mixTeacherSeq);
		}
		if(count($arrSearch)>0){
			if(array_key_exists('note', $arrSearch)){
				$strQuery .= " AND (wn.note like '%".$arrSearch['note']."%' or su.subject like '%".$arrSearch['subject']."%') ";
			}
			if(array_key_exists('record_seq', $arrSearch)){
				$strQuery .= sprintf(" AND md5(wn.record_seq)='%s' ",$arrSearch['record_seq']);
			}			
		}
		$strQuery .= sprintf(" order by wn.create_date desc ");
		
		if($arrPaging){
			$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
		}
		echo $strQuery;
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);		
	}
	public function setWrongNote($intUserSeq,$strNoteTitle){
		$strQuery = sprintf("insert into wrong_note (user_seq,note_title,create_date,last_update_date,delete_flg) values (%d,'%s',now(),now(),0)",$intUserSeq,quote_smart($strNoteTitle));
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		if($boolResult){
			$mixResult = mysql_insert_id($this->resWrongDB->res_DB);
		}else{
			$mixResult = $boolResult;
		}
		return($mixResult);		
	}
	public function setWrongNoteQuestion($intNoteSeq,$intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq,$intUserAnswer,$strWrongNoteFileName,$strQuestion){
		$arrWrongNote = $this->getWrongNoteFromQuestion($intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq);
		if(count($arrWrongNote)>0){
			include("Model/ManGong/SQL/MySQL/WrongNode/setWrongNoteQuestionUpdate.php");
		}else{
			include("Model/ManGong/SQL/MySQL/WrongNode/setWrongNoteQuestionInsert.php");
		}
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}	
	public function deleteWrongNote($intUserSeq,$intWrongNoteSeq){
		$strQuery = sprintf("update wrong_note set delete_flg=1 where user_seq=%d and seq=%d",$intUserSeq,$intWrongNoteSeq);
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);
	}	
	public function updateWrongNote($intUserSeq,$strNoteTitle,$intWrongNoteSeq){
		$strQuery = sprintf("update wrong_note set note_title='%s' where user_seq=%d and seq=%d",$strNoteTitle,$intUserSeq,$intWrongNoteSeq);
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
	/*
	 * $arrNoteList = array(array(`wrong_note_seq`, `user_seq`, `test_seq`, `record_seq`, `question_seq`, `user_answer`,`test_date`, `note`))
	 * 
	 */
	public function setWrongNoteList($intUserSeq,$intNoteSeq,$arrNoteList){
		$arrValues = array();
		foreach($arrNoteList as $intKey=>$arrResult){
			$strDummyQuery = sprintf(
					"(%d, %d, %d, %d, %d, %d, '%s', now(), '%s', '%s', 0)",
					$intNoteSeq,
					$intUserSeq,
					$arrResult['test_seq'],
					$arrResult['record_seq'],
					$arrResult['question_seq'],	
					$arrResult['question_order_no'],
					$arrResult['user_answer'],
					$arrResult['test_date'],
					$arrResult['note']
					);
			array_push($arrValues,$strDummyQuery);
		}
		$strQuery = sprintf("INSERT INTO `wrong_note_list` (`wrong_note_seq`, `user_seq`, `test_seq`, `record_seq`, `question_seq`,`question_order_no`, `user_answer`, `create_date`, `test_date`, `note`, `delete_flg`) VALUES %s",join(",",$arrValues));
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
	public function getWrongAnswerNoteFromTest($intTestSeq,$intRecordSeq,$intStudentSeq){
		$strQuery = sprintf("
				select r1.*,tql.order_number from (
				select ua.*,wn.seq as wrong_note_list_seq,wn.create_date as wrong_note_date from user_answer ua left outer join wrong_note_list wn on ua.test_seq=wn.test_seq and ua.record_seq=wn.record_seq and ua.user_seq=wn.user_seq and ua.question_seq=wn.question_seq where ua.test_seq=%d and ua.record_seq=%d and ua.user_seq=%d and ua.result_flg=0
				) as r1 left join test_question_list tql on r1.test_seq=tql.test_seq and r1.question_seq=tql.question_seq
				",$intTestSeq,$intRecordSeq,$intStudentSeq);
		$arrResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($arrResult);						
	}
	public function moveListToOtherNote($intUserSeq,$arrNoteListSeq,$intToNoteSeq){
		if(!is_array($arrNoteListSeq)){
			$arrNoteListSeq = array($arrNoteListSeq);
		}
		$strQuery = sprintf("update wrong_note_list set wrong_note_seq=%d where user_seq=%d and seq in (%s)",$intToNoteSeq,$intUserSeq,join(",",$arrNoteListSeq));
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
	public function deleteWrongNoteList($intUserSeq,$intWrongNoteListSeq){
		$strQuery = sprintf("update wrong_note_list set delete_flg=1 where user_seq=%d and seq=%d",$intUserSeq,$intWrongNoteListSeq);
		$boolResult = $this->resWrongDB->DB_access($this->resWrongDB,$strQuery);
		return($boolResult);		
	}
}
?>