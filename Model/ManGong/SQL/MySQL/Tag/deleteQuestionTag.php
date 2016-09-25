<?php
$strQuery = sprintf("delete from question_tag where question_seq=%d ",$intQuestionSeq);
if($strTag){
	$strQuery .= sprintf(" and tag='%s' ",quote_smart($strTag));
}
?>