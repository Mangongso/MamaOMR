<?php
/*
 * Created on 2006. 12. 7
*
* To change the template for this generated file go to
* Window - Preferences - PHPeclipse - PHP - Code Templates
*/

// date 부분 mysql 함수 이용하여 입력 하도록 수정할것 !

if(!$arr_input['reg_id']){
	$this->query["update_post"] = "update ".$this->table_name." set ".
			"subject='".quote_smart($arr_input[subject])."', ".
			"homepage='".$arr_input[homepage]."', ".
			"email='".$arr_input[email]."', ".
			"update_ip='".$arr_input[update_ip]."', ".
			"reg_name='".$arr_input[reg_name]."', ".
			"password=IF(password='$arr_input[password]','$arr_input[password]',password('".$arr_input[password]."')), ".
			"bbs_seq='".$arr_input[bbs_seq]."',".
			"post_type='".$arr_input['post_type']."',".
			"keyword='".$arr_input['keyword']."'".
			" where bbs_seq='".$arr_input[bbs_seq]."' and seq=".$arr_input[seq]." and password='".$arr_input[orignal_password]."'";
}else{
	$this->query["update_post"] = "update ".$this->table_name." set ".
			"subject='".quote_smart($arr_input[subject])."', ".
			"homepage='".$arr_input[homepage]."', ".
			"email='".$arr_input[email]."', ".
			"update_ip='".$arr_input[update_ip]."', ".
			"reg_name='".$arr_input[reg_name]."', ".
			"bbs_seq='".$arr_input[bbs_seq]."', ".
			"post_type='".$arr_input['post_type']."',".
			"keyword='".$arr_input['keyword']."'".
			" where bbs_seq='".$arr_input[bbs_seq]."' and seq=".$arr_input[seq]." and reg_id='".$arr_input['reg_id']."'";
}
?>
