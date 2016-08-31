<?
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
		"del_flg = '0'";

$this->quary["insert_member_extend_info"] = "
insert into member_extend_info set
member_id = '$arr_input[member_id]',
tel = '$arr_input[tel]',
cphone = '$arr_input[cphone]',
sex = '$arr_input[sex]',
address1 = '$arr_input[address1]',
address2 = '$arr_input[address2]',
zcode1 = '$arr_input[zcode1]',
zcode2 = '$arr_input[zcode2]',
idno1 = '$arr_input[idno1]',
idno2 = '$arr_input[idno2]',
messenger = '$arr_input[messenger]',
messenger_display = '$arr_input[messenger_display]',
homepage = '$arr_input[homepage]',
homepage_display = '$arr_input[homepage_display]',
member_type = '$arr_input[member_type]'
";

?>