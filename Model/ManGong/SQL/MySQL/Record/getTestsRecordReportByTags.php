<?php
$strQuery = sprintf("SELECT *,COUNT(tag) tag_cnt,SUM(result_flg) corect_cnt,ROUND(SUM(result_flg)/COUNT(tag),2)*100 AS corect_percent FROM 
	(SELECT * FROM user_answer WHERE test_seq=%d AND delete_flg=0 AND record_seq=%d) ua_re,
	(SELECT qt.*,qsq.seq,qsq.question_type,qsq.example_type FROM question_tag qt,
		(SELECT q.* 
			FROM question q,
			(SELECT * FROM test_question_list WHERE test_seq=%d) sq
		WHERE q.seq=sq.question_seq) qsq
	WHERE qt.question_seq=qsq.seq) q_re
WHERE ua_re.question_seq=q_re.seq GROUP BY tag
",$intTestsSeq,$intRecordSeq,$intTestsSeq);
?>