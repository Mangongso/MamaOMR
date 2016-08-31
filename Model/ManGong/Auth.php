<?
require_once("Model/Core/DataManager/DataHandler.php");

class Auth{
	private $resAuthDB = null;
	public function __construct($resProjectDB=null){
		$this->resAuthDB = $resProjectDB;
	}
	public function __destruct(){}
	
	public function getMemberByAuthKey($intMemberSeq,$strAuthKey){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.member_seq=%d and auth_key='%s'",$intMemberSeq,$strAuthKey);
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($arrResult);
	}
	public function checkAuthNumber($cphone,$intAuthNumber){
		$strQuery = sprintf("select * from mobile_auth where cphone='%s' and auth_key=md5(%d) ",$cphone,$intAuthNumber);
		//add limit time 1 minute
		$strQuery .= " and create_date > DATE_FORMAT((NOW() -INTERVAL 1 MINUTE),'%Y-%m-%d %H:%i:%s') "; 
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		if(count($arrResult)>0){
			$boolResult=true;
		}else{
			$boolResult=false;
		}
		return($boolResult);		
	}
	public function setMobileAuth($cphone,$intAuthNumber,$strName){
		$strQuery = sprintf("insert into mobile_auth set cphone='%s',auth_key=md5(%d),name='%s',create_date=now()",$cphone,$intAuthNumber,$strName);
		$boolReturn = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		$intAuthSeq = mysql_insert_id($this->resAuthDB->res_DB);
		return($boolReturn);		
	}
	public function getMemberByIPAddress($strIPAddress,$strAuthKey,$authKeyType='md5'){
		switch($authKeyType){
			case('password'):
				$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.ip_address='%s' and A.auth_key=password('%s')",$strIPAddress,$strAuthKey);
			break;
			default:
				$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where A.ip_address='%s' and md5(A.auth_key)='%s'",$strIPAddress,$strAuthKey);
			break;
		}
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($arrResult);		
	}
	public function setMemberPolicy($intMemberSeq,$strMemberName,$strMemberType,$serializePolicyInfo,$intRevision=1){
		$arrPolicyInfo = unserialize($serializePolicyInfo);
		if($arrPolicyInfo['period']){
			$expirationDate = date("Y-m-d 23:59:59",strtotime(($arrPolicyInfo['period']+1)." day", time()));
		}else{
			$start = new DateTime(date('Y-m-d'));
			$end = new DateTime(date('Y-m-d',strtotime("1 month", time())));
			$days = date_diff($start, $end);
			
			$expirationDate = date("Y-m-d 23:59:59",strtotime("1 month", time()));
			$arrPolicyInfo['period'] = $days->days;
		}
		
		$strQuery = sprintf("insert into member_grade_policy set member_seq=%d,member_name='%s',member_type='%s',member_grade=%d,start_date=now(),expiration_date='%s',period=%d,max_student_count=%d,max_upload_size=%d,revision=%d",
																$intMemberSeq,$strMemberName,$strMemberType,$arrPolicyInfo['member_grade'],$expirationDate,$arrPolicyInfo['period'],$arrPolicyInfo['max_student_count'],$arrPolicyInfo['max_upload_size'],$intRevision);
		$boolResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($boolResult);		
	}
	public function updateMemberPolicy($intMemberSeq,$arrUpdateValue=array()){
		$strQuery = "update member_grade_policy set ";
		
		$keys = array_keys($arrUpdateValue);
		foreach(array_keys($keys) AS $k ){
			
		    $this_value = $arrUpdateValue[$keys[$k]];
		    $nextval = $arrUpdateValue[$keys[$k+1]];
		    if(!$nextval){
		       	$strQuery .= sprintf(" %s='%s' ",$keys[$k],$this_value);
		    }else{
		    	$strQuery .= sprintf(" %s='%s', ",$keys[$k],$this_value);
		    }
		}
		$strQuery .= sprintf(" where member_seq=%d ",$intMemberSeq);
		$boolResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($boolResult);		
	}
	public function checkMemberPeriod($intMemberSeq){
		//get member grade policy
		$arrMemberGradePolicy = $this->getMemberGradePolicy($intMemberSeq);
		
		//check member period
		if(date("Y-m-d",strtotime($arrMemberGradePolicy[0]['expiration_date'])) < date("Y-m-d")){
			return PERIOD_OVER;
		}else{
			return MEMBER_POLICY_COMPLETE;
		}
	}
	public function checkMemberFileUploadSizePolicy($intMemberSeq,$files){
		//get current file size
		$arrMemberGradePolicy = $this->getMemberGradePolicy($intMemberSeq);
		$currentFileSize = $this->getCurrentTotalFileSize($intMemberSeq);
		//upload file size
		$uploadFileSize = 0;
		foreach($files as $intKey=>$arrFile){
			$uploadFileSize = $uploadFileSize + $arrFile['size'][0];
		}
		$totalFileSize = (int)$currentFileSize + (int)$uploadFileSize;
		if($totalFileSize > $arrMemberGradePolicy[0]['max_upload_size']){
			return array('result_code'=>MAX_UPLOAD_FILE_SIZE_OVER,'current_file_size'=>$currentFileSize,'max_file_size'=>$arrMemberGradePolicy[0]['max_upload_size']);
		}		
		return array('result_code'=>MEMBER_POLICY_COMPLETE);
	}
	public function checkMemberStudentCountPolicy($intMemberSeq,$intCurrentStudentCount,$intUpdateStudentCount){
		//get member grade policy
		$arrMemberGradePolicy = $this->getMemberGradePolicy($intMemberSeq);
		$totalStudentCount = $intCurrentStudentCount + $intUpdateStudentCount;
		if($totalStudentCount > $arrMemberGradePolicy[0]['max_student_count']){
			return array('result_code'=>MAX_STUDENT_COUNT_OVER,'current_student_count'=>$intCurrentStudentCount,'max_student_count'=>$arrMemberGradePolicy[0]['max_student_count']);
		}
		return array('result_code'=>MEMBER_POLICY_COMPLETE);
	}
	public function getMemberGradePolicy($intMemberSeq){
		$strQuery = "select seq,member_seq,member_name,member_type,member_grade,start_date,expiration_date,period,max_student_count,max_upload_size,revision,IF(DATE_FORMAT(expiration_date,'%Y %m %d')<DATE_FORMAT(NOW(),'%Y %m %d'),0,1) member_status from member_grade_policy where member_seq=".$intMemberSeq." order by revision desc";
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($arrResult);		
	}
	public function getCurrentTotalFileSize($intMemberSeq){
		$strQuery = sprintf("SELECT SUM(a.file_size) as total_file_size FROM (
							SELECT member_seq,file_size FROM report_upload_file WHERE member_seq=%d
							UNION ALL
							SELECT member_seq,file_size FROM test_upload_file WHERE member_seq=%d) a",$intMemberSeq,$intMemberSeq);
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($arrResult[0]['total_file_size']);		
	}
	/* smart_omr auth 
	public function getMemberBySOMRManagerAuthKey($strAuthKey){
		$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where MD5(CONCAT(TRIM(LEADING '0' FROM A.member_seq),A.member_id))='%s'",$strAuthKey);
		$arrResult = $this->resAuthDB->DB_access($this->resAuthDB,$strQuery);
		return($arrResult);
	}
	*/
}
?>