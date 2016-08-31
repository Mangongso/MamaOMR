<?
$query = sprintf(
<<<EOF
select count(*) as flg from member_basic_info where email='%s'
EOF
,
$email
);
?>