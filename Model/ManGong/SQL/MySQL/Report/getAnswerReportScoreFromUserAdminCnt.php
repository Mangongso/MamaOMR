<?
include("Model/TechQuiz/SQL/MySQL/Common/commonWhereQuery.php");

$strQuery = sprintf("SELECT count(*) cnt
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
						",$intDeleteFlg);
if(count($arrWhereQuery)){
	$strQuery .= " where ".join(" and ", $arrWhereQuery);
}
?>