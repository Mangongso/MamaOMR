<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Group{
	private $resGroupDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resGroupDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getGroup(){
		$strQuery = "select * from test_group where delete_flg=0";
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function getGroupList($mixTeacherSeq,$arrOrder=array("seq asc")){
		if(is_array($mixTeacherSeq)){
			$strQuery = sprintf("select * from group_list where teacher_seq in (".join(',',$mixTeacherSeq).") and delete_flg=0 ");
		}else{
			$strQuery = sprintf("select * from group_list where teacher_seq=%d and delete_flg=0 ",$mixTeacherSeq);
		}
		if(count($arrOrder)){
			$strQuery .= " order by ".join(',', $arrOrder);
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function checkGroupName($intTeacherSeq,$strGroupName){
		$strQuery = sprintf("select * from group_list where teacher_seq=%d and group_name='%s' and delete_flg=0",$intTeacherSeq,$strGroupName);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$boolResult = false;
		if(count($arrResult)>0){
			$boolResult = true;
		}
		return($boolResult);
	}
	public function setGroupList($intTeacherSeq,$strGroupName,&$intGroupSeq){
		$strQuery = sprintf("insert into group_list set teacher_seq=%d,group_name='%s',create_date=now()",$intTeacherSeq,$strGroupName);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$intGroupSeq = mysql_insert_id($this->resGroupDB->res_DB);
		return($arrResult);
	}
	public function updateGroupList($intGroupSeq,$strGroupName){
		$strQuery = sprintf("update group_list set group_name='%s',modify_date=now() where seq=%d",$strGroupName,$intGroupSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$intGroupSeq = mysql_insert_id($this->resGroupDB->res_DB);
		return($arrResult);
	}
	public function deleteGroupList($intGroupSeq){
		$strQuery = sprintf("update group_list set delete_flg=1,modify_date=now() where seq=%d",$intGroupSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$intGroupSeq = mysql_insert_id($this->resGroupDB->res_DB);
		return($arrResult);
	}
	public function getGroupUserList($intGroupSeq){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq WHERE B.member_seq IN(select student_seq from group_user_list where group_seq=%d and delete_flg=0) AND del_flg='0' order by A.name",$intGroupSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function getGroupByStudentSeq($mixTeacherSeq=null,$intStudentSeq,$boolMd5=false){
		if($mixTeacherSeq){
			if($boolMd5){
				$strQuery = sprintf("SELECT * FROM (SELECT * FROM group_user_list WHERE student_seq = %d AND md5(teacher_seq)='%s' and delete_flg=0) A LEFT OUTER JOIN group_list B ON A.group_seq=B.seq where B.delete_flg=0",$intStudentSeq,$mixTeacherSeq);
			}else{
				$strQuery = sprintf("SELECT * FROM (SELECT * FROM group_user_list WHERE student_seq = %d AND teacher_seq=%d and delete_flg=0) A LEFT OUTER JOIN group_list B ON A.group_seq=B.seq where B.delete_flg=0",$intStudentSeq,$mixTeacherSeq);
			}
		}else{
			$strQuery = sprintf("SELECT * FROM (SELECT * FROM group_user_list WHERE student_seq = %d and delete_flg=0) A LEFT OUTER JOIN group_list B ON A.group_seq=B.seq where B.delete_flg=0",$intStudentSeq);
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function getNotSelectedUserList($intTeacherSeq,$arrSelectedUser){
		$strQuery = sprintf("SELECT * FROM member_basic_info WHERE member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d ",$intTeacherSeq);
		if(count($arrSelectedUser)>0){
			$strQuery .= " AND student_seq not in(".implode(',',$arrSelectedUser).")";
		}
		$strQuery .= "and approve_flg=1 and delete_flg=0) AND del_flg='0'";
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);		
	}
	public function setGroupUserList($intTeacherSeq,$intGroupSeq,$arrGroupUser){
		$strQuery = "insert into group_user_list (group_seq,teacher_seq,student_seq) values ";
		$intGroupUserCount = count($arrGroupUser);
		foreach($arrGroupUser as $intKey=>$arrResult){
			if(($intGroupUserCount-1)==$intKey){
				$strQuery .= sprintf("(%d,%d,%d)",$intGroupSeq,$intTeacherSeq,$arrResult);
			}else{
				$strQuery .= sprintf("(%d,%d,%d),",$intGroupSeq,$intTeacherSeq,$arrResult);
			}
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		$intGroupSeq = mysql_insert_id($this->resGroupDB->res_DB);
		return($arrResult);
	}
	public function deleteGroupUserList($intGroupSeq){
		$strQuery = sprintf("update group_user_list set delete_flg=1 where group_seq=%d",$intGroupSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function deleteGroupUserListByStudentSeq($intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("update group_user_list set delete_flg=1 where teacher_seq=%d and student_seq=%d",$intTeacherSeq,$intStudentSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);
	}
	public function setTeacherStudentList($intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("insert into teacher_student_list set teacher_seq=%d ,student_seq=%d",$intTeacherSeq,$intStudentSeq);
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherStudentList($intTeacherSeq,$intApproveFlg=null,$intGroupSeq=null,&$arrPaging=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTeacherStudentListCount($intTeacherSeq,$intApproveFlg,$intGroupSeq);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		//$strQuery = sprintf("SELECT * FROM member_basic_info WHERE member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		if($intGroupSeq){
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM group_user_list WHERE teacher_seq=%d AND group_seq=%d and delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intGroupSeq);
		}else if($intApproveFlg===0 || $intApproveFlg){
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND approve_flg=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intApproveFlg);
		}else{
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		}
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		//print_r($strQuery);
		//exit;
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherStudentListCount($intTeacherSeq,$intApproveFlg=null,$intGroupSeq=null){
		//$strQuery = sprintf("SELECT * FROM member_basic_info WHERE member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		if($intGroupSeq){
			$strQuery = sprintf("select count(*) as cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM group_user_list WHERE teacher_seq=%d AND group_seq=%d and delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intGroupSeq);
		}else if($intApproveFlg===0 || $intApproveFlg){
			$strQuery = sprintf("select count(*) as cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND approve_flg=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intApproveFlg);
		}else{
			$strQuery = sprintf("select count(*) as cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getTeacherStudentListBySearch($intTeacherSeq,$intApproveFlg,$strSearchType,$strSearch,&$arrPaging=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTeacherStudentListBySearchCount($intTeacherSeq,$intApproveFlg,$strSearchType,$strSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		if($intApproveFlg===0 || $intApproveFlg){
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND approve_flg=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intApproveFlg);
		}else{
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		}
		switch($strSearchType){
			case('name'):
			case('email'):
				$strQuery .= " AND A.".$strSearchType." like '%".quote_smart($strSearch)."%' ";
			break;
			case('cphone'):
				$strQuery .= " AND B.".$strSearchType." like '%".quote_smart($strSearch)."%' ";
			break;
		}
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherStudentListBySearchCount($intTeacherSeq,$intApproveFlg,$strSearchType,$strSearch){
		if($intApproveFlg===0 || $intApproveFlg){
			$strQuery = sprintf("select count(*)as cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND approve_flg=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq,$intApproveFlg);
		}else{
			$strQuery = sprintf("select count(*)as cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq IN(SELECT student_seq FROM teacher_student_list WHERE teacher_seq=%d AND delete_flg=0) AND del_flg='0'",$intTeacherSeq);
		}
		switch($strSearchType){
			case('name'):
			case('email'):
				$strQuery .= " AND A.".$strSearchType." like '%".quote_smart($strSearch)."%' ";
			break;
			case('cphone'):
				$strQuery .= " AND B.".$strSearchType." like '%".quote_smart($strSearch)."%' ";
			break;
		}
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult[0]['cnt']);		
	}
	public function getTeacherStudentApproveList($intTeacherSeq,$intGroupSeq=null,$intTerm=1,$arrSch=array(),&$arrPaging=null){
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getTeacherStudentApproveListCnt($intTeacherSeq,$intGroupSeq,$intTerm,$arrSch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param'] 
			);
		}
		include("Model/ManGong/SQL/MySQL/Group/getTeacherStudentApproveList.php");
		
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherStudentApproveListCnt($intTeacherSeq,$intGroupSeq=null,$intTerm=null,$arrSch=array()){
		include("Model/ManGong/SQL/MySQL/Group/getTeacherStudentApproveList.php");
		$arrResult = $this->resGroupDB->DB_access($this->resGroupDB,$strQuery);
		return(count($arrResult));		
	}
}
?>