<?php
$strQuery1 = "select count(distinct(user_email)) as cnt from user_answer";
$strQuery2 = sprintf("select count(distinct(user_email)) as cnt from user_answer where test_seq=%d",$intTestsSeq);
$strQuery3 = sprintf("select count(distinct(user_email)) as cnt from user_answer where test_seq=%d and published_seq=%d",$intTestsSeq,$intPublishedSeq);
?>