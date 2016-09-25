<?php
if(count($arrTestsSeq)){
	$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(distinct(user_seq)) as user_count from record where test_seq in (".join(',',$arrTestsSeq).") and revision=1");
}else if(!is_null($strTestsSeqGroup)){
	$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(distinct(user_seq)) as user_count from record where test_seq in (".$strTestsSeqGroup.") and revision=1");
}else{
	$strQuery = sprintf("select seq, COALESCE(SUM(user_score),0) AS total_user_score, COALESCE(total_score,0) AS total_score, count(user_seq) as user_count from record where test_seq=%d and revision=1",$intTestsSeq);
}
if(!is_null($strUserSeq)){
	$strQuery .= sprintf(" and md5(user_seq)='%s' ",$strUserSeq);
}
?>