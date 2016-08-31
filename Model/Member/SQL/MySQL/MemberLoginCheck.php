<?
$this->quary["member_login_check"] = 
"select count(*) as mb_count from member_basic_info " .
"where member_id='$arr_input[member_id]' and auth_key=password('$arr_input[auth_key]') and del_flg='0'";
?>