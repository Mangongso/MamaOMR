<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

if($arr_input['reg_id']){
	$this->query["delete_post_comment"] = sprintf(
			"delete from ".$this->comment_table_name." ".
			"where bbs_seq=%d and post_seq in(%s) and cmt_id=%d and reg_id='%s'",
			$arr_input[bbs_seq],
			join(",",$arr_input['post_seq']),
			$arr_input[cmt_id],
			$arr_input[reg_id]
	);	
}else{
	$this->query["delete_post_comment"] = sprintf(
	"delete from ".$this->comment_table_name." ".
	"where bbs_seq=%d and post_seq in(%s) and cmt_id=%d and pwd=password('%s')",
	$arr_input[bbs_seq],
	join(",",$arr_input['post_seq']),
	$arr_input[cmt_id],
	$arr_input[cmt_pwd]
	);
}
?>
