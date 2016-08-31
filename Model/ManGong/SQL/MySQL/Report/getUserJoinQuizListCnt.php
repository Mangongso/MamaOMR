<?
include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");
$strSubQuery = '';
if(!is_null($intUserSeq)){
	$strSubQuery .= sprintf(" and user_seq=%d",$intUserSeq);
}else{
	$strSubQuery .= sprintf(" group by test_seq");
}
$strQuery = sprintf("SELECT count(*) cnt
				FROM ( 
					SELECT s.seq AS test_seq,s.create_date AS test_reg_date,s.start_date AS test_start_date,s.finish_date AS test_finish_date,s.*,rc.*
					FROM
					   (SELECT test_seq AS s_seq,
						right_count+wrong_count AS question_cnt,
						SUM(right_count)AS correct,
						SUM(wrong_count) AS incorrect,
						COUNT(user_seq) AS user_cnt
					   FROM record WHERE revision=1 ".$strSubQuery." GROUP BY test_seq) AS rc, 
					  (SELECT sv.*,sp.start_date,sp.finish_date 
						FROM test sv,test_published sp 
						WHERE sv.seq=sp.test_seq 
						AND sv.delete_flg=0
					  ) AS s 
					  where s.seq=rc.s_seq 
					) AS R,
					(SELECT mb.member_seq AS m_seq,mb.name AS writer_name FROM member_basic_info mb,member_extend_info me WHERE mb.member_seq=me.member_seq AND mb.del_flg='0' AND me.member_type='T') m
				WHERE R.writer_seq=m.m_seq ");
if(count($arrWhereQuery)){
	$strQuery .= " and ".join(" and ", $arrWhereQuery);
}
?>