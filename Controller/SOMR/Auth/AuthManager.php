<?
/**
 * @Controller 학습매니져 인증
 * @subpackage   	Core/DBmanager/DBmanager
 * @package      	Mangong/StudentMG
 * @subpackage   	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/StudentMG.php');
require_once('Model/Member/Member.php');

/**
 * Variable 세팅
 * @var 	$strManagerSeq	 md5암호화된 매니져 시컨즈 
 */
$strManagerSeq = $_SESSION['smart_omr']['member_key'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			Member  				: Member 객체
 * @property	object 		StudentMG 					: StudentMG 객체
 */
if(!$resMangongDB){
	$resMangongDB = new DB_manager('MAIN_SERVER');
}
if(!$objMember){
	$objMember = new Member($resMangongDB);
}
if(!$objStudentMG){
	$objStudentMG = new StudentMG($resMangongDB);
}

 /**
 * Main Process
 */
$arrRequestMember = $objStudentMG->getManagerStudentByAuthKey($_GET['mat']);
$arrRequestMember = $objMember->getMemberByMemberSeq($arrRequestMember[0]['student_seq']);
if(count($arrRequestMember) && ( md5($arrRequestMember[0]['member_seq'])!=$strManagerSeq ) ){
	//get manager student list
	$arrManagerStudentList = $objStudentMG->getManagerStudentList($strManagerSeq,md5($arrRequestMember[0]['member_seq']));
	//$strManagerSeq
	if(!count($arrManagerStudentList)){
		//get manager info 
		$arrManagerInfo = $objMember->getMemberByMemberSeq($strManagerSeq);
		//update manager member seq	
		//$boolManagerResult = $objStudentMG->setManagerStudent($arrManagerInfo[0]['member_seq'],$arrRequestMember[0]['member_seq']);
		$boolManagerResult = $objStudentMG->updateManagerStudent($arrManagerInfo[0]['member_seq'],md5($arrRequestMember[0]['member_seq']),$_GET['mat']);
		if($boolManagerResult){
			$objStudentMG->deleteManagerStudentAuthKey(md5($arrRequestMember[0]['member_seq']),$_GET['mat']);
			$manager_msg = $arrRequestMember[0]['name'].'님의 매니저가 되었습니다. 마이페이지에서 '.$arrRequestMember[0]['name'].'님의 학습결과를 확인하실 수 있습니다.';
		}else{
			$manager_msg = '등록 중 오류가 발생하였습니다. 계속해서 오류가 발생하면 han@mangongso.com 으로 문의주세요.';
		}
	}else{
		$boolManagerResult = false;
		$manager_msg = '이미등록된 학생입니다.';
	}
}else if((md5($arrRequestMember[0]['member_seq'])!=$strManagerSeq)){
	$boolManagerResult = false;
	$manager_msg = '자기 자신은 매니저가 될수 없습니다.';
}else{
	$boolManagerResult = false;
	$manager_msg = '매니저 요청한 학생이 존재하지 않습니다.';
}

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	boolean 		$arr_output['manager']['result'] 			: 인증 결과 성공 여부
 * @property	string 			$arr_output['manager']['manager_msg'] : 결과 메세지
 */
$arr_output['manager']['result'] = $boolManagerResult;
$arr_output['manager']['manager_msg'] = $manager_msg;
?>