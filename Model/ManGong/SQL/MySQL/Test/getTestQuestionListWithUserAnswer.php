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
				UA.seq as user_answer_seq,
				UA.record_seq,
				UA.result_flg,
				UA.user_answer,
				UA.score,
				UAD.discus_answer,
				UAD.answer_comment
				FROM
				test_question_list SQ
				LEFT OUTER JOIN question AS Q ON SQ.question_seq=Q.seq
				LEFT OUTER JOIN user_answer as UA ON UA.user_seq=%d AND UA.test_seq=SQ.test_seq AND UA.question_seq=Q.seq AND record_seq=(SELECT MAX(record_seq) FROM user_answer WHERE user_seq=UA.user_seq AND test_seq=UA.test_seq AND question_seq=UA.question_seq)
				LEFT JOIN user_answer_discus as UAD ON UAD.user_answer_seq=UA.seq and UAD.question_seq=Q.seq
				WHERE
				SQ.test_seq=%d and Q.question_type in (%s)
				ORDER BY SQ.order_number ASC",$intUserSeq,$intTestSeq,join(',',$arrQuestionType));
?>