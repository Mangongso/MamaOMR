<?php
$query = "SELECT * FROM member_basic_info AS A , member_extend_info AS B WHERE B.member_seq = A.member_seq ";
//SELECT * FROM member_basic_info AS A , member_extend_info AS B WHERE B.member_seq = A.member_seq  ORDER BY A.member_seq  DESC LIMIT 0,10 
/*
if(trim($order)){
	$query = sprintf($query." order by %s",$order);
}else{
	
}*/
if(!$arrSearch){
	$search_type = $arr_input[paging][paging][page][0][link_param][search_type];
	$search_text = $arr_input[paging][paging][page][0][link_param][search_text];
	if($search_type && $search_text){
		$query = $query." and A.".$search_type." like '%".$search_text."%' ";
	}
}else{
	foreach($arrSearch as $intKey=>$arrSubSearch){
		if($arrSubSearch['search_type'] && !is_null($arrSubSearch['search_keyword'])){
			switch($arrSubSearch['search_type']){
				case('member_type'):
					$query = $query." and B.".$arrSubSearch['search_type']."='".$arrSubSearch['search_keyword']."' ";
				break;
				case('client_id'):
					$query = $query." and A.member_id in (select member_id from project_at_member where project_seq in (select project_seq from project_at_member where member_id='".sprintf('%s',$arrSubSearch['search_keyword'])."'))";
					break;			
				default:
					$query = $query." and A.".$arrSubSearch['search_type']." like '%".$arrSubSearch['search_keyword']."%' ";
				break;
			}
		}
	}
}
$query = $query." order by A.reg_date DESC" ;

if($arr_input[paging]){
	if($query){$query .= " limit ".$arr_input[paging][limit_start].",".$arr_input[paging][limit_offset];}
}

//echo $query;
?>