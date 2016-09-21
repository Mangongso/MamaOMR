<?php
$strQuery = sprintf("select *,UNIX_TIMESTAMP(start_date) as start_unix_time,UNIX_TIMESTAMP(finish_date) as finish_unix_time from test_published where test_seq=%d and published_type=%d and delete_flg=0 order by start_date",$intTestsSeq,$intPublishedType);
?>