<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
$this->query["set_post_comment"] = sprintf(
"insert into ".$this->comment_table_name." set ". 
"cmt_name='%s',". 
"reg_id='%s',". 
"pwd=password('%s'),".
"comment='%s',".
"reg_ip='%s',".
"reg_date=SYSDATE(),".
"post_seq=%d,".
"bbs_seq=%d",
$arr_input[cmt_name],
$arr_input[reg_id],
$arr_input[cmt_pwd],
quote_smart($arr_input[comment]),
$_SERVER['REMOTE_ADDR'],
$arr_input[post_seq],
$arr_input[bbs_seq]);
?>