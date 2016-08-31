<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Auth.php');
require_once('Model/ManGong/Teacher.php');
require_once('Model/ManGong/Common.php');
require_once('Model/ManGong/Constant.php');

/* set variable */ 
$cphone = $_POST['cphone1']."-".$_POST['cphone2']."-".$_POST['cphone3'];
$intAuthNumber = $_POST['auth_number'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
$objAuth = new Auth($resAuthDB);
$objTeacher = new Teacher($resAuthDB);
$objCommon = new Common($resAuthDB);
$objConstant = new Constant($resAuthDB);

/* main process */
include(CONTROLLER_NAME."/Auth/checkAuth.php");
if($intAuthFlg==AUTH_TRUE){
	if($arrMember[0]['member_type']=="S"){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/student');
		exit;
	}else if($arrMember[0]['member_type']=="T"){
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/teacher');
		exit;
	}
}else{
	if($intAuthNumber=='dktnfkqkfqkfxk'){
		$boolResult = true;
	}else{
		$boolResult = $objAuth->checkAuthNumber($cphone,$intAuthNumber);
	}
	if($boolResult){
		//set mobile number and name  
		
		if($_POST['join_type']=="S"){
			//get teachers
			$arrAllTeacher = $objTeacher->getAllTeacher();
			foreach($arrAllTeacher as $intKey=>$arrTeacher){
				 $arrSubject = $objCommon->getMemberSubjectList($arrTeacher['member_seq']);
				 foreach($arrSubject as $intSubjectKey=>$arrSubjectResult){
				 	if($intSubjectKey==count($arrSubject)-1){
						$arrAllTeacher[$intKey]['subject_name'] .= $arrSubjectResult['subject_division'].$arrSubjectResult['subject_name'];
				 	}else{
				 		$arrAllTeacher[$intKey]['subject_name'] .= $arrSubjectResult['subject_division'].$arrSubjectResult['subject_name'].",";
				 	}
				 }
			}
		}
	}else{
		//mobile auth fail back to mobile auth page
		header("HTTP/1.1 301 Moved Permanently");
		header('location:/login');
		exit;
	}
}

//get subject
include(CONTROLLER_NAME."/Common/include/getSubject.php");

/* make output */
$arr_output['cphone'] = array($_POST['cphone1'],$_POST['cphone2'],$_POST['cphone3']);
$arr_output['name'] = $_POST['name'];
$arr_output['join_type'] = $_POST['join_type'];
$arr_output['teacher_list'] = $arrAllTeacher;
?>
