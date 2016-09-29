<?php
$strQuery = sprintf("SELECT user_email,question_seq,question_answer,user_answer,result_flg,create_date,%d,%d,example_seq,matrix_example_seq,0,1 FROM user_answer WHERE delete_flg=0 AND answer_type=0 AND question_seq=%d",$intPublishedSeq,$intTestsSeq,$intQuestionSeq);
$strQuery = "insert into user_answer (user_email,question_seq,question_answer,user_answer,result_flg,create_date,published_seq,test_seq,example_seq,matrix_example_seq,delete_flg,answer_type) ".$strQuery;
?>