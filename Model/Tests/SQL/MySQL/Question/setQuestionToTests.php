<?php
$strQuery1 = sprintf("insert into test_question_list (test_seq,question_seq,sort) select %d,%d,max(sort)+1 from test_question_list where test_seq=%d",$intTestsSeq,$intQuestionSeq,$intPosition,$intTestsSeq);
$strQuery2 = sprintf("update test_question_list set sort=sort+1 where test_seq=%d and sort>%d",$intTestsSeq,$intPosition+1);
$strQuery3 = sprintf("update test_question_list set sort=sort+1 where test_seq=%d and sort>=%d",$intTestsSeq,$intPosition);
$strQuery4 = sprintf("insert into test_question_list set test_seq=%d,question_seq=%d,sort=%d",$intTestsSeq,$intQuestionSeq,$intPosition);
?>