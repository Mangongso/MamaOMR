<?php
$strQuery = sprintf("select count(*) as cnt from record where user_seq=%d and test_seq=%d and testing_time is null",$intMemberSeq,$intTestsSeq);
?>