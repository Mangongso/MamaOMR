<?php
$this->query["set_post_file"] = "insert into post_upload_file set 
server_file_name = '$arr_file_info[save_name]',
upload_file_name = '$arr_file_info[name]',
file_type = '$arr_file_info[type]',
file_size = '$arr_file_info[size]',
reg_date = SYSDATE(),
post_seq = $arr_input[seq],
bbs_seq = $arr_input[bbs_seq]
";
?>