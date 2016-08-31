<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Member/Member.php");

class Teacher extends Member{
	private $resTeacherDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resTeacherDB = $resProjectDB;
		$this->resMemberDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getAllTeacher($arrSearch=array(),&$arrPaging=null,$strDelFlg=null){
		if($this->resTestsDB->DB_name=="db_idgkr_quiz"){
			include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
		}else{
			include("Model/ManGong/SQL/MySQL/Common/commonWhereQuery.php");
		}
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getAllTeacherCnt($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='T' ";
		if(is_null($strDelFlg)){
			$strQuery .= sprintf(" and A.del_flg='0' ");
		}else{
			$strQuery .= sprintf(" and A.del_flg='%s' ",$strDelFlg);
		}
		 
		if(count($arrWhereQuery)){
			$strQuery .= " and ".join(" and ",$arrWhereQuery);
		}
		$strQuery .= " order by A.member_seq desc ";
		if($arrPaging){
			if($strQuery){
				$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
			}
		}
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function getAllTeacherCnt($arrSearch=array()){
		if($this->resTestsDB->DB_name=="db_idgkr_quiz"){
			include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
		}else{
			include("Model/ManGong/SQL/MySQL/Common/commonWhereQuery.php");
		}
		$strQuery = "select count(*) cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='T' and A.del_flg='0' ";
		if(count($arrWhereQuery)){
			$strQuery .= " and ".join(" and ",$arrWhereQuery);
		}
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	public function getAllTeacherBySubject($intSubjectCode){
		$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq ";
		if(strlen($intSubjectCode)>2){
			$strQuery .= " RIGHT JOIN (SELECT DISTINCT(member_seq) FROM member_subject_list WHERE subject_code like '%".$intSubjectCode."%') C ON A.member_seq=C.member_seq ";
		}else{//과목으로만 가져오기
			$strQuery .= " RIGHT JOIN (SELECT DISTINCT(member_seq) FROM member_subject_list WHERE subject_code like '%".$intSubjectCode."') C ON A.member_seq=C.member_seq ";
		}
		$strQuery .= " where member_type='T' and A.del_flg='0' and A.level<10000 ";
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function getTeacher($intTeacherSeq){
		$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='T' and A.member_seq=".$intTeacherSeq;
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function searchTeacher($arrSearch=array()){
		$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where del_flg='0' ";
		if($arrSearch['name']){
			$strQuery .= sprintf(" and A.name='%s' ",$arrSearch['name']);
		}
		if($arrSearch['cphone']){
			$strQuery .= " and B.cphone like '%".$arrSearch['cphone']."'";
		}
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function getTeachersByStudentSeq($intStudentSeq){
		$strQuery = sprintf("SELECT *
							FROM (SELECT DISTINCT(teacher_seq),approve_flg FROM teacher_student_list WHERE student_seq=%d AND delete_flg=0) t,
								member_basic_info mb,
								member_extend_info me 
							WHERE t.teacher_seq = mb.member_seq 
							AND mb.member_seq=me.member_seq
							AND del_flg='0' ",$intStudentSeq);
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherProfile($intTeacherSeq){
		$strQuery = "select * from teacher_profile where teacher_seq=".$intTeacherSeq;
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function getMemberByLevel($intLevel){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.level=%d",$intLevel);
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function getMemberByTeacherKey($intMemberSeq,$strTeacherKey){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq=%d and auth_key='%s'",$intMemberSeq,$strTeacherKey);
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);
	}
	public function checkTeacherNumber($cphone,$intTeacherNumber){
		$strQuery = sprintf("select * from mobile_auth where cphone='%s' and auth_key=md5(%d)",$cphone,$intTeacherNumber);
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		if(count($arrResult)>0){
			$boolResult=true;
		}else{
			$boolResult=false;
		}
		return($boolResult);		
	}
	public function setTeacherStudentList($intTeacherSeq,$intStudentSeq,$intApproveFlg=null){
		$strQuery = sprintf("insert into teacher_student_list set teacher_seq=%d,student_seq=%d,apply_date=now()",$intTeacherSeq,$intStudentSeq);
		if($intApproveFlg){
			$strQuery .= ",approve_flg=1,approve_date=now()";
		}
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);		
	}
	public function updateTeacherStudentList($intTeacherSeq,$intStudentSeq,$intApproveFlg){
		if($intApproveFlg){
			$strQuery = sprintf("update teacher_student_list set approve_flg=%d,approve_date=now() where teacher_seq=%d and student_seq=%d",$intApproveFlg,$intTeacherSeq,$intStudentSeq);
		}else{
			$strQuery = sprintf("update teacher_student_list set approve_flg=%d,modify_date=now() where teacher_seq=%d and student_seq=%d",$intApproveFlg,$intTeacherSeq,$intStudentSeq);
		}
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);		
	}
	public function deleteTeacherStudentList($intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("update teacher_student_list set delete_flg=1,modify_date=now() where teacher_seq=%d and student_seq=%d",$intTeacherSeq,$intStudentSeq);
		$arrResult = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrResult);		
	}
	public function setTeacherNumber($cphone,$intTeacherNumber){
		$strQuery = sprintf("insert into mobile_auth set cphone='%s',auth_key=md5(%d)",$cphone,$intTeacherNumber);
		$boolReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		$intTeacherSeq = mysql_insert_id($this->resTeacherDB->res_DB);
		return($boolReturn);		
	}
	public function updateTeacherExtendInfo($strAcademy,$strSchool,$strSubject,$intMemberSeq,$intBirthYear=null,$intBirthMonth=null,$intBirthDay=null){
		$strQuery = sprintf("update member_extend_info set academy='%s',school='%s',subject='%s' ",$strAcademy,$strSchool,$strSubject);
		if($intBirthYear){
			$strQuery .= sprintf(" ,birth_day_y=%d ",$intBirthYear);
		}
		if($intBirthMonth){
			$strQuery .= sprintf(" ,birth_day_m=%d ",$intBirthMonth);
		}
		if($intBirthDay){
			$strQuery .= sprintf(" ,birth_day_d=%d ",$intBirthDay);
		}
		$strQuery .= sprintf(" where member_seq=%d ",$intMemberSeq);
		$boolReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($boolReturn);		
	}
	public function getSubMaster($intUserSeq){
		$strQuery = sprintf("select sm.*,mi.nickname,mi.name,mi.cphone,mi.subject,(select count(*) from test where sub_master=sm.child_user_seq and writer_seq=sm.parent_user_seq and delete_flg=0) as quiz_cnt from sub_master as sm left join (SELECT mb.member_seq,mb.nickname,mb.name,me.cphone,me.subject FROM member_basic_info mb,member_extend_info me WHERE mb.member_seq=me.member_seq AND mb.del_flg='0') as mi on sm.child_user_seq=mi.member_seq where parent_user_seq=%d and delete_flg=0",$intUserSeq);
		$arrReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($arrReturn);
	}
	public function getParentMaster($intUserSeq,$setSessionFlg=0){
		//$strQuery = sprintf("select sm.*,mi.nickname,mi.name from sub_master as sm left join member_basic_info as mi on sm.child_user_seq=mi.member_seq where child_user_seq=%d and delete_flg=0",$intUserSeq);
		$strQuery = sprintf("select sm.*,mi.nickname,mi.name from sub_master as sm left join member_basic_info as mi on sm.parent_user_seq=mi.member_seq where child_user_seq=%d and delete_flg=0",$intUserSeq);
		$arrReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		if(count($arrReturn) && $setSessionFlg){
			$arrParentSession = array();
			//set session parent master info
			foreach($arrReturn as $intKey=>$arrResult){
				array_push($arrParentSession, array('member_seq'=>$arrResult['parent_user_seq'],'name'=>$arrResult['name']));
			}
			$_SESSION['login_info']['parent_master'] = $arrParentSession;
		}
		return($arrReturn);	
	}
	public function setSubMaster($intParentUserSeq,$intChildUserSeq){
		$strQuery = sprintf("insert into sub_master set parent_user_seq=%d,child_user_seq=%d,create_date=now(),status=100,delete_flg=0",$intParentUserSeq,$intChildUserSeq);
		$boolReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($boolReturn);
	}
	public function updateSubMasterStatus($intStatus,$intParentUserSeq,$intSeq){
		$strQuery = sprintf("update sub_master set status=%d where parent_user_seq=%d and seq=%d",$intStatus,$intParentUserSeq,$intSeq);
		$boolReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($boolReturn);
	}
	public function deleteSubMaster($intParentUserSeq,$intSeq){
		$strQuery = sprintf("delete from sub_master where parent_user_seq=%d and seq=%d",$intParentUserSeq,$intSeq);
		$boolReturn = $this->resTeacherDB->DB_access($this->resTeacherDB,$strQuery);
		return($boolReturn);		
	}
}
?>