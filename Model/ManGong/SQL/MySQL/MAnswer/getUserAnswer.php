<?php
$arrWhere = array();
array_push($arrWhere,sprintf('ua.user_seq=%d',$intMemberSeq));
array_push($arrWhere,sprintf('ua.test_seq=%d',$intTestsSeq));
if($intRecordSeq){
	array_push($arrWhere,sprintf('ua.record_seq=%d',$intRecordSeq));
}else{
	array_push($arrWhere,sprintf('ua.record_seq=(SELECT MIN(record_seq) FROM user_answer WHERE user_seq=%d AND test_seq=%d and delete_flg=0)',$intMemberSeq,$intTestsSeq));
}
if($intQuestionCount){
	$strQuery = sprintf("select ua.*,ql.order_number from user_answer as ua left join test_question_list as ql on ua.test_seq=ql.test_seq and ua.question_seq=ql.question_seq LEFT JOIN question q ON ql.question_seq=q.seq  where ".join(" and ",$arrWhere)." and q.question_type in (".join(",",$arrQuestionType).") order by ql.order_number asc limit 0,%d",$intQuestionCount);
}else{
	$strQuery = sprintf("select ua.*,ql.order_number from user_answer as ua left join test_question_list as ql on ua.test_seq=ql.test_seq and ua.question_seq=ql.question_seq LEFT JOIN question q ON ql.question_seq=q.seq  where ".join(" and ",$arrWhere)." and q.question_type in (".join(",",$arrQuestionType).") order by ql.order_number asc");
}
?>