<?php
$strQuery = sprintf("UPDATE test_published SET total_score=(SELECT SUM(question_score) FROM test_question_list WHERE test_seq=%d) WHERE test_seq=%d AND seq=%d",$intTestSeq,$intTestSeq,$intPublishedSeq);
?>