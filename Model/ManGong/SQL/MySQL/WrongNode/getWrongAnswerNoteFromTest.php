<?php
$strInnerQuery = sprintf(" SELECT * FROM user_answer WHERE delete_flg=0 AND test_seq=%d ",$intTestSeq);
		if(!is_null($intRecordSeq)){
			$strInnerQuery .= sprintf(" AND record_seq=%d ",$intRecordSeq);
		}
		if(!is_null($intStudentSeq)){
			$strInnerQuery .= sprintf(" AND user_seq=%d ",$intStudentSeq);
		}
		$strQuery = sprintf("
				SELECT r1.*,tql.order_number 
				FROM ( SELECT ua.*,wn.seq AS wrong_note_list_seq,wn.create_date AS wrong_note_date,wn.file_name,wn.question AS question_contents
					FROM (".$strInnerQuery." group by question_seq having result_flg=0) ua 
					LEFT OUTER JOIN wrong_note_list wn ON ua.test_seq=wn.test_seq 
					/*AND ua.record_seq=wn.record_seq */
					AND ua.user_seq=wn.user_seq 
					AND ua.question_seq=wn.question_seq 
					) AS r1 
					LEFT JOIN test_question_list tql ON r1.test_seq=tql.test_seq AND r1.question_seq=tql.question_seq
					ORDER BY order_number
				",$intTestSeq);
?>