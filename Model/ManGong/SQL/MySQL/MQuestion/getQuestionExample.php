<?php
if($intExampleType){
	$strQuery = sprintf("select * from question_example where question_seq=%d and example_type=%d order by example_number asc",$intQuestionSeq,$intExampleType);
	if($intLimit){
		$strQuery = $strQuery.sprintf(' limit 0,%d',$intLimit);
	}	
}else{
	$strQueryRows = sprintf("select * from question_example where question_seq=%d and example_type=1 order by example_number asc",$intQuestionSeq);
	$strQueryCols = sprintf("select * from question_example where question_seq=%d and example_type=2 order by example_number asc",$intQuestionSeq);
}
?>