<?
$strQuery = sprintf("
select count(*) as cnt from member_basic_info where member_id='%s' and join_type=%d
",
$strMemberId,
$intJoinType
);
?>