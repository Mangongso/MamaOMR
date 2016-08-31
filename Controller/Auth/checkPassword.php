<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
//require_once('Model/ManGong/Auth.php');
require_once('Model/Member/Member.php');

/* set variable */ 
$strCphone = $_POST['cphone1']."-".$_POST['cphone2']."-".$_POST['cphone3'];
$strPassword = $_POST['password'];

/* create object */
$resAuthDB = new DB_manager('MAIN_SERVER');
//$objAuth = new Auth($resAuthDB);
$objMember = new Member($resAuthDB);

/* main process */
$arrMember = $objMember->getMemberByCphone($resAuthDB,$strCphone,false);
$intResult = $objMember->checkIsMember($resAuthDB,$arrMember[0]['member_seq'],$strPassword);
if($intResult){
	
	//update auth key 
	$strAuthKey = $objMember->updateAuthKey($resAuthDB,$arrMember[0]['member_seq']);
	//update address ip
	$arr_input = array('member_id'=>$arrMember[0]['member_seq'],'ip_address'=>$_SERVER['REMOTE_ADDR']);
	$objMember->updateMember($resAuthDB,$arr_input);
	
	//set auth key
	$strMemberToken = md5($arrMember[0]['email'].$arrMember[0]['cphone'].$_SERVER['REMOTE_ADDR'].time());
	setcookie("member_token", $strMemberToken, time()+60*60*24*30, '/');
	if($arrMember[0]['member_type']=="S" || ($arrMember[0]['member_type']=="T" && $arrMember[0]['confirm_flg']==0)){
		$_SESSION[$strMemberToken] = array(
				'auth_key'=>$strAuthKey,
				'member_seq'=>$arrMember[0]['member_seq'],
				'nickname'=>$arrMember[0]['nickname'],
				'name'=>$arrMember[0]['name'],
				'member_type'=>$arrMember[0]['member_type']
		);		
	}
	//set auto login
	if($intAutoLoginFlg){
		//set cookie 
		$strAutoLoginToken = md5($strAuthKey);
		setcookie(md5('autoLoginToken'), $strAutoLoginToken, time()+60*60*24*30, '/');
	}
	$boolResult=true;
}else{
	$boolResult=false;
	
}
/* make output */
$arrResult = array(
		'boolResult'=>$boolResult,
		'member_type'=>$arrMember[0]['member_type']
);
echo json_encode($arrResult);
?>