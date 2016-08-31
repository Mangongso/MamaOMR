<?
include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");

$strQuery = sprintf("SELECT R.test_seq,
							R.user_name,
							R.correct,
							R.incorrect/(R.correct+R.incorrect)*100 AS incorrect_repcent ,
							R.correct+R.incorrect AS total_cnt,
							R.correct/(R.correct+R.incorrect)*100 AS correct_repcent ,
							R.test_cnt,
							R.member_id,
							R.name,
							R.nickname,
							R.email,
							R.company,
							R.dept,
							R.join_type,
							R.position
						FROM ( SELECT * FROM 
							(SELECT test_seq,
								SUM(right_count)AS correct,
								SUM(wrong_count) AS incorrect,
								COUNT(test_seq) AS test_cnt,
								user_name,
								user_seq,
								DATE_FORMAT(MAX(start_date),'%%Y-%%m-%%d') AS start_date
							   FROM record r,test s WHERE r.test_seq=s.seq AND revision=1 AND s.delete_flg=0 GROUP BY user_seq) rc,
							(SELECT mb.member_seq,member_id,join_type,name,nickname,email,company,dept,position FROM member_basic_info mb,member_extend_info me WHERE mb.member_seq=me.member_seq and del_flg='0') m
							WHERE rc.user_seq=m.member_seq) AS R 
						");
if(count($arrWhereQuery)){
	$strQuery .= " where ".join(" and ", $arrWhereQuery);
}

if(count($arrOrder)){
	$cnt = 0;
	foreach($arrOrder as $strColumn=>$strOrder){
		if($cnt==0){
			$strQuery .= sprintf(" ORDER BY %s %s",$strColumn,$strOrder);
			$cnt++;
		}else{
			$strQuery .= sprintf(" , %s %s",$strColumn,$strOrder);
		}
	}
}else{
	$strQuery .= " ORDER BY test_cnt DESC,
							correct DESC,
							incorrect ";
}
?>