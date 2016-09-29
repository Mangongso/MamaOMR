<?php
$strQuery = sprintf("select count(*) as cnt from question_example where question_seq=%d and seq=%d and delete_flg=0",$intQuestionSeq,$intExampleSeq);
?>