<?php
$strQuery = sprintf("select count(*) as cnt from test_question_list where test_seq=%d and question_seq=%d",$intTestsSeq,$intQuestionSeq);
?>