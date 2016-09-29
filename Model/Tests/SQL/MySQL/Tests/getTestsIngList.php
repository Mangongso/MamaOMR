<?php
$strQuery = "select sp.seq as published_seq,start_date,finish_date,state,UNIX_TIMESTAMP(start_date) as start_time,UNIX_TIMESTAMP(finish_date) as finish_time,s.* from test_published as sp left join test as s on sp.test_seq=s.seq where sp.start_date<=now() and sp.finish_date>=now() order by sp.start_date DESC,s.seq DESC";
?>