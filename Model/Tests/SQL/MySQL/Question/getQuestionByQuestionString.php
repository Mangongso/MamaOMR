<?php
$strQuery = sprintf("select question_seq from test_question_list where question_seq in (select seq from question where contents='%s') and test_seq=%d",$strQuestion,$intTestsSeq);
$strQuery = "select * from question where seq in (".$strQuery.")";
?>