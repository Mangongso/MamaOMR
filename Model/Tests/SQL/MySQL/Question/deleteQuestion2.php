<?php
if($intPublishedSeq){
	$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and published_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intPublishedSeq,$intQuestionSeq);
}else{
	$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and question_seq=%d and delete_flg=0",$intTestsSeq,$intQuestionSeq);
}
?>