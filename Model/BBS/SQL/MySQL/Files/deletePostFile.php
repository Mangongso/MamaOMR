<?php
$this->query["delete_post_file"] = "delete from post_upload_file where bbs_seq=$arr_input[bbs_seq] and seq=$arr_input[seq] and file_id in (".join(",",$arr_input[delete_file]).")"; 
?>