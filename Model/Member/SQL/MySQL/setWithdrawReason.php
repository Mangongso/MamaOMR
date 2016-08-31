<? 
$strQuery = sprintf("insert into member_withdraw set
member_seq=%d,
member_name='%s',
type=%d,
reason='%s',
free_contents='%s',
reg_date=now()
",
$intMemberSeq,
$strMemberName,
$strReasonType,
quote_smart($strReason),
quote_smart($strFreeWrite)
);
?>