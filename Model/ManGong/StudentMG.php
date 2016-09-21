<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class StudentMG{
	private $resStudentMGDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resStudentMGDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function setCounsel($intParentsSeq=0,$intWriterSeq,$strWriterType,$strWriterName,$intTargetSeq,$intCounselType,$strSubject,$strContents,$intEditAble=0,$intReadAble=0,&$intInsertSeq){
		$strQuery = sprintf("INSERT INTO counsel set parents_seq=%d, writer_seq=%d,  writer_type='%s', writer_name='%s', target_seq=%d,  type=%d, create_date=now(), modify_date=now(), subject='%s', contents='%s', edit_able=%d, read_able=%d",$intParentsSeq,$intWriterSeq,$strWriterType,$strWriterName,$intTargetSeq,$intCounselType,quote_smart($strSubject),quote_smart($strContents),$intEditAble,$intReadAble);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		$intInsertSeq = mysql_insert_id($this->resStudentMGDB->res_DB);
		return($boolResult);		
	}
	public function updateCounsel($intCounselSeq,$intWriterSeq,$intCounselType,$strSubject,$strContents,$intEditAble=0,$intReadAble=0){
		$strQuery = sprintf("update counsel set type=%d, subject='%s', contents='%s', modify_date=now(), edit_able=%d, read_able=%d where seq=%d and writer_seq=%d",$intCounselType,quote_smart($strSubject),quote_smart($strContents),$intEditAble,$intReadAble,$intCounselSeq,$intWriterSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function updateCounselByStudent($intCounselSeq,$intWriterSeq,$strContents){
		$strQuery = sprintf("update counsel set contents='%s', modify_date=now() where seq=%d and writer_seq=%d",quote_smart($strContents),$intCounselSeq,$intWriterSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function updateCounselReadFlg($intCounselSeq,$strMemberType,$intReadFlg=null){
		if(!is_null($intReadFlg)){
			switch($strMemberType){
				case('T'):
					$strQuery = sprintf(" update counsel set teacher_read_flg=%d,modify_date=now() where seq=%d ",$intReadFlg,$intCounselSeq);
				break;
				default:
					$strQuery = sprintf(" update counsel set student_read_flg=%d,modify_date=now() where seq=%d ",$intReadFlg,$intCounselSeq);
				break;
			}
		}
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function updateParentsSeqFromCounsel($intCounselSeq,$intParentsSeq,$intWriterSeq){
		$strQuery = sprintf("update counsel set parents_seq=%d, modify_date=now() where seq=%d and writer_seq=%d ",$intParentsSeq,$intCounselSeq,$intWriterSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function deleteCounsel($intCounselSeq,$intWriterSeq){
		$strQuery = sprintf("update counsel set delete_flg=1,modify_date=now() where seq=%d and writer_seq=%d ",$intCounselSeq,$intWriterSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function getCounsel($intCounselSeq,$intWriterSeq=null){
		$strQuery = sprintf("select * from counsel where seq=%d and delete_flg=0 ",$intCounselSeq);
		if($intWriterSeq){
			$strQuery .= sprintf(" and writer_seq=%d ",$intWriterSeq);
		}
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	public function getCounselList($intWriterSeq,$intTargetSeq=null,$strMemberType,&$arrPaging){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getCounselListCount($intWriterSeq,$intTargetSeq,$strMemberType);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		$strQuery = sprintf("select * from counsel where delete_flg=0 and writer_seq=%d ",$intWriterSeq);
		if($intTargetSeq){
			$strQuery .= sprintf(" and target_seq=%d ",$intTargetSeq);
		}
		if($strMemberType=='S'){
			$strQuery .= sprintf(" and (read_able=1 or edit_able=1) ");
		}
		$strQuery .= " order by create_date desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	public function getCounselListCount($intWriterSeq,$intTargetSeq=null,$strMemberType){
		$strQuery = sprintf("select count(*) as cnt  from counsel where delete_flg=0 and writer_seq=%d ",$intWriterSeq);
		if($intTargetSeq){
			$strQuery .= sprintf(" and target_seq=%d ",$intTargetSeq);
		}
		if($strMemberType=='S'){
			$strQuery .= sprintf(" and (read_able=1 or edit_able=1) ");
		}
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	
	/* record_mg */
	public function setRecordMG($intTeacherSeq,$intStudentSeq,$strItems,$strDuringPeriod,$strScoreRating,&$intInserSeq){
		$strQuery = sprintf("INSERT INTO record_mg set teacher_seq=%d, student_seq=%d,  items='%s', during_period='%s', score_rating='%s', create_date=now(), modify_date=now() ",$intTeacherSeq,$intStudentSeq,quote_smart($strItems),quote_smart($strDuringPeriod),quote_smart($strScoreRating));
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		$intInserSeq = mysql_insert_id($this->resStudentMGDB->res_DB);
		return($boolResult);		
	}
	public function updateRecordMG($intRecordMGSeq,$strItems,$strDuringPeriod,$strScoreRating,$intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("update record_mg set items='%s', during_period='%s', score_rating='%s', modify_date=now() where seq=%d and teacher_seq=%d and student_seq=%d",quote_smart($strItems),quote_smart($strDuringPeriod),quote_smart($strScoreRating),$intRecordMGSeq,$intTeacherSeq,$intStudentSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function deleteRecordMG($intRecordMGSeq,$intTeacherSeq=null,$intStudentSeq=null){
		$strQuery = sprintf("update record_mg set delete_flg=1, modify_date=now() where seq=%d and teacher_seq=%d and student_seq=%d",$intRecordMGSeq,$intTeacherSeq,$intStudentSeq);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($boolResult);		
	}
	public function getStudentMGList($intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("select * from record_mg where delete_flg=0 and teacher_seq=%d and student_seq=%d ",$intTeacherSeq,$intStudentSeq);
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	
	/* get update cunsel */
	public function getNewCounselCount($intWriterSeq,$intTargetSeq,$strMemberType){
		$strQuery = sprintf("SELECT count(*) as cnt FROM counsel 
							WHERE delete_flg=0 ");
		if($intWriterSeq){
			$strQuery .= sprintf(" and writer_seq=%d ",$intWriterSeq);
		}
		if($intTargetSeq){
			$strQuery .= sprintf(" and target_seq=%d ",$intTargetSeq);
		}
		if($strMemberType=='T'){
			$strQuery .= " AND teacher_read_flg=0 ";
		}else{
			$strQuery .= " AND student_read_flg=0 AND (read_able=1 OR edit_able=1)";
		}
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	
	/* SOMR manager 
	 * somr 의 매니저 등록 관련 function
	 * */
	public function updateManagerStudent($intManagerSeq,$strStudentSeq,$strAuthKey){
		$strQuery = sprintf("update student_manager set manager_seq=%d, modify_date=now() where md5(student_seq)='%s' and auth_key='%s' ",$intManagerSeq,$strStudentSeq,$strAuthKey);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		$intInserSeq = mysql_insert_id($this->resStudentMGDB->res_DB);
		return($boolResult);		
	}
	public function getManagerStudentList($strManagerSeq,$strStudentSeq=null){
		$strQuery = sprintf("select * from student_manager where delete_flg=0 and md5(manager_seq)='%s'",$strManagerSeq);
		if(!is_null($strStudentSeq)){
			$strQuery .= sprintf(" and md5(manager_seq)='%s'",$strStudentSeq);
		}
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	public function getManagerStudentByAuthKey($strAuthKey){
		$strQuery = sprintf("select * from student_manager where delete_flg=0 and auth_key='%s'",$strAuthKey);
		$arrResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		return($arrResult);		
	}
	public function setManagerStudentAuthKey($intStudentSeq,$strAuthKey){
		$strQuery = sprintf("INSERT INTO student_manager set student_seq=%d, auth_key='%s', create_date=now() ",$intStudentSeq,$strAuthKey);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		$intInserSeq = mysql_insert_id($this->resStudentMGDB->res_DB);
		return($boolResult);		
	}
	public function deleteManagerStudentAuthKey($strStudentSeq,$strAuthKey){
		$strQuery = sprintf("update student_manager set auth_key='', modify_date=now() where md5(student_seq)='%s' and auth_key='%s' ",$strStudentSeq,$strAuthKey);
		$boolResult = $this->resStudentMGDB->DB_access($this->resStudentMGDB,$strQuery);
		$intInserSeq = mysql_insert_id($this->resStudentMGDB->res_DB);
		return($boolResult);		
	}
	
}
?>