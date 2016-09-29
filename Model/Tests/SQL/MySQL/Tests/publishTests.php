<?php
$strQuery = sprintf("insert into test_published (test_seq,start_date,finish_date,state,delete_flg,published_date,published_type) values (%d,'%s','%s',0,0,now(),%d)",$intTestsSeq,$strStartDate,$strFinishDate,$intPublishType);
?>