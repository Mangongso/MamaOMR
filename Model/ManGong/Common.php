<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Common{
	private $resCommonDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resCommonDB = $resProjectDB;
	}
	public function __destruct(){}
	
	private $arrITWTopic = array(
		"62076"=>"가상화"				,
		"61023"=>"개발자"				,
		"62073"=>"BYOD"				,
		"62078"=>"네트워크"			,
		"35"	=>"데이터센터"		,
		"55815"=>"디지털디바이스"		,
		"61022"=>"디지털 마케팅"		,
		"62072"=>"디지털이미지"		,
		"37"	=>"모바일"			,
		"65212"	=>"미래기술"			,
		"36"	=>"보안"				,
		"54652"=>"브라우저"			,
		"62077"=>"VDI"				,
		"65210"=>"BI/분석"			,
		"54649"=>"빅데이터"			,
		"63417"=>"사물인터넷"			,
		"62080"=>"서버"				,	
		"38"	=>"소셜미디어"		,
		"55816"=>"스마트TV"			,
		"62075"=>"스마트폰"			,
		"62084"=>"스토리지"			,
		"65209"=>"3D프린팅"			,
		"62081"=>"CIO"				,
		"62082"=>"IT관리"			,
		"54647"=>"iOS"				,
		"62079"=>"안드로이드"			,
		"39"	=>"애플리케이션"		,
		"62086"=>"오픈소스"			,
		"40"	=>"오피스＆협업"		,
		"62071"=>"웨어러블컴퓨팅"		,
		"62085"=>"웹서비스"			,
		"54650"=>"윈도우"				,
		"63355"=>"UX"				,
		"62083"=>"컨슈머라이제이션"	,
		"34"	=>"클라우드"			,
		"54653"=>"클라우드오피스"		,
		"54651"=>"태블릿"				,
		"54648"=>"특허전쟁"			,
		"62074"=>"퍼스널컴퓨팅"		,
		"65211"=>"프라이버시"			
	);
	
	public function setTeacherStudentList($intTeacherSeq,$intStudentSeq){
		$strQuery = sprintf("insert into teacher_student_list set teacher_seq=%d,student_seq=%d",$intTeacherSeq,$intStudentSeq);
		$arrResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherSeqByStudentSeq($intStudentSeq,$intTeacherSeq=null){
		$strQuery = sprintf("SELECT t.teacher_seq,t.approve_flg,m.name 
							FROM (SELECT DISTINCT(teacher_seq),approve_flg FROM teacher_student_list WHERE student_seq=%d AND delete_flg=0) t,
								member_basic_info m 
							WHERE t.teacher_seq = m.member_seq AND del_flg='0' ",$intStudentSeq);
		if($intTeacherSeq){
			$strQuery .= sprintf(" and t.teacher_seq=%d ",$intTeacherSeq);
		}
		$arrResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($arrResult);		
	}
	public function getCategory($intCategoryType,$mixMemberSeq){
		if(is_array($mixMemberSeq)){
			if(count($mixMemberSeq)){
				$strQuery = sprintf("select * from category where category_type=%d and writer_seq in (".join(',',$mixMemberSeq).") and delete_flg=0",$intCategoryType);
			}else{
				return array();
			}
		}else{
			$strQuery = sprintf("select * from category where category_type=%d and writer_seq=%d and delete_flg=0",$intCategoryType,$mixMemberSeq);
		}
		$arrResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($arrResult);		
	}
	public function getTerms($intMemberSeq,$strTermType){
		$arrResult = array();
		
		switch($strTermType){
			case('topic'):
				$intVid = 9;
				$arrResult = $this->arrITWTopic;
			break;
		}
		
		$strQuery = sprintf("select * from term_data where writer_seq=%d and vid=%d ",$intMemberSeq,$intVid);
		$arrTermsResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		
		foreach($arrTermsResult as $intKey=>$arrTerms){
			$arrResult[$arrTerms['tid']]=$arrTerms['name'];
		}
		
		return($arrResult);
	}
	public function setTerm($intMemberSeq,$strTermType,$strName,&$intTid=null){
		switch($strTermType){
			case('topic'):
				$intVid = 9;
			break;
		}
		if(!is_null($intTid) && trim($intTid)!=''){
			$strQuery = sprintf("update term_data set name='%s' where tid=%d and writer_seq=%d",$strName,$intTid,$intMemberSeq);
			$boolResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		}else{
			$strQuery = sprintf("insert into term_data set writer_seq=%d,vid=%d,name='%s'",$intMemberSeq,$intVid,$strName);
			$boolResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
			$intTid = mysql_insert_id($this->resCommonDB->res_DB);
		}
		return($boolResult);		
	}
	/* 삭제시 term_test 데이터도 삭제가되어야 하는지 확인이 필요 */
	public function deleteTerm($intMemberSeq,$intTid){
		$strQuery = sprintf("delete from term_data where tid=%d and writer_seq=%d",$intTid,$intMemberSeq);
		$boolResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($boolResult);		
	}
	public function getTermTests($intTermType,$intTestsSeq){
		
	}
	public function setTermTests($intMemberSeq,$intTid,$intTestsSeq){
		switch($strTermType){
			case('topic'):
				$intVid = 9;
			break;
		}
		//check is my term add 
		/*
		 * $arrTerm = $this->getTerm($intMemberSeq, $intTid);
		 * */
		if(count($arrTerms) || true){
			$strQuery = sprintf("insert into term_test(tid,test_seq) values(%d,%d) on duplicate key update tid=%d,test_seq=%d",$intTid,$intTestsSeq,$intTid,$intTestsSeq);
			$boolResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
			//$intTid = mysql_insert_id($this->resCommonDB->res_DB);
		}
		return($boolResult);		
	}
	
	public function getMemberSubjectList($intMemberSeq,$intSubjectCode=null){
		$strQuery = sprintf("select * from member_subject_list where member_seq=%d ",$intMemberSeq);
		if($intSubjectCode){
			$strQuery = sprintf(" and subject_code=%d ",$intSubjectCode);
		}
		$arrResult = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($arrResult);		
	}
	public function setMemberSubjectList($intMemberSeq,$intSubjectCode,$strSubjectDivision,$strSubjectName){
		$strQuery = sprintf("insert into member_subject_list set member_seq=%d,subject_code=%d,subject_division='%s',subject_name='%s'",$intMemberSeq,$intSubjectCode,$strSubjectDivision,$strSubjectName);
		$boolReturn = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($boolReturn);		
	}
	public function deleteMemberSubjectList($intMemberSeq,$intSubjectCode=null){
		$strQuery = sprintf("delete from member_subject_list where member_seq=%d ",$intMemberSeq);
		if($intSubjectCode){
			$strQuery = sprintf(" and subject_code=%d ",$intSubjectCode);
		}
		$boolReturn = $this->resCommonDB->DB_access($this->resCommonDB,$strQuery);
		return($boolReturn);		
	}
}
?>