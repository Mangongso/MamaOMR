<?php
if($intContFlg==0){
			$strColumn = "*";
		}else{
			$strColumn = "count(*) as cnt";
		}
		$strQuery = sprintf("select ".$strColumn." from idg_related_contents 
					 where test_seq in (
					 	select test_seq from test_question_list where question_seq in (
					 		select question_seq from question_group_list where group_seq=%d
					 	)
					 )",$intGroupSeq);
?>