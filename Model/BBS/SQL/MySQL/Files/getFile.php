<?php
$this->query["getFile"] = sprintf("select file_id, server_file_name, upload_file_name, file_type, file_size, reg_date, post_seq, bbs_seq from post_upload_file where bbs_seq='%s' and post_seq in ('%s')",$arr_input[bbs_seq],is_array($arr_input[seq])?join(",",$arr_input[seq]):$arr_input[seq]);
?>