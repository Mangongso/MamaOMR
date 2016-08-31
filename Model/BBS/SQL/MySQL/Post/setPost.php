<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // date 부분 mysql 함수 이용하여 입력 하도록 수정할것 !
 
$this->query["set_post"] = "insert into ".$this->table_name." set ".
"subject='".quote_smart($arr_input[subject])."', ".
"sort=".$arr_input[sort].", ";
if($arr_input[parent_seq]){$this->query["set_post"] .= "parent_post_id=".$arr_input[parent_seq].", ";}
if($arr_input['read_flg']){
	$this->query["set_post"] .= "read_flg=".$arr_input['read_flg'].", ";
}
if($arr_input[group]){$this->query["set_post"] .= "post.group='".$arr_input[group]."', ";}
if($arr_input[target_user]){$this->query["set_post"] .= "post.target_user='".join(',',$arr_input[target_user])."', ";}

$this->query["set_post"] .= "depth=".$arr_input[depth].", ".
"email='".$arr_input[email]."', ".
"homepage='".$arr_input[homepage]."', ".
"reg_ip='".$arr_input[reg_ip]."', ".
"w_year=DATE_FORMAT(SYSDATE(),'%Y'), ".
"w_month=DATE_FORMAT(SYSDATE(),'%m'), ".
"reg_name='".$arr_input[reg_name]."', ".
"reg_id='".$arr_input['reg_id']."', ".
"reg_date=SYSDATE(), ".
"password=password('".$arr_input[password]."'), ".
"post_type='".$arr_input[post_type]."', ".
"bbs_seq='".$arr_input[bbs_seq]."', ".
"category_seq='".$arr_input[category_seq]."', ".
"writer_seq='".$arr_input[writer_seq]."', ".
"teacher_seq='".$arr_input[teacher_seq]."', ".
"keyword='".$arr_input[keyword]."', ".
"del_flg='0'";

if($arr_input[link_seq]){$this->query["set_post"] .= ",link_seq=".$arr_input[link_seq];}

$this->query["get_post_insert_id"] .= "select max(seq) as post_insert_id from post "
?>
