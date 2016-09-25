<?php
if($intRecodeSeq){
	$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.result_flg,1,0)) AS right_count
			FROM user_answer AS ua
			LEFT JOIN test_question_list AS QL ON ua.test_seq=QL.test_seq AND ua.question_seq=QL.question_seq
			WHERE ua.user_seq=%d AND ua.test_seq=%d AND record_seq=%d",$intMemberSeq,$intTestsSeq,$intRecodeSeq);
}else{
	$strQuery = sprintf("SELECT ua.*,SUM(IF(ua.result_flg>0,ua.score,0)) AS user_score, SUM(QL.question_score) AS total_score, COUNT(ua.seq) AS total_count, SUM(IF(ua.result_flg=1,1,0)) AS right_count
			FROM user_answer AS ua
			LEFT JOIN test_question_list AS QL ON ua.test_seq=QL.test_seq AND ua.question_seq=QL.question_seq
			WHERE ua.user_seq=%d AND ua.test_seq=%d",$intMemberSeq,$intTestsSeq);			
}		
if($intQuestionSeq){
	$strQuery .= sprintf(" AND ua.question_seq=%d ",$intQuestionSeq);
}
?>