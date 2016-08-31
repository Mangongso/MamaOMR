<?php
/*
 * Created on 2006. 12. 5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$arr_where = array();
$arr_where_sub = array();

if($arr_input[bbs_seq]){$arr_where[]="a.bbs_seq='".$arr_input[bbs_seq]."'";}
if($arr_input[reg_id]){
	$arr_where[]="(a.reg_id='".$arr_input[reg_id]."' or post_type=1)";
}

if($arr_input[search_column] == "subject"){
	$arr_where_sub[] = "subject like '%".$arr_input[search_text]."%'";
}
if($arr_input[search_column] == "contents"){
	$arr_where_sub[] = "a.seq in (select distinct(seq) from post_contents where content like '%".$arr_input[search_text]."%')";
}
if($arr_input[search_column] == "reg_name"){
	$arr_where_sub[] = "reg_name = '".$arr_input[search_text]."'";
}
if($arr_input[search_column] == "all"){
	$arr_where_sub[] = "subject like '%".$arr_input[search_text]."%' or a.seq in (select distinct(post_seq) from post_contents where content like '%".$arr_input[search_text]."%')";
}
if(count($arr_where_sub)>0){
	$arr_where[] = "(".join(" and ",$arr_where_sub).")";
}
if(count($arr_input['seq'])>0){
	$arr_where[] = " a.seq in (".join(",",$arr_input[seq]).")";
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
	$arr_where[] = " a.target_user like '%".$arr_input['target_user']."%'";
}

if(count($arr_input[seq])<1){
	if(!$arr_input['post_type']){
		$arr_where[] = " del_flg='0' and (post_type is null or post_type=0)";
	}else{
		$arr_where[] = " del_flg='0' and post_type=".$arr_input['post_type'];
	}
}

if(count($arr_where)>0){$str_where = " where ".join(" and ",$arr_where);}

//group
if($arr_input['group']){
	if(is_array($arr_input['group'])){
		$str_where .= " OR a.group in (".join(',',$arr_input['group']).")";
	}else{
		$str_where .= " OR a.group=".$arr_input['teacher_seq'];
	}
}

$str_order = "";
switch($arr_input[sort][column]){
	case("reg_date"):
	$str_order = "reg_date";
	break;
	case("modifydate"):
	$str_order = "modifydate";
	break;
	case("subject"):
	$str_order = "subject";
	break;	
	default:
	$str_order = "parent_post_id DESC,sort";
	break;
}
switch($arr_input[sort][column]){
	case("reg_date"):
	case("modifydate"):
	case("subject"):
	if($arr_input[sort][AD] == "D"){
		$str_order = $str_order." DESC";
	}
	break;
}
if(trim($str_order)){$str_order = " order by ".$str_order;}

if($arr_input[paging]){
	$str_limit = sprintf(" limit %d,%d",$arr_input[paging][limit_start],$arr_input[paging][limit_offset]);
}
/*
$this->query["get_post"] = "select 
a.seq,
a.subject,
unix_timestamp(a.reg_date) as reg_date,
unix_timestamp(a.modifydate) as modifydate,
a.link_seq,
a.parent_seq,
a.depth,
a.email,
a.homepage,
a.reg_ip,
a.w_year,
a.w_month,
a.reg_name,
a.reg_id,
a.password,
a.del_flg,
a.update_ip,
a.bbs_seq,
a.read_count,
b.comment_count 
from post a left outer join (select seq, count(seq) comment_count from post_comment where bbs_seq=".$arr_input[bbs_seq]." group by seq) b on a.seq = b.seq ";
*/

$this->query["get_post"] = "select 
a.seq,
a.subject,
unix_timestamp(a.reg_date) as reg_date,
unix_timestamp(a.modifydate) as modifydate,
a.link_post_id,
a.parent_post_id,
a.depth,
a.email,
a.homepage,
a.reg_ip,
a.w_year,
a.w_month,
a.reg_name,
a.reg_id,
a.password,
a.del_flg,
a.update_ip,
a.bbs_seq,
a.post_type,
a.category_seq,
a.read_count,
a.sort,
a.*,
@rownum:=@rownum+1 as row_num
from ".$this->table_name." a";

$this->query["get_post"] .= trim($str_where)?$str_where:"";
$this->query["get_post"] .= " and (@rownum:=0)=0 ";
$this->query["get_post"] .= trim($str_order)?$str_order:"";
$this->query["get_post"] .= trim($str_limit)?$str_limit:"";
//print_r($this->query["get_post"]);

unset($arr_where);
unset($arr_where_sub);
unset($str_order);
unset($str_where);
unset($str_order);
unset($str_limit);
?>
