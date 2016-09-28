<?php
include("Model/ManGong/SQL/MySQL/Common/commonWhereQuery.php");
$strQuery = sprintf("select *,md5(seq) as book_key from book_info where delete_flg=0 ");
if(count($arrSearch)){
	$strQuery .= " and ".join(' and ',$arrWhereQuery);
}
if(count($arrQueId)){
	$strQuery .= " and seq in (".join(',',$arrQueId).")";
}
$strQuery .= sprintf(" order by seq desc ");
if($arrPaging){
	if($strQuery){
		$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
	}
}
?>