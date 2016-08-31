<?
$strQuery = sprintf("
select count(*) as cnt from member_basic_info where member_seq=%d and pwd=password('%s')
",
$intMemberSeq,
$strPassword
);
?>