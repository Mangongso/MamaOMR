<?php
$strQuery = sprintf ( "SELECT * FROM question_tag
							where question_seq in (SELECT sq.question_seq
													FROM test_question_list sq,question q
													WHERE sq.question_seq = q.seq
													AND test_seq=%d
													AND q.delete_flg=0)", $intTestsSeq );
?>