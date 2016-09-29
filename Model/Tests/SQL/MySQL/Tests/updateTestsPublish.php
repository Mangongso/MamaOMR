<?php
$strQuery = sprintf("update test_published set start_date='%s',finish_date='%s',published_type=%d where test_seq=%d and seq=%d",$strStartDate,$strFinishDate,$intTestsSeq,$intPublishSeq,$intPublishType);
?>