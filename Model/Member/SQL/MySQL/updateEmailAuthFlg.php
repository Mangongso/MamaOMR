<?
$strQuery = sprintf("update member_basic_info set auth_flg=1 where member_seq=%d and auth_key='%s'",$intMemberSeq,$strAuthKey);
?>