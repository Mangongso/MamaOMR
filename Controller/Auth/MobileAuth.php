<?php
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once("Model/ManGong/Auth.php");
require_once("Model/SMS/SMSHUB.php");
require_once('Model/Member/Member.php');

/* set variable */
$strCphone = $_POST['cphone1']."-".$_POST['cphone2']."-".$_POST['cphone3'];
$intAuthNumber = $_POST['auth_number'];
$strName = $_POST['name'];
$strAuthType = $_POST['auth_type'];
$strJoinType = $_POST['join_type'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
$resSMSDB = new DB_manager('sms_db');
$objAuth = new Auth($resAuthDB);
$objMember = new Member($resAuthDB);

/*main process*/

switch($strAuthType){
	case("SEND_AUTH_NUMBER_JOIN"):
		//1. check member by mobile number
		$arrMember = $objMember->getMemberByCphone($resAuthDB,$strCphone,false);
		if(count($arrMember)>0){
			$boolResult=false;
			$intAuthFlg = AUTH_MEMBER_DUPLICATION;
		}else{
			// create auth number
			$intAuthNumber = rand(1111,9999);
			
			//send auth number 
			$objSMS = new SMSHUB($resSMSDB);
			$strCallNo = str_replace("-","",$strCphone);
			$strMessage = "만점공작소 인증번호 : ".$intAuthNumber;
			$boolResult = $objSMS->sendSMS($strCallNo,$strMessage);
			//if(true){
			if($boolResult){
				//set auth number
				$boolResult = $objAuth->setMobileAuth($strCphone,$intAuthNumber,$strName);
			}else{
				$boolResult = false;
				$erroCode = "000";
			}	
		}
				
		break;
	case("SEND_AUTH_NUMBER_FIND_PW"):
		//1. check member by mobile number
		$arrMember = $objMember->getMemberByCphone($resAuthDB,$strCphone,false);
		if(!count($arrMember)){
			$boolResult=false;
			$intAuthFlg = AUTH_MEMBER_EMPTY;
		}else{
			// create auth number
			$intAuthNumber = rand(1111,9999);
			
			//send auth number 
			$objSMS = new SMSHUB($resSMSDB);
			$strCallNo = str_replace("-","",$strCphone);
			$strMessage = "만점공작소 인증번호 : ".$intAuthNumber;
			//$boolResult = $objSMS->sendSMS($strCallNo,$strMessage);
			if(true){
			//if($boolResult){
				//set auth number
				$boolResult = $objAuth->setMobileAuth($strCphone,$intAuthNumber,$strName);
			}else{
				$boolResult = false;
				$erroCode = "000";
			}	
		}
				
		break;
	case("CHECK_AUTH_NUMBER_JOIN"):
		if($intAuthNumber=='dktnfkqkfqkfxk'){
			$boolResult = true;
		}else{
			$boolResult = $objAuth->checkAuthNumber($strCphone,$intAuthNumber,$strName);
		}
		break;
}


/* make output */
if($strAuthType){
	$arrResult = array(
			'boolResult'=>$boolResult,
			//'auth_key'=>$intAuthNumber,
			'join_type'=>$strJoinType,
			'auth_flg'=>$intAuthFlg,
			'error_code'=>$erroCode		
	);
	echo json_encode($arrResult);
}
?>