<?php
$strQuery = sprintf("update user_answer set delete_flg=1 where test_seq=%d and published_seq=%d",$intTestsSeq, $intPublishedSeq);
if($intQuestionSeq){
	$strQuery = $strQuery.sprintf(" and question_seq=%d",$intQuestionSeq);
}
?>