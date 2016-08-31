<? 
$strQuery = sprintf("update member_basic_info set del_flg='1' where member_seq=%d and pwd=password('%s')",$intMemberSeq,$strPassword);
?>