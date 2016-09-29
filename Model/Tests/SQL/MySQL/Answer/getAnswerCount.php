<?php
if($intMatrixExampleSeq){
			$strQuery = sprintf("select count(*) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=%d",$intQuestionSeq,$intExampleSeq,$intMatrixExampleSeq);
		}else{
			$strQuery = sprintf("select count(*) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0",$intQuestionSeq,$intExampleSeq);
		}
		if($intPublishedSeq){
			$strQuery = $strQuery.sprintf(" and published_seq=%d",$intPublishedSeq);
		}
?>