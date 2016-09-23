<?
/**
 * @Controller 소셜 로그인 인증
 * @subpackage   	Core/DBmanager/DBmanager
 * @subpackage   	Member/Member
 */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/Member/Member.php');

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object 		$objMember 		: Member 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objMember = new Member($resMangongDB);


/**
 * 소셜 로그인 타입에 따른 Variable 세팅
 * ka : kakao , fa : facebook , na : naver
 * @var 	$strMemberID 소셜 유저 아이디
 * @var 	$strName 이름
 * @var 	$intJoinType 소셜 타입
 * @var 	$strEmail 이메일
 * */
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

/**
 * Main Process
 * 
 */
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
/**
 * SESSON에 로그인 정보를 담는다.
 * */
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

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 소셜 인증 결과 성공 여부
 */
$arrResult = array(
		'result'=>$boolResult
);
echo json_encode($arrResult);
?>