<?php
/*
 * Created on 2006. 12. 7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 */
// make where 
$arr_where = array();
array_push($arr_where,sprintf("(bbs_seq=%d and seq in (%s))",$arr_input['bbs_seq'],join(",",$arr_input['seq'])));
if($arr_input['reg_id']){
	array_push($arr_where,sprintf("reg_id=%d",$arr_input['reg_id']));
}
if(count($arr_where)>0){
	$str_where = join(" and ",$arr_where);
	if($delete_flg == true){
		$this->query["delete_contents"] = "delete from ".$this->table_name." where ".$str_where;
		$this->query["delete_post_upload_files"] = "delete from post_upload_file where ".$str_where;
		$this->query["delete_post_comment"] = "delete from post_comment where ".$str_where;
		$this->query["delete_post"] = "delete from ".$this->table_name." where ".$str_where;
	}else{
		if($arr_input[bbs_seq] && $arr_input[seq]){
			$this->query["delete_post"] = "update ".$this->table_name." set del_flg = '1' where ".$str_where;
		}
	}
}
?>
