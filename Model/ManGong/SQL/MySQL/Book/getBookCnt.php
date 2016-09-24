<?php
include("Model/ManGong/SQL/MySQL/Common/commonWhereQuery.php");
$strQuery = sprintf("select count(*) cnt from book_info where delete_flg=0 ");
if(count($arrSearch)){
	$strQuery .= " and ".join(' and ',$arrWhereQuery);
}
?>