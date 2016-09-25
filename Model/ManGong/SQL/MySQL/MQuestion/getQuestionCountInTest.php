<?php
if(count($arrTestsSeq)){
	$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq in (".join(',',$arrTestsSeq).")");
}else if(!is_null($strTestsSeqGroup)){
	$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq in (".$strTestsSeqGroup.")");
}else{
	$strQuery = sprintf("select count(*) as question_count from test_question_list where test_seq=%d",$intTestSeq);
}
?>