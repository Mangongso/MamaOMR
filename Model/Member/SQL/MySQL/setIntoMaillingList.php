<?
$strQuery = sprintf("insert into rm_mailling set 
recive_name='%s',         
email='%s',
create_time=now(),  
join_type=1,
del_flg=0",
$strReciveName,
$strEmail
);
?>