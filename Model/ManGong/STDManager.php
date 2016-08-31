<?
require_once("Model/Core/Util/Paging.php");

class STDManager{
	public function __construct($resSMDB=null){
		$this->objPaging =  new Paging();
		$this->resSMDB = $resSMDB;
	}
	public function __destruct(){}
	public function getQuickTestQuestionByAnswer($strTesterToken,$strQuestionSeqMD5,$strAnswer){
		if($strQuestionSeqMD5 && $strAnswer){
			$strQuery = sprintf("select * from stdm_quick_test where md5(seq)='%s' and delete_flg=0",$strQuestionSeqMD5);
			$arrResultThisQuestion = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
			if($strAnswer=="Y"){
				$intNextQuestionSeq = $arrResultThisQuestion[0]['yes_goto_seq'];
			}else{
				$intNextQuestionSeq = $arrResultThisQuestion[0]['no_goto_seq'];
			}
			$strQuery = sprintf("select * from stdm_quick_test where seq=%d",$intNextQuestionSeq);
			$arrResultNextQuestion = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
			if($arrResultNextQuestion[0]['question_type']==2){
				if(!$_SESSION['STDM_QT_TYPE']){
					$_SESSION['STDM_QT_TYPE'] = array();
				}
				array_push($_SESSION['STDM_QT_TYPE'],$arrResultNextQuestion[0]['result']);
				$intNextQuestionSeq = $arrResultNextQuestion[0]['yes_goto_seq'];
			}
			$strQuery = sprintf("select * from stdm_quick_test where seq=%d",$intNextQuestionSeq);
			$arrResultNextQuestion = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		}else{
			unset($_SESSION['STDM_TESTER_TOKEN']);
			unset($_SESSION['STDM_QT_TYPE']);
			$_SESSION['STDM_TESTER_TOKEN'] = md5(uniqid());
			$strQuery = sprintf("select * from stdm_quick_test where question_no=%d",1);
			$arrResultNextQuestion = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		}
		if(!$arrResultNextQuestion){
			$mixResult = $this->setQuickTestResult($strTesterToken,join("",$_SESSION['STDM_QT_TYPE']));
		}else{
			$mixResult = $arrResultNextQuestion;
		}
		return($mixResult);
	}
	public function getQuickTestLastQuestionCount($intQuestionNo){
		$strQuery = sprintf("select count(*) as cnt from stdm_quick_test where question_no>%d and delete_flg=0",$intQuestionNo);
		$arrResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($arrResult[0]['cnt']);
	}
	public function setQuickTestResult($strTesterToken,$strResult){
		$strQuery = sprintf("insert into stdm_quick_test_result set test_date=now(),tester_token='%s',test_result='%s',referer='%s',from_ip='%s',delete_flg=0",$strTesterToken,$strResult,$_SESSION['STDM_REFERER'],$_SERVER['REMOTE_ADDR']);
		$boolResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($boolResult);
	}
	public function setTesterProfile($strTesterToken,$strName,$strCPhone,$strSchool,$strGrade,$strTesterType){
		$strQuery = sprintf("update stdm_quick_test_result set
				tester_name='%s',
				from_cphone='%s',
				tester_type=%d,
				school='%s',
				grade='%s'
				where tester_token='%s'
				",$strName,$strCPhone,$strTesterType,$strSchool,$strGrade,$strTesterToken);
		$boolResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($boolResult);
	}
	public function getQuickTestResult($strTesterToken){
		$strQuery = sprintf("select * from stdm_quick_test_result where tester_token='%s'",$strTesterToken);
		$arrResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($arrResult);
	}
	public function getQuickTestDesc($strResult){
		$strQuery = sprintf("select * from stdm_quick_test_result_desc where result='%s'",$strResult);
		$arrResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($arrResult);
	}
	public function getLastTesterList($intLastTesterSeq,$intListLimit=10){
		if(!$intLastTesterSeq){
			$strQuery = sprintf("select * from stdm_quick_test_result order by test_date DESC limit 0,%d",$intListLimit);
		}else{
			$strQuery = sprintf("select * from stdm_quick_test_result where seq>%d order by test_date DESC",$intLastTesterSeq);
		}
		$arrResult = $this->resSMDB->DB_access($this->resSMDB,$strQuery);
		return($arrResult);
	}
}
?>