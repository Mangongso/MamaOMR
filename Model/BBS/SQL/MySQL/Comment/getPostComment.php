<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$this->query["get_post_comment"] = sprintf(
"select ".
"cmt_id,".
"cmt_name,".
"reg_id,".
"pwd,".
"comment,".
"reg_ip,".
"unix_timestamp(reg_date) as reg_date,".
"post_seq,".
"bbs_seq ".
"from ".$this->comment_table_name." where bbs_seq='%s' and post_seq in (%s) ",
$arr_input[bbs_seq],is_array($arr_input[post_seq])?join(",",$arr_input[post_seq]):$arr_input[post_seq]);
if(!is_null($arr_input[cmt_id])){
	$this->query["get_post_comment"] .= sprintf(" and cmt_id=%d ",$arr_input[cmt_id]);
}
$this->query["get_post_comment"] .= sprintf(" order by cmt_id DESC ");
?>
