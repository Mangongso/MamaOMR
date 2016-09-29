<?php
$strQuery = sprintf("select SQ.test_seq,SQ.question_seq,Q.writer_seq, Q.contents, Q.question_type, Q.example_type, Q.create_date, Q.modify_date from test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq where SQ.test_seq=%d",$intTestsSeq);
?>