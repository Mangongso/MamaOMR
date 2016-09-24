<?php
$strQuery = sprintf("update question_example set contents='%s' where question_seq=%d and seq=%d",quote_smart(trim($strContents)),$intQuestionSeq,$intExampleSeq);
?>