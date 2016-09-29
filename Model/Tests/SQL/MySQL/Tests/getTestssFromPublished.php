<?php
$strQuery = "select sp.seq as published_seq,start_date,finish_date,state,unix_timestamp(sp.start_date) as start_time,unix_timestamp(sp.finish_date) as finish_time,s.* from test_published as sp left join test as s on sp.test_seq=s.seq  where s.delete_flg=0 and sp.delete_flg=0";
if(!$boolNullDateShowFlg){
	$strQuery = $strQuery." and (start_date!='0000-00-00 00:00:00' and finish_date!='0000-00-00 00:00:00')";
}
$strWhere = $this->getSurverSearchQuery($arrSearch);
if(trim($strWhere)){
	$strQuery .= " and (".$strWhere.")";
}
$strQuery .= " order by ".$arrOrder['type']." ".$arrOrder['sort'].",s.seq DESC";
if($arrPaging){
	$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
}
?>