<?php
if($intContFlg==0){
		$strQuery = sprintf("select *,(select max(seq) from test_published where delete_flg=0 and test_seq=s.seq) as test_published_seq from test as s
				where seq in (
				select test_seq from test_question_list where question_seq in (
				select question_seq from question_group_list where group_seq=%d
		)
		)",$intGroupSeq);
		}else{
		$strQuery = sprintf("select count(*) as cnt from test
				where seq in (
				select test_seq from test_question_list where question_seq in (
				select question_seq from question_group_list where group_seq=%d
		)
		)",$intGroupSeq);
		}
?>