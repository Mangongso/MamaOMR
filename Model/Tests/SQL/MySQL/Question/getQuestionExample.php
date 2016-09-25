<?php
if($intExampleType){
	$strQuery = sprintf("select * from question_example where question_seq=%d and example_type=%d and delete_flg=0",$intQuestionSeq,$intExampleType);
}else{
	$strQueryRows = sprintf("select * from question_example where question_seq=%d and example_type=1 and delete_flg=0",$intQuestionSeq);
	$strQueryCols = sprintf("select * from question_example where question_seq=%d and example_type=2 and delete_flg=0",$intQuestionSeq);
}

/*
$strQuery = sprintf("SELECT * FROM question_example_type".$arrQuestionResult[0]['example_type']." WHERE question_seq=%d AND delete_flg=0",$intQuestionSeq);
switch($arrQuestionResult[0]['example_type']){
	case("1"):
		break;
	case("2"):
		break;
	default:
		break;
}
*/
?>