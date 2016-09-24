<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
$this->query["update_post_comment"] = sprintf(
"update ".$this->comment_table_name." set ". 
"cmt_name='%s',". 
"reg_id='%s',". 
"pwd='%s',".
"comment='%s',".
"reg_ip='%s' ".
"where bbs_seq=%d and seq=%d",
$arr_input[cmt_name],
$arr_input[reg_id],
$arr_input[pwd],
$arr_input[comment],
$_SERVER['REMOTE_ADDR'],
$arr_input[bbs_seq],
$arr_input[seq]
);
?>
