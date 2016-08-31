<?
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/Member/Member.php');

//set variable check
/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objMember = new Member($resMangongDB);

/* main process */
switch($_REQUEST['social_login_type']){
	case('ka'):
		$strMemberID = 'ka'.$_REQUEST['auth_data']['id'];
		$strName = $_REQUEST['auth_data']['properties']['nickname'];
		$intJoinType = JOIN_KAKAO;
		break;
	case('fa'):
		$strMemberID = 'fa'.$_REQUEST['fa_id'];
		$strName = $_REQUEST['fa_name'];
		$strEmail = $_REQUEST['fa_email'];
		$intJoinType = JOIN_FACEBOOK;
		break;
	case('tw'):
		$strMemberID = 'tw'.$_REQUEST['auth_data']['id'];
		break;
	case('na'):
		/* access token get user info */
		$strAccessToken = $_REQUEST['access_token'];
		$url = "https://openapi.naver.com/v1/nid/me";
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		 $headers = array();
		 $headers[] = "User-Agent: curl/7.43.0";
		 $headers[] = "Accept: */*";
		 $headers[] = "Content-Type: application/xml";
		 $headers[] = "Authorization: Bearer ".$strAccessToken;
		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 $xmlstr = curl_exec ($ch);
		 curl_close ($ch);
		 
		$xmlstr = utf8_encode($xmlstr);
		$xmlobj = json_decode($xmlstr,true);
		
		$strMemberID = 'na'.$xmlobj['response']['id'];
		$strName = $xmlobj['response']['name'];
		$strEmail = $xmlobj['response']['email'];
		$intJoinType = JOIN_NAVER;
		break;
}

$arrMember = $objMember->getMemberByMemberID($strMemberID);
if(count($arrMember)){
	$boolResult = true;
}else{
	//set user 
	$arr_input = array(
		'member_id'=>$strMemberID,
		'name'=>$strName,
		'nickname'=>$strName,
		'email'=>$strEmail,
		'member_type'=>"S",
		'join_type'=>$intJoinType
	);
	$boolResult = $objMember->setMember($resMangongDB,$arr_input,$intMemberSeq);
}
if($boolResult){
	//login
	$_SESSION['smart_omr'] = array(
		'member_key'=>md5($arrMember[0]['member_seq']),
		'nickname'=>$arrMember[0]['nickname'],
		'name'=>$arrMember[0]['name'],
		'member_type'=>$arrMember[0]['member_type'],
		'kakao_access_token'=>$_REQUEST['access_token']
	);
}

/* make output */
$arrResult = array(
		'result'=>$boolResult
);
echo json_encode($arrResult);
?>