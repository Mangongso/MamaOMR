<?php
/*
 * Created on 2006. 12. 5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$arr_where = array();
array_push($arr_where,sprintf("(bbs_seq=%d and seq in (%s))",$arr_input['bbs_seq'],join(",",$arr_input['seq'])));
$str_where = join(" or ",$arr_where);
if($arr_input[member_level]<100){
	if(trim($arr_input[member_id])){
		$str_where = "(".$str_where.")".sprintf(" and  reg_id = '%s'",$arr_input[member_id]);
	}
	if(!trim($arr_input[member_id]) || trim($arr_input[password])){
		$str_where = "(".$str_where.")".sprintf(" and  password = password('%s')",$arr_input[password]);
	}
}
$this->query["auth_post"] = "select seq from ".$this->table_name." where ".$str_where;
?>
