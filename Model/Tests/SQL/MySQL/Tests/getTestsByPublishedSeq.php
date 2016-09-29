<?php
$strQuery = sprintf("select s.*,sp.seq as publish_seq,sp.start_date,sp.finish_date,unix_timestamp(sp.start_date) as start_time,unix_timestamp(sp.finish_date) as finish_time from test_published as sp join test as s on sp.test_seq=s.seq where sp.seq=%d",$intPublishedSeq);
?>