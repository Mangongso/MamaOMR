<?
if($arr_input['level'] && $arr_input['member_seq']){
	$query = sprintf("update member_basic_info set level=%d where member_seq=%d",$arr_input['level'],$arr_input['member_seq']);
}
?>