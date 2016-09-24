<?php
$strQuery = sprintf("REPLACE into test_tag (test_seq,tag,create_date) values (%d,'%s',now())",$intTestsSeq,quote_smart($strTag));
?>