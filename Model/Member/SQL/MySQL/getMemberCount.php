<?php

$query = "select count(*) as count from member_basic_info";

if(!$arrSearchs){
	$search_type = $arr_input[paging][paging][page][0][link_param][search_type];
	$search_text = $arr_input[paging][paging][page][0][link_param][search_text];
	if($search_type && $search_text){
		$query = $query." and A.".$search_type." like '%".$search_text."%' ";
	}
}else{
	foreach($arrSearchs as $intKey=>$arrSearch){
		if($arrSearch['search_type'] && $arrSearch['search_keyword']){
			switch($arrSearch['search_type']){
				case('member_type'):
					$query = $query." and B.".$arrSearch['search_type']."='".$arrSearch['search_keyword']."' ";
				break;
				case('client_id'):
					$query = $query." and A.member_id in (select member_id from project_at_member where project_seq in (select project_seq from project_at_member where member_id='".sprintf('%s',$arrSearch['search_keyword'])."'))";
					break;			
				default:
					$query = $query." and A.".$arrSearch['search_type']." like '%".$arrSearch['search_keyword']."%' ";
				break;
			}
		}
	}
}

?>