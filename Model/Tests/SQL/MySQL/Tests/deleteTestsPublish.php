<?php
$strQuery = sprintf("update test_published set delete_flg=1 where test_seq=%d and seq=%d and published_type=0",$intTestsSeq,$intPublishSeq);
?>