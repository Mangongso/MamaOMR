<?php
/*
 * Created on 2006. 12. 5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$arr_where = array();
$arr_where_sub = array();

if(trim($arr_input[bbs_seq])){$arr_where[]="a.bbs_seq='".$arr_input[bbs_seq]."'";}
if($arr_input[search_column] == "subject"){
	$arr_where_sub[] = "subject like '%%".$arr_input[search_text]."%%'";
}
if($arr_input[search_column] == "contents"){
	$arr_where_sub[] = "a.seq in (select distinct(seq) from post_contents where content like '".$arr_input[search_text]."%%')";
}
if($arr_input[search_column] == "reg_name"){
	$arr_where_sub[] = "reg_name = '".$arr_input[search_text]."'";
}
if($arr_input[search_column] == "all"){
	$arr_where_sub[] = "subject like '%%".$arr_input[search_text]."%%' or a.seq in (select distinct(seq) from post_contents where content like '".$arr_input[search_text]."%%')";
}
if(count($arr_where_sub)>0){
	$arr_where[] = "(".join(" and ",$arr_where_sub).")";
}

//teacher_seq
if($arr_input['teacher_seq']){
	if(is_array($arr_input['teacher_seq'])){
		$arr_where[] = " teacher_seq in (".join(',',$arr_input['teacher_seq']).")";
	}else{
		if(is_numeric($arr_input['teacher_seq'])){
			$arr_where[] = " teacher_seq='".$arr_input['teacher_seq']."'";
		}else{
			$arr_where[] = " md5(teacher_seq)='".$arr_input['teacher_seq']."'";
		}
	}
}

if($arr_input['writer_seq']){
	$arr_where[] = " writer_seq=".$arr_input['writer_seq'];
}
//target_user
if($arr_input['target_user']){
	$arr_where[] = " a.target_user like '%%".$arr_input['target_user']."%%'";
}

if($arr_input[post_type]){
	$arr_where[] = "del_flg='0' and post_type=".$arr_input[post_type];
}else{
	$arr_where[] = "del_flg='0' and post_type is null";
}

if(count($arr_where)>0){$str_where = " and ".join(" and ",$arr_where);}

$this->query["get_prev_post"] = sprintf("select 
seq,
subject,
unix_timestamp(reg_date) as reg_date,
unix_timestamp(modifydate) as modifydate,
link_post_id,
parent_post_id,
depth,
email,
reg_ip,
w_year,
w_month,
reg_name,
reg_id,
password,
del_flg,
update_ip,
bbs_seq,
read_count
from ".$this->table_name." a
where bbs_seq='%s' and seq>%d $str_where order by seq limit 0,1",$arr_input[bbs_seq],$arr_input[seq]);
?>
