<?
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Profile{
	private $resProfileDB = null;
	private $objPaging = null;
	public function __construct($resProjectDB=null){
		$this->objPaging =  new Paging();
		$this->resProfileDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getTeacherProfileInfo($intTeacherSeq){
		$strQuery = sprintf("select * from teacher_profile where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
		$arrResult = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($arrResult);		
	}
	public function getStudentProfileInfo($intMemberSeq){ 
		$strQuery = sprintf("select * from student_profile where student_seq=%d and delete_flg=0",$intMemberSeq);
		$arrResult = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($arrResult);		
	}
	public function getTeacherProfileInfoByDomain($strDomain){
		$strQuery = sprintf("select * from teacher_profile where domain like '%%%s%%' and delete_flg=0",trim($strDomain));
		$arrResult = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($arrResult);
	}	
	public function setTeacherProfile($intTeacherSeq,$strTeacherName,$strTeacherBriefHistory=null,$strProfileImg=null,$intOpenFlg=0,$strDomain = null,$strColumnName='profile_img',$strDesc=null,$strButtonTitle=null){
		$strQuery = sprintf("insert into teacher_profile set teacher_seq=%d,teacher_name='%s',teacher_brief_history='%s',open_flg=%d,domain='%s',%s='%s',description='%s',button_title='%s'",$intTeacherSeq,$strTeacherName,quote_smart($strTeacherBriefHistory),$intOpenFlg,$strDomain,$strColumnName?$strColumnName:'profile_img',$strProfileImg,$strDesc,$strButtonTitle);
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function setStudentProfile($intMemberSeq,$strMemberName,$strImg=null,$strColumnName='profile_img'){
		$strQuery = sprintf("insert into student_profile set student_seq=%d,student_name='%s',%s='%s'",$intMemberSeq,$strMemberName,$strColumnName,$strImg);
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function updateProfileImg($intMemberSeq,$strMemberType,$strProfileImg,$strColumnName){
		switch($strMemberType){
			case('T'):
				$strQuery = sprintf("update teacher_profile set %s='%s' where teacher_seq=%d and delete_flg=0",$strColumnName,$strProfileImg,$intMemberSeq);
				break;
			case('S'):
				$strQuery = sprintf("update student_profile set %s='%s' where student_seq=%d and delete_flg=0",$strColumnName,$strProfileImg,$intMemberSeq);
				break;
		}
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function updateTeacherBriefHistory($intTeacherSeq,$strTeacherBriefHistoryContent){
		$strQuery = sprintf("update teacher_profile set teacher_brief_history='%s' where teacher_seq=%d and delete_flg=0",quote_smart($strTeacherBriefHistoryContent),$intTeacherSeq);
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function updateTeacherDomain($intTeacherSeq,$strDomain){
		$strQuery = sprintf("update teacher_profile set domain='%s' where teacher_seq=%d and delete_flg=0",str_replace("http://","",trim($strDomain)),$intTeacherSeq);
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);
	}	
	public function setVisitorCount($intTeacherSeq,$strVisitorCookieValue){
		$arrTeacherInfo = $this->getTeacherProfileInfo($intTeacherSeq);
		//check today visitor count
		if(md5(date('Y-m-d',strtotime($arrTeacherInfo[0]['today'])))==$strVisitorCookieValue){
			//update two field today_count=today_count+1,total_count
			$strQuery = sprintf("update teacher_profile set today_visit_count=today_visit_count+1,total_visit_count=total_visit_count+1 where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
		}else{
			//update three field today_count=1,total_count,today
			$strQuery = sprintf("update teacher_profile set today_visit_count=1,total_visit_count=total_visit_count+1,today=now() where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
		}
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function updateTeacherProfileOpenFlg($intTeacherSeq,$intOpenFlg){
		if($intOpenFlg){
			$strQuery = sprintf("update teacher_profile set open_flg=1 where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
		}else{
			$strQuery = sprintf("update teacher_profile set open_flg=0 where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
		}
		$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		return($boolReturn);		
	}
	public function updateTeacherProfileInfo($intTeacherSeq,$strDesc=null,$strButtonTitle=null){
		if(!is_null($strDesc) && !is_null($strButtonTitle)){
			$strQuery = sprintf("update teacher_profile set description='%s',title='%s' where teacher_seq=%d and delete_flg=0",quote_smart($strDesc),quote_smart($strButtonTitle),$intTeacherSeq);
		}else if(!is_null($strDesc)){
			$strQuery = sprintf("update teacher_profile set description='%s' where teacher_seq=%d and delete_flg=0",quote_smart($strDesc),$intTeacherSeq);
		}else if(!is_null($strButtonTitle)){
			$strQuery = sprintf("update teacher_profile set button_title='%s' where teacher_seq=%d and delete_flg=0",quote_smart($strButtonTitle),$intTeacherSeq);
		}else{
			$boolReturn = false;
		}
		//print_r($strQuery);
		//exit;
		if($strQuery){
			$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		}
		return($boolReturn);		
	}
	public function updateTeacherProfileViewFlg($intTeacherSeq,$intReportViewFlg,$intTestViewFlg,$intHelpViewFlg,$intNoticeViewFlg){
		$arrWhere = array();
		if(!is_null($intReportViewFlg)){
			array_push($arrWhere, sprintf("view_report_flg=%d",$intReportViewFlg));
		}
		if(!is_null($intTestViewFlg)){
			array_push($arrWhere, sprintf("view_test_flg=%d",$intTestViewFlg));
		}
		if(!is_null($intHelpViewFlg)){
			array_push($arrWhere, sprintf("view_help_flg=%d",$intHelpViewFlg));
		}
		if(!is_null($intNoticeViewFlg)){
			array_push($arrWhere, sprintf("view_notice_flg=%d",$intNoticeViewFlg));
		}
		if(count($arrWhere)){
			$strQuery = sprintf("update teacher_profile set ".join(',', $arrWhere)." where teacher_seq=%d and delete_flg=0",$intTeacherSeq);
			$boolReturn = $this->resProfileDB->DB_access($this->resProfileDB,$strQuery);
		}else{
			$boolReturn = false;
		}
		return($boolReturn);		
	}
}
?>