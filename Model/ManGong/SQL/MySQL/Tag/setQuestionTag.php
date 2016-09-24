<?php
$strQuery = sprintf("REPLACE into question_tag (question_seq,tag,create_date) values (%d,'%s',now())",$intQuestionSeq,quote_smart($strTag));
?>