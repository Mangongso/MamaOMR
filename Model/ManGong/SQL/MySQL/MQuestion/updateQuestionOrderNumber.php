<?php
$strQuery = sprintf("update test_question_list set order_number=order_number+1 where test_seq=%d and order_number>=%d",$intTestsSeq,$intQuestionSeq,$intQuestionNumber,$intQuestionScore,$intOrderNumber);
?>