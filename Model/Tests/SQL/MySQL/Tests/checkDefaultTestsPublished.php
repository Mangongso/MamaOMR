<?php
$strQuery = sprintf("select count(*) as cnt from test_published where test_seq=%d and published_type=1",$intTestsSeq);
?>