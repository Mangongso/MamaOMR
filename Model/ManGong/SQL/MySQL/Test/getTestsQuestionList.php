<?php
$strQuery = sprintf("select
					SQ.test_seq,
					SQ.order_number,
					SQ.question_seq,
					SQ.question_number,
					SQ.question_score,
					Q.writer_seq,
					Q.contents,
					Q.question_type,
					Q.example_type,
					Q.hint,
					Q.commentary,
					Q.create_date,
					Q.modify_date,
					Q.tags,
					Q.file_name
				from
					test_question_list SQ left outer join question as Q ON SQ.question_seq=Q.seq
				where
					SQ.test_seq=%d and Q.question_type in (%s)
				order by SQ.order_number asc",$intTestsSeq,join(',',$arrQuestionType));
?>