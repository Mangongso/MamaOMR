<?php
$strQuery = "select * from question where delete_flg=0 ";
if($intWriterType){
	$strQuery .= sprintf(" and writer_seq=%d",$intWriterType);
}
if($intQuestionType){
	$strQuery .= sprintf(" and question_type=%d",$intQuestionType);
}
if($strQuestionTagName){
	$strQuery .= sprintf(" and seq in (select question_seq from question_tag where tag_name='%s' and delete_flg=0)",$strQuestionTagName);
}
$strQuery = $strQuery . ' order by sort';
?>