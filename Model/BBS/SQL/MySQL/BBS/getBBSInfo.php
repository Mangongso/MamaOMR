<?
$this->query['getBBSInfo'] = sprintf("select * from bbs where seq in ('%s')",join(',',$arr_input[bbs_seq]));
?>