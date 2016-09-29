<?php
$strQuery = sprintf("update test_question_list set sort=%d where test_seq=%d and question_seq=%d",$arrSort['position'],$intTestsSeq,$arrSort['seq']);
?>