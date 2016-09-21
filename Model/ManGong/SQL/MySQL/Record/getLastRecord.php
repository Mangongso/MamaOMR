<?php
$strQuery = sprintf("select * from record where user_seq=%d and test_seq=%d ORDER BY revision DESC",$intMemberSeq,$intTestsSeq);
?>