<?php
$query = "SELECT * FROM 
		(SELECT A.member_seq, A.member_id, A.email, A.name, A.nickname, A.reg_date, A.modifydate, A.del_flg, A.ip_address, A.level, A.admin_level, A.confirm_flg, A.join_type, A.member_grade, B.tel, B.cphone, B.sex, B.member_type, B.academy, B.school, B.subject FROM member_basic_info AS A , member_extend_info AS B WHERE B.member_seq = A.member_seq) M 
		WHERE del_flg='1' ";
if($arr_input){
	$arr_where = array();
	foreach($arr_input as $column=>$value){
		$arr_where[]=sprintf("M.%s='%s'",$column,$value);
	}
	$query .= " and ".join(" and ",$arr_where);
}



if($arrSearch){
	if($arrSearch['search_type'] && $arrSearch['search_keyword']){
		if($arrSearch['search_type']!='member_type'){
			$query = $query." and M.".$arrSearch['search_type']." like '%".$arrSearch['search_keyword']."%' ";
		}else{
			$query = $query." and M.".$arrSearch['search_type']."='".$arrSearch['search_keyword']."' ";
		}
	}
}
$query = $query." order by M.modifydate DESC" ;

if($arr_input[paging]){
	if($query){$query .= " limit ".$arr_input[paging][limit_start].",".$arr_input[paging][limit_offset];}
}

//echo $query;
?>