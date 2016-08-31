<?
$this->quary["member_login"] = 
"select count(*) as member_count from member_basic_info " .
"where email='$arr_input[email]' and (pwd=password('$arr_input[pwd]') or pwd=old_password('$arr_input[pwd]')) and del_flg='0'";
?>