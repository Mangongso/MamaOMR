<?

if(is_array($arr_input[column])){
	$str_column = join(",",$arr_input[column]);
}

$arr_where = array();
if(!is_null($arr_input[member_id])){
	$arr_where[] = sprintf("(A.member_seq='%s' or A.member_id='%s')",$arr_input[member_id],$arr_input[member_id]);
}else{
	if($arr_input[email]){$arr_where[]=sprintf("A.email='%s'",$arr_input[email]);}
	if($arr_input[idno1] || $arr_input[idno2]){$arr_where[]=sprintf("(B.idno1='%s' and B.idno2='%s')",$arr_input[idno1],$arr_input[idno2]);}
	if($arr_input[name]){$arr_where[]=sprintf("A.name='%s'",$arr_input[name]);}
	if($arr_input[tel]){$arr_where[]=sprintf("B.tel='%s'",$arr_input[tel]);}
	if($arr_input[auth_key]){
		$arr_where[]=sprintf("A.auth_key='%s'",$arr_input[auth_key]);
	}
}	
$this->query["select_member_info"] = sprintf("
select $str_column from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where %s",join(" and ",$arr_where)); 

if($strOrder){
	$this->query["select_member_info"] = $this->query["select_member_info"].sprintf(" order by %s",$strOrder);	
}
?>