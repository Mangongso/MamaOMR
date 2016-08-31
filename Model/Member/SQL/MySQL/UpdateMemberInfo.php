<?
$arr_query = array();
if(array_key_exists("email",$arr_input)){array_push($arr_query,"email = '$arr_input[email]'");}  
if(array_key_exists("email_display",$arr_input)){array_push($arr_query,"email_display = '$arr_input[email_display]'");}
if(array_key_exists("name",$arr_input)){array_push($arr_query,"name = '$arr_input[name]'");}
if(array_key_exists("name_display",$arr_input)){array_push($arr_query,"name_display = '$arr_input[name_display]'");}
if(array_key_exists("login_num",$arr_input)){
	array_push($arr_query,"login_num = login_num+$arr_input[login_num]");
	array_push($arr_query,"modifydate = now()");
}
if(array_key_exists("ip_address",$arr_input)){array_push($arr_query,"ip_address = '$arr_input[ip_address]'");}
if(array_key_exists("pwd",$arr_input) && trim($arr_input['pwd'])){array_push($arr_query,"pwd = IF(pwd='$arr_input[pwd]','$arr_input[pwd]',password('$arr_input[pwd]'))");}
if(array_key_exists("key",$arr_input)){array_push($arr_query,"auth_key = password('$arr_input[key]')");}
if(array_key_exists("nickname",$arr_input)){array_push($arr_query,"nickname = '$arr_input[nickname]'");}
if(array_key_exists("login_guide_flg",$arr_input)){array_push($arr_query,"login_guide_flg = $arr_input[login_guide_flg]");}
if(count($arr_query)>0){
	$this->quary["update_member_basic_info"] = "update member_basic_info set ".join(",",$arr_query)." where $strSeqColumn = '$arr_input[member_id]'";
}
unset($arr_query); 
$arr_query = array();
if(array_key_exists("tel",$arr_input)){array_push($arr_query,"tel = '$arr_input[tel]'");}
if(array_key_exists("cphone",$arr_input)){array_push($arr_query,"cphone = '$arr_input[cphone]'");}
if(array_key_exists("sex",$arr_input)){array_push($arr_query,"sex = '$arr_input[sex]'");}
if(array_key_exists("address1",$arr_input)){array_push($arr_query,"address1 = '$arr_input[address1]'");}
if(array_key_exists("address2",$arr_input)){array_push($arr_query,"address2 = '$arr_input[address2]'");}
if(array_key_exists("zcode1",$arr_input)){array_push($arr_query,"zcode1 = '$arr_input[zcode1]'");}
if(array_key_exists("zcode2",$arr_input)){array_push($arr_query,"zcode2 = '$arr_input[zcode2]'");}
if(array_key_exists("messenger",$arr_input)){array_push($arr_query,"messenger = '$arr_input[messenger]'");}
if(array_key_exists("messenger_display",$arr_input)){array_push($arr_query,"messenger_display = '$arr_input[messenger_display]'");}
if(array_key_exists("homepage",$arr_input)){array_push($arr_query,"homepage = '$arr_input[homepage]'");}
if(array_key_exists("homepage_display",$arr_input)){array_push($arr_query,"homepage_display = '$arr_input[homepage_display]'");}
if(count($arr_query)>0){
	$this->quary["update_member_extend_info"] = "update member_extend_info set ".join(",",$arr_query)." where $strSeqColumn = ".$arr_input[member_id];
}
unset($arr_query);
?>