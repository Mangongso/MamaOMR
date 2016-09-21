<?
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/StudentMG.php');
require_once('Model/Member/Member.php');

$strManagerSeq = $_SESSION['smart_omr']['member_key'];

if(!$resMangongDB){
	$resMangongDB = new DB_manager('MAIN_SERVER');
}
if(!$objMember){
	$objMember = new Member($resMangongDB);
}
if(!$objStudentMG){
	$objStudentMG = new StudentMG($resMangongDB);
}

/* main process */
// get manager request member 
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
$arr_output['manager']['result'] = $boolManagerResult;
$arr_output['manager']['manager_msg'] = $manager_msg;
?>