<?php
if($intPublishedSeq){
			$strQuery = sprintf("select user_answer,count(user_answer) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0 and published_seq=%d group by user_answer",$intQuestionSeq,$intExampleSeq,$intPublishedSeq);
		}else{
			$strQuery = sprintf("select user_answer,count(user_answer) as cnt from user_answer where delete_flg=0 and question_seq=%d and example_seq=%d and matrix_example_seq=0 group by user_answer",$intQuestionSeq,$intExampleSeq);
		}
?>