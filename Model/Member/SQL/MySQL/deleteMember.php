<? 
$strQuery = sprintf("update member_basic_info set del_flg='1',modifydate=now() where member_seq=%d",$intMemberSeq);
?>