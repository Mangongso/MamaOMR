<?php
$this->query["set_form_file"] = "insert into form_upload_file set 
server_file_name = '$arr_file_info[save_name]',
upload_file_name = '$arr_file_info[name]',
file_type = '$arr_file_info[type]',
file_size = '$arr_file_info[size]',
reg_date = SYSDATE(),
DATA_ID = $arr_input[data_id],
FORM_ID = $arr_input[form_id]
";
?>