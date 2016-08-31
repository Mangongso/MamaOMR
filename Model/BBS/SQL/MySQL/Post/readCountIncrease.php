<?php
$this->query["read_count_increase"] = "update ".$this->table_name." set ".
"read_count=read_count+".((int)$arr_input['count']?$arr_input['count']:1).
" where bbs_seq='".$arr_input[bbs_seq]."' and seq in(".join(",",$arr_input[seq]).")";
?>