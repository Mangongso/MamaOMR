<?php
/*
 * Created on 2006. 11. 22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once("Model/Core/Util/Paging.php");
require_once("Model/Core/DataManager/DataHandler.php");

class Member{
	public $MebmerInformation;
	private $objPaging;
	public $MemberList;
	public $arrMemberPaging;
	public $resMemberDB;
	function __construct($resMemberDB=null){
		define("JOIN_HANBNC", 1);
		define("JOIN_FACEBOOK", 2);
		define("JOIN_GOOGLE", 3);
		define("JOIN_NAVER", 4);
		define("JOIN_KAKAO", 5);
		define("JOIN_TWITTER", 6);
		
		
		define("JOIN_ERROR_DUPLICATE_ID", 1);
		define("JOIN_ERROR_DUPLICATE_NICKNAME", 2);
		define("JOIN_ERROR_DUPLICATE_EMAIL", 3);
		
		if(!is_null($resMemberDB)){
			$this->resMemberDB = $resMemberDB;
		}
		$this->objPaging = new Paging();
	}
	function __destruct(){}	
	
	public function updateAuthKey($resMemberDB,$intMemberSeq){
		$strKey = md5(uniqid());
		include("Model/Member/SQL/MySQL/updateAuthKey.php");
		$resMemberDB->DB_access($resMemberDB,$strQuery);
		return($strKey);
	}
	public function checkEmailAuth($resMemberDB,$intMemberSeq,$strAuthKey){
		include("Model/Member/SQL/MySQL/checkEmailAuth.php");
		$arrCnt = $resMemberDB->DB_access($resMemberDB,$strQuery);
		if($arrCnt[0]['cnt']==1){
			include("Model/Member/SQL/MySQL/updateEmailAuthFlg.php");
			$resMemberDB->DB_access($resMemberDB,$strQuery);
			$boolReturn = true;
		}else{
			$boolReturn = false;
		}
		return($boolReturn);		
	}
	function getMemberCount($res_DB,$arr_input=null,$arrSearch=array()){
		include("Model/Member/SQL/MySQL/getMemberCount.php");
		$arr_sponsor_count = $res_DB->DB_access($res_DB,$query);
		return($arr_sponsor_count[0]['count']);			
	}
	function getMemberTotalCount($res_DB){
		$query = sprintf("select count(*) count from member_basic_info where del_flg='0'");
		$arrResult = $res_DB->DB_access($res_DB,$query);
		return($arrResult[0]['count']);			
	}
	function getMemberList($res_DB,$arr_input=null,$arrSearch=array()){
		if(!empty($arr_input[paging])){
			$int_total_count = $this->getMemberCount($res_DB,$arr_input,$arrSearch);
			$arr_input[paging] = $this->arrMemberPaging = 
			$this->objPaging->getPaging(
				$int_total_count,
				$arr_input[paging][page]?$arr_input[paging][page]:1,
				$arr_input[paging][result_number],
				$arr_input[paging][block_number],
				$arr_input[paging][param]
			);	
		}
		include("Model/Member/SQL/MySQL/getMemberList.php");
		$arr_member = $res_DB->DB_access($res_DB,$query);
		return($arr_member);				
	}
	function getMemberJoinType($res_DB,$intMemberSeq){
		include("Model/Member/SQL/MySQL/getMemberJoinType.php");
		$arrMemberType = $res_DB->DB_access($res_DB,$strQuery);
		return($arrMemberType[0]['opensocial_flg']);
	}
	function setMember($res_DB=null,$arr_input=null,&$intMemberSeq=null){
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/MemberRegisterBasic.php");
		}
		if(!$res_DB->DB_access($res_DB,$this->quary["insert_member_basic_info"])){
			$this->result = false;
		}
		$arr_input['member_seq'] = mysql_insert_id($res_DB->res_DB);
		$intMemberSeq = mysql_insert_id();
		include("Model/Member/SQL/MySQL/MemberRegisterExtend.php");
		if($res_DB->DB_access($res_DB,$this->quary["insert_member_extend_info"])){
			$this->result = $arr_input['member_seq'];
		}else{
			$this->result = false;
		}
		return($this->result);
	}
	public function getMemberByMemberID($strMemberID){
		include("Model/Member/SQL/MySQL/getMemberByMemberID.php");
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
	public function getMemberByMemberSeq($mixMemberSeq){
		include("Model/Member/SQL/MySQL/getMemberByMemberSeq.php");
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		return($arrResult);
	}
	public function getMemberByCphone($res_DB,$strCphone,$boolMD5=true){
		$strCphone = str_replace("-","",$strCphone);
		if($boolMD5){
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where md5(REPLACE(cphone,'-',''))='%s'",$strCphone);
		}else{
			$strQuery = sprintf("select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where REPLACE(cphone,'-','')='%s'",quote_smart($strCphone));
		}
		$arrResult = $res_DB->DB_access($res_DB,$strQuery);
		return($arrResult);
	}
	public function checkConfirm($strMemberID){
		include("Model/Member/SQL/MySQL/checkConfirm.php");
		$arrResult = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
		if($arrResult[0]['cnt']>0){
			$boolResult = true;
		}else{
			$boolResult = false;
		}
		return($boolResult);		
	}
	function getMember($res_DB,$arr_input=array("column"=>array("*")),$strOrder = null,$strArrayKey = null){
		if(!trim((string)$arr_input[column])){
			$arr_input[column] = array("*");
		}
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/getMemberInfo.php");
		}

		if($strArrayKey){
			$arr_temp = $res_DB->DB_access($res_DB,$this->query["select_member_info"],$strArrayKey);
		}else{
			$arr_temp = $res_DB->DB_access($res_DB,$this->query["select_member_info"]);
		}
	
		$arr_email = array();
		$arr_tel = array();
		$arr_cphone =  array();
		
		foreach($arr_temp as $key=>$value){
			unset($arr_email);
			unset($arr_tel);
			unset($arr_cphone);
			$arr_email = explode("@",$value[email]);
			$arr_tel = explode("-",$value[tel]);
			$arr_cphone = explode("-",$value[cphone]);
			$arr_temp[$key][email1] = $arr_email[0];
			$arr_temp[$key][email2] = $arr_email[1];
			$arr_temp[$key][tel1] = $arr_tel[0];
			$arr_temp[$key][tel2] = $arr_tel[1];
			$arr_temp[$key][tel3] = $arr_tel[2];
			$arr_temp[$key][cphone1] = $arr_cphone[0];
			$arr_temp[$key][cphone2] = $arr_cphone[1];
			$arr_temp[$key][cphone3] = $arr_cphone[2];
		}
		$this->result = $arr_temp;
		return($this->result);
	}
	function updateMember($res_DB,$arr_input = null,$strSeqColumn = 'member_seq'){
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/UpdateMemberInfo.php");
		}
		$this->quary["update_member_basic_info"];
		if(trim($this->quary["update_member_basic_info"])){
			if(!$res_DB->DB_access($res_DB,$this->quary["update_member_basic_info"])){
				$this->result = false;
			}else{
				$this->result = true;
			}
		}
		if(trim($this->quary["update_member_extend_info"])){
			if(!$res_DB->DB_access($res_DB,$this->quary["update_member_extend_info"])){
				$this->result = false;
			}else{
				$this->result = true;
			}
		}
		return($this->result);
	}

	function getDeleteMember($res_DB,$arr_input=null,$arrSearch=null){
		include("Model/Member/SQL/MySQL/getDeleteMember.php");
		$return = $res_DB->DB_access($res_DB,$query);
		return($return);
	}
	function levelUpdate($res_DB=null,$arr_input = null){
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/".__FUNCTION__.".php");
			if(!$res_DB->DB_access($res_DB,$query)){
				$this->result = false;
			}	
		}
		return($this->result);
	}
	function getMemberRanking($res_DB,$arr_input = null){
		include("Model/Member/SQL/MySQL/".__FUNCTION__.".php");
		$arr_ranking = $res_DB->DB_access($res_DB,$query);
		return($arr_ranking);
	}
	function memberLoginChk($res_DB=null,$arr_input = null,$checkType = null){
		$arr_login_info = array();
		switch($checkType){
			case("1"):
				include("Model/Member/SQL/MySQL/MemberLoginCheck.php");
				$this->result = $res_DB->DB_access($res_DB,$this->quary["member_login_check"]);
				if($this->result[0][mb_count]){
					$arr_input = array(column=>array("email","name","nickname"),member_id=>array($_COOKIE[member_id]));
					$arr_member_info = $this->getMember($res_DB,$arr_input);
					$arr_login_info[member_id] = $arr_member_info[0][member_id];
					$arr_login_info[member_name] = $arr_member_info[0][name];
					$arr_login_info[member_nickname] = $arr_member_info[0][nickname];
					$arr_login_info[member_level] = $arr_member_info[0][level];
					$arr_login_info[status] = 1;
				}else{
					$arr_login_info[status]= false;
				}
		 	break;
			default:
				$obj_DataHandler = new DataHandler();
				if($arr_login_info = $obj_DataHandler->getUserSession("login_info")){
					$return = $arr_login_info['login_info'];
				}
				else{
					$return = false;
				}
				break;
		}
		return($return);
	}
	function checkNickName($res_DB=null,$nickname){
		include("Model/Member/SQL/MySQL/checkNickName.php");
		$return = $res_DB->DB_access($res_DB,$query);
		return($return[0]['flg']);
	}
	function checkEmail($res_DB=null,$email){
		include("Model/Member/SQL/MySQL/".__FUNCTION__.".php");
		$return = $res_DB->DB_access($res_DB,$query);
		return($return[0]['flg']);
	}
	function memberLoginByIdAndPwd($strMemberId,$strPassword){
		if($strMemberId && $strPassword){
			include("Model/Member/SQL/MySQL/memberLoginByIdAndPwd.php");
			$arrResult = $this->result = $this->resMemberDB->DB_access($this->resMemberDB,$strQuery);
			if($arrResult[0]['cnt'] > 0){
				$arrInputMember = array(
						"member_id"=>$strMemberId,
						"column"=>array("A.member_seq","A.member_id","A.name","A.email","A.nickname","A.level")
				);				
				$arrMemberInfo = $this->getMember($this->resMemberDB,$arrInputMember);
				$mixReturn = true;
				$str_key = md5(rand(0,10000));
				$arrSession = array('login_info'=>array(
						"member_seq"=>(int)$arrMemberInfo[0][member_seq],
						"member_id"=>$arrMemberInfo[0][member_id],
						"member_name"=>$arrMemberInfo[0][name],
						"member_nickname"=>$arrMemberInfo[0][nickname],
						"member_email"=>$arrMemberInfo[0][email],
						"member_level"=>$arrMemberInfo[0][level],
						"auth_key"=>$str_key,
						"status"=>1
				));	
				$objDataHandler = new DataHandler();
				$objDataHandler->setUserSession($arrSession);
			}
		}else{
			$mixReturn = false;
		}
		return($mixReturn);
	}
	function memberLogin($res_DB,$arr_input = null){
		if(!empty($arr_input)){
			include("Model/Member/SQL/MySQL/MemberLogin.php");
		}
		$this->result = $res_DB->DB_access($res_DB,$this->quary["member_login"]);
		if($this->result[0][member_count]>0){
			$arr_input_member = array(
				"email"=>$arr_input[email],
				"column"=>array("A.member_seq member_id","A.name","A.email","A.nickname","A.level","A.team_seq")
				);
 			$arr_member_info = $this->getMember($res_DB,$arr_input_member);
 			$obj_DataHandler = new DataHandler();
 			$str_key = md5(rand(0,10000));
 			$arr_session = array('login_info'=>array(
				"member_id"=>(int)$arr_member_info[0][member_id],
				"member_name"=>$arr_member_info[0][name],
				"member_nickname"=>$arr_member_info[0][nickname],
				"member_email"=>$arr_member_info[0][email],
				"member_level"=>$arr_member_info[0][level],
 				"member_team"=>(int)$arr_member_info[0]['team_seq'],
				"auth_key"=>$str_key,
 				"opensocial_flg"=>$arr_member_info[0]['opensocial_flg'],
				"status"=>1
 			));
		 	if(defined("LV".$arr_member_info[0][level]."_NAME")){
 				$arr_session['login_info']['member_level_name'] = constant("LV".$arr_member_info[0][level]."_NAME");
 			} 
		 	if(defined("LV".$arr_member_info[0][level]."_ICON")){
 				$arr_session['login_info']['member_level_icon'] = constant("LV".$arr_member_info[0][level]."_ICON");
 			} 	 					
 			
 			$arr_cookie = array('rmEmail'=>$arr_input[email]);
 			
 			if(!empty($arr_input[save])){
 				$tmp=time()+(3600*240);
 				$obj_DataHandler->setUserCookie($arr_cookie,$tmp);	
 			}else{
 				
 				$obj_DataHandler->delUserCookie($arr_cookie);	
 			}
 			
 			$obj_DataHandler->setUserSession($arr_session);

 			$arr_member_info = array(
 			'member_seq' => $arr_member_info[0][member_id],
			'member_id' => $arr_member_info[0][member_id],
			'ip_address' => getenv("REMOTE_ADDR"),
 			'key'=>$str_key,
 			'login_num'=>1
 			);
 			$this->updateMember($res_DB,$arr_member_info);
 			return($arr_session);
		}else{
			return(false);
		}
	}
	function getLevelIcon($level){
		return(constant("LV".$level."_ICON"));
	}
	function memberLogout($res_DB){
		$str_key = md5(rand(0,10000));
		$arr_input = array(
		member_id => $_SESSION[member_id]?$_SESSION[member_id]:$_SESSION['login_info']['member_id'],
		key=>"",
		);
		$obj_DataHandler = new DataHandler();
		$arr_session = array("login_info");
		$obj_DataHandler->delUserSession($arr_session);
		$this->updateMember($res_DB,$arr_input);
		return(true);
	}
	function sendMemberInformationEmail($subject,$to_mail,$arr_input,$strMailContents=null){
		if(trim($to_mail)){
			$charset = 'UTF-8'; 
			$subject = "=?".$charset."?B?".base64_encode($subject)."?=\n";
			$from = '"=?'.$charset.'?B?'.base64_encode($arr_input['from_name']).'?='.'"<'.$arr_input['from_email'].'>'; 		
			$to_mail_send = '<'.$to_mail.'>' ; 
			$headers = "MIME-Version: 1.0\n". 
						"Content-Type: text/html; charset=".$charset."\n".
						"To: ". $to_mail_send ."\n". 
						"From: ".$from;
			if(!$strMailContents){
				$contents = '<h3>'.$arr_input[member_name].'님의  회원 정보 확인 메일 입니다.</h3>
				 <p>발송된 비밀번호는 임시로 발송된 비밀번호 입니다.<br />로그인 후 회원정보 변경 페이지에서 비밀번호를 꼭 변경해주세요.</p>
				 <ul>
					<li>회원 이름 : '.$arr_input[member_name].'</li>
					<li>닉네임 : '.$arr_input[member_nickname].'</li>
					<li>임시비밀번호 : '.$arr_input[member_pwd].'</li>
				 </ul>
				';	
			}			
			$mail=mail($to_mail,$subject,$contents,$headers);
		}	
	}
	
	// new
	function getMemberDetail($res_DB,$member_seq,$arr_input=null){
		include("Model/Member/SQL/MySQL/getMemberDetail.php");
		$return = $res_DB->DB_access($res_DB,$query);
		return($return);
	}
	public function checkIsMember($resMemberDB,$intMemberSeq,$strPassword){
		include("Model/Member/SQL/MySQL/checkIsMember.php");
		$return = $resMemberDB->DB_access($resMemberDB,$strQuery);
		return($return[0]['cnt']);		
	}
	public function checkIsMemberById($resMemberDB,$strMemberId,$intJoinType=JOIN_HANBNC){
		include("Model/Member/SQL/MySQL/checkIsMemberById.php");
		$return = $resMemberDB->DB_access($resMemberDB,$strQuery);
		return($return[0]['cnt']);
	}	
	function withdrawMember($resMemberDB,$intMemberSeq,$strPassword){
		include("Model/Member/SQL/MySQL/withdrawMember.php");
		$return = $resMemberDB->DB_access($resMemberDB,$strQuery);
		return($return);
	}
	function deleteMember($resMemberDB,$intMemberSeq){
		include("Model/Member/SQL/MySQL/deleteMember.php");
		$return = $resMemberDB->DB_access($resMemberDB,$strQuery);
		return($return);
	}
	public function setWithdrawReason($resMemberDB,$intMemberSeq,$strMemberName,$strReasonType,$strReason,$strFreeWrite=null){
		include("Model/Member/SQL/MySQL/setWithdrawReason.php");
		$return = $resMemberDB->DB_access($resMemberDB,$strQuery);
		return($return);		
	} 
	function getPrizeList($res_DB,$arr_input=null){
		include("Model/Member/SQL/MySQL/getPrizeList.php");
		$return = $res_DB->DB_access($res_DB,$query);
		return($return);
	}
	function updatePrizeState($res_DB,$match_seq,$ranking,$state){
		include("Model/Member/SQL/MySQL/getPrizeCount.php");
		$int_prize_count = $res_DB->DB_access($res_DB,$query);
		
		if($int_prize_count[0]['count'] > 0){
			include("Model/Member/SQL/MySQL/updatePrizeState.php");
		}else{
			include("Model/Member/SQL/MySQL/setPrizeState.php");
		}
		echo $query;
		if(!$res_DB->DB_access($res_DB,$query)){
			$this->result = false;
		}
		return($this->result);
		
	}
}
?>