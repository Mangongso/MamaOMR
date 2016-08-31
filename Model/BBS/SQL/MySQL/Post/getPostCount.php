<?php
/*
 * Created on 2006. 12. 5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$arr_where = array();
$arr_where_sub = array();

if($arr_input[bbs_seq]){$arr_where[]="bbs_seq='".$arr_input[bbs_seq]."'";}
if($arr_input[search_column] == "subject"){
	$arr_where_sub[] = "subject like '%".$arr_input[search_text]."%'";
}
if($arr_input[search_column] == "contents"){
	$arr_where_sub[] = "a.seq in (select distinct(seq) from post_contents where content like '".$arr_input[search_text]."%')";
}
if($arr_input[search_column] == "reg_name"){
	$arr_where_sub[] = "reg_name = '".$arr_input[search_text]."'";
}
if($arr_input[search_column] == "all"){
	$arr_where_sub[] = "subject like '%".$arr_input[search_text]."%' or a.seq in (select distinct(seq) from post_contents where content like '".$arr_input[search_text]."%')";
}
if($arr_input[start_reg_date]){
	$arr_where_sub[] = "reg_date>='".$arr_input[start_reg_date]."'";
}
if($arr_input[end_reg_date]){
	$arr_where_sub[] = "reg_date<='".$arr_input[end_reg_date]."'";
}
if($arr_input[password]){
	$arr_where_sub[] = "password=password('".$arr_input[password]."')";
}
if(count($arr_where_sub)>0){
	$arr_where[] = "(".join(" and ",$arr_where_sub).")";
}
if(count($arr_input[seq])>0){
	$arr_where[] = " seq in (".join(",",$arr_input[seq]).")";
}

if($arr_input['teacher_seq']){
	if(is_array($arr_input['teacher_seq'])){
		$arr_where[] = " teacher_seq in (".join(',',$arr_input['teacher_seq']).")";
	}else{
		$arr_where[] = " md5(teacher_seq)='".$arr_input['teacher_seq']."'";
	}
}else if($arr_input['writer_seq']){
	$arr_where[] = " writer_seq=".$arr_input['writer_seq'];
}

//target_user
if($arr_input['target_user']){
	$arr_where[] = " a.target_user like '%".$arr_input['target_user']."%'";
}

if(count($arr_input[seq])<1){
	if(!$arr_input['post_type']){
		$arr_where[] = "del_flg='0' and (post_type is null or post_type=0)";
	}else{
		$arr_where[] = "del_flg='0' and post_type=".$arr_input['post_type'];
	}
}
if(count($arr_where)>0){$str_where = " where ".join(" and ",$arr_where);}

$this->query["get_post"] = "select count(*) as count from ".$this->table_name." a ";
$this->query["get_post"] .= trim($str_where)?$str_where:"";
unset($arr_where);
unset($arr_where_sub);
unset($str_order);
unset($str_where);
unset($str_order);
unset($str_limit);
?>
