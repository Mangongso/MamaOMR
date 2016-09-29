<?php
$arrValues = array();
		foreach($arrQuestionSeq as $intKey=>$intQuestionSeq){
			array_push($arrValues,sprintf("(%d,%d,now())",$intGroupSeq,$intQuestionSeq));
		}
		$strQuery = "insert into question_group_list (group_seq,question_seq,create_date) values ".join(',',$arrValues);
?>