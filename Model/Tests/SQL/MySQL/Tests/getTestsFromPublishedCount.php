<?php
$strQuery = "select count(*) as cnt from test_published as sp left join test as s on sp.test_seq=s.seq where s.delete_flg=0 and sp.delete_flg=0";
if(!$boolNullDateShowFlg){
	$strQuery = $strQuery." and (start_date!='0000-00-00 00:00:00' and finish_date!='0000-00-00 00:00:00')";
}
$strWhere = $this->getSurverSearchQuery($arrSearch);
if(trim($strWhere)){
	$strQuery .= " and (".$strWhere.")";
}
?>