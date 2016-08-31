<?php
//수강중
$strQueryPart1 = sprintf("
SELECT * FROM (
	SELECT B.member_seq,A.name,A.email,B.cphone,B.sex,B.member_type,B.test_seq,B.test_flg FROM member_basic_info A 
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
	$strQueryPart1 .= " ".join($arrWhereQuery, 'and');
}

if(count($arrSch)){
	$strQueryPart1 .= sprintf(' AND MA.%s like "%%%s%%" ',$arrSch['key'],$arrSch['value']);
}
$strQueryPart1 .= " AND T.expiration_date >= NOW() ORDER BY T.expiration_date ASC ";

//기간만료
$strQueryPart2 = sprintf("
SELECT * FROM (
	SELECT B.member_seq,A.name,A.email,B.cphone,B.sex,B.member_type,B.test_seq,B.test_flg FROM member_basic_info A 
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

",$intTeacherSeq);


//신규
$strQueryPart3 = sprintf("
SELECT * FROM (
	SELECT B.member_seq,A.name,A.email,B.cphone,B.sex,B.member_type,B.test_seq,B.test_flg FROM member_basic_info A 
	LEFT OUTER JOIN member_extend_info B 
	ON A.member_seq = B.member_seq 
	WHERE A.member_seq 
	IN(SELECT student_seq 
		FROM teacher_student_list 
		WHERE teacher_seq=%d 
		AND approve_flg=1 
		AND delete_flg=0) 
	AND del_flg='0' ) MA LEFT OUTER JOIN
	(SELECT * FROM ticket WHERE delete_flg=0 GROUP BY student_seq ORDER BY seq DESC) T
ON MA.member_seq = T.student_seq
where T.expiration_date IS NULL

",$intTeacherSeq);


$arrWhereQuery = array();
if(!is_null($intGroupSeq)){
	array_push($arrWhereQuery, sprintf(" AND MA.member_seq in (SELECT student_seq FROM group_user_list WHERE teacher_seq=%d AND group_seq=%d and delete_flg=0)  ",$intTeacherSeq,$intGroupSeq));
}

if(count($arrWhereQuery)>0){
	$strQueryPart2 .= " ".join($arrWhereQuery, 'and');
}

if(count($arrSch)){
	$strQueryPart2 .= sprintf(' AND MA.%s like "%%%s%%" ',$arrSch['key'],$arrSch['value']);
}
$strQueryPart2 .= " AND T.expiration_date < NOW() ORDER BY T.expiration_date DESC ";

if($intTerm==1){//수강중
	$strQuery =  " select T1.* from (".$strQueryPart1.") T1 ";
}else if($intTerm==2){//기간만료
	$strQuery =  " select T2.* from (".$strQueryPart2.") T2 ";
}else if($intTerm==3){//신규회원
	$strQuery =  $strQueryPart3;
}else{
	$strQuery =  " select T1.* from (".$strQueryPart1.") T1 UNION ALL select T2.* from (".$strQueryPart3.") T2 UNION ALL select T3.* from (".$strQueryPart2.") T3 ";
}

if($arrPaging){
	$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
}
//print_r($strQuery);
//exit;
?>