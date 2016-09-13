<?php
$strQuery = sprintf("update test_question_list set question_number=%d,question_score=%d,order_number=%d where test_seq=%d and question_seq=%d",$intQuestionNumber,$intQuestionScore,$intOrderNumber,$intTestsSeq,$intQuestionSeq);
?>