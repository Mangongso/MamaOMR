<?php
$strQuery = sprintf("SELECT
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
				UAL.user_answer,
				UAL.discus_answer
				FROM
				test_question_list SQ
				LEFT OUTER JOIN question AS Q ON SQ.question_seq=Q.seq
				LEFT OUTER JOIN user_answer_log as UAL ON UAL.user_seq=%d AND UAL.test_seq=SQ.test_seq AND UAL.question_seq=Q.seq
				WHERE
				SQ.test_seq=%d and Q.question_type in (%s)
				ORDER BY SQ.order_number ASC",$intUserSeq,$intTestSeq,join(',',$arrQuestionType));
?>