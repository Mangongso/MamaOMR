<?php
if(is_numeric($mixMemberSeq)){
	$strQuery = sprintf("select * from record where user_seq=%d ",$mixMemberSeq);
}else{
	$strQuery = sprintf("select * from record where md5(user_seq)='%s' ",$mixMemberSeq);
}

if(!is_null($intTestsSeq)){
	$strQuery .= sprintf(" and test_seq=%d ",$intTestsSeq);
}
if($intRevisionFlg){
	$strQuery .= sprintf(" and revision=%d ",$intRevisionFlg);
}
if(!$intSortFlg){
	$strQuery .= sprintf(" order by revision");
}else{
	$strQuery .= sprintf(" order by revision DESC");
}
?>