<?
$this->quary["insert_member_extend_info"] = "
insert into member_extend_info set
member_seq = $arr_input[member_seq],
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
birth_day_y = '$arr_input[birth_day_y]',
birth_day_m = '$arr_input[birth_day_m]',
birth_day_d = '$arr_input[birth_day_d]',
member_type = '$arr_input[member_type]'
";
?>