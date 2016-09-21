<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");
require_once("Model/Member/Member.php");

class Student extends Member{
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resMemberDB = $resProjectDB;
	}
	public function __destruct(){}
	public function getAllStudent($arrSearch=array(),&$arrPaging,$strDelFlg=null){
		include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
		if(!is_null($arrPaging)){
			$intTotalCount = $this->getAllStudentCnt($arrSearch);
			$arrPaging = $this->objPaging->getPaging(
					$intTotalCount,
					$arrPaging['page']?$arrPaging['page']:1,
					$arrPaging['result_number'],
					$arrPaging['block_number'],
					$arrPaging['param']
			);
		}
		$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='S' ";
		if(is_null($strDelFlg)){
			$strQuery .= " and A.del_flg='0' ";
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
		//print_r($strQuery);
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
	public function getAllStudentCnt($arrSearch=array()){
		include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
		$strQuery = "select count(*) cnt from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='S' and A.del_flg='0' ";
		if(count($arrWhereQuery)){
			$strQuery .= " and ".join(" and ",$arrWhereQuery);
		}
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return $arrResult[0]['cnt'];
	}
	public function getMemberByStudentKey($intMemberSeq,$strStudentKey){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq=%d and auth_key='%s'",$intMemberSeq,$strStudentKey);
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
	public function checkStudentNumber($cphone,$intStudentNumber){
		$strQuery = sprintf("select * from mobile_auth where cphone='%s' and auth_key=md5(%d)",$cphone,$intStudentNumber);
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		if(count($arrResult)>0){
			$boolResult=true;
		}else{
			$boolResult=false;
		}
		return($boolResult);		
	}
	public function setStudentNumber($cphone,$intStudentNumber){
		$strQuery = sprintf("insert into mobile_auth set cphone='%s',auth_key=md5(%d)",$cphone,$intStudentNumber);
		$boolReturn = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		$intStudentSeq = mysql_insert_id($this->resMemberDB->res_DB);
		return($boolReturn);		
	}
	public function updateStudentExtendInfo($strAcademy,$strSchool,$intMemberSeq,$intBirthYear=null,$intBirthMonth=null,$intBirthDay=null){
		$strQuery = sprintf("update member_extend_info set academy='%s',school='%s' ",$strAcademy,$strSchool);
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
		$boolReturn = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($boolReturn);		
	}
	public function getPenddingStudent($intTeacherSeq=null,$arrSearch=array()){
		$strQuery = "SELECT * FROM 
						teacher_student_list A,
						member_basic_info B,
						member_extend_info C 
					WHERE A.student_seq = B.member_seq
					AND B.member_seq = C.member_seq
					AND delete_flg=0 AND approve_flg=0 ";
		if($intTeacherSeq){
			$strQuery .= sprintf(" and A.teacher_seq=%d ",$intTeacherSeq);
		}
		if($arrSearch){
			foreach($arrSearch as $strKey=>$strValue){
				switch(name){
					case('name'):
						$strQuery .= " and B.".$strKey." like '%".$strValue."%' ";
					break;
				}
			}
		}
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
}
?>