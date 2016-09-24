<?
$arr_input[confirm_flg] = (int)$arr_input[confirm_flg];
$this->quary["insert_member_basic_info"] = "insert into member_basic_info set " .
		"member_id = '$arr_input[member_id]'," .
		"email = '$arr_input[email]'," .
		"email_display = '$arr_input[email_display]'," .
		"name = '$arr_input[name]'," .
		"name_display = '$arr_input[name_display]'," .
		"nickname = '$arr_input[nickname]'," .
		"reg_date = sysdate()," .
		"login_num = 0," .
		"ip_address = '$arr_input[ip_address]'," .
		"pwd = password('$arr_input[pwd]')," .
		"del_flg = '0',".
		"confirm_flg = $arr_input[confirm_flg],".
		"join_type = ".($arr_input['join_type']?$arr_input['join_type']:JOIN_HANBNC).",".
		"admin_level = ".($arr_input['admin_level']?$arr_input['admin_level']:0).",".
		"n_keyword = '".$arr_input['n_keyword']."'";
?>