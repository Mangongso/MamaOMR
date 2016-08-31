<?php
$strQuery = sprintf("
SELECT count(*) cnt FROM (
	SELECT A.*,B.cphone,B.sex,B.member_type,B.test_seq,B.test_flg FROM member_basic_info A 
	LEFT OUTER JOIN member_extend_info B 
	ON A.member_seq = B.member_seq 
	WHERE A.member_seq 
	IN(SELECT student_seq 
		FROM teacher_student_list 
		WHERE teacher_seq=%d 
		AND approve_flg=1 
		AND delete_flg=0) 
	AND del_flg='0' ) MA,
	(SELECT * FROM ticket WHERE delete_flg=0 GROUP BY student_seq ORDER BY seq DESC) T
WHERE MA.member_seq = T.student_seq 

",$intTeacherSeq,$intTeacherSeq);

$arrWhereQuery = array();
if(!is_null($intGroupSeq)){
	array_push($arrWhereQuery, sprintf(" AND MA.member_seq in (SELECT student_seq FROM group_user_list WHERE teacher_seq=%d AND group_seq=%d and delete_flg=0)  ",$intTeacherSeq,$intGroupSeq));
}

if(count($arrWhereQuery)>0){
	$strQuery .= " ".join($arrWhereQuery, 'and');
}
if($intTerm==1){//수강중
	$strQuery .= " AND T.expiration_date >= NOW() ORDER BY T.expiration_date ASC ";
}else{//기간만료
	$strQuery .= " AND T.expiration_date < NOW() ORDER BY T.expiration_date DESC ";
}

//print_r($strQuery);
//exit;
?>