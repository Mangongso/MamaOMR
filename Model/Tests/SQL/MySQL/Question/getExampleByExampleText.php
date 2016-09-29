<?php
$strQuery = sprintf("select * from question_example where question_seq=%d and contents='%s'",$intQuestionSeq,trim($strUserAnswer));
?>