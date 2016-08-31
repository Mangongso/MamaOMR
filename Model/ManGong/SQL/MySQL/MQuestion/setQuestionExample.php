<?php
if(is_null($intQuestionExampleSeq)){
	$strQuery = sprintf("INSERT INTO question_example
						SET question_seq=%d,
							example_type=%d, 
							contents='%s',
							create_date=now(), 
							modify_date=default, 
							answer_flg=%d,
							delete_flg=%d,
							example_number=%d,
							subjective_answer='%s'",	
							$intQuestionSeq,
							$strExampleType,
							quote_smart($strContents),
							$intAnswerFlg,
							$intDeleteFlg,
							$intExampleNumber,
							quote_smart($strSubjectiveAnswer)
				);
}else{
	$strQuery = sprintf("UPDATE question_example 
						SET question_seq=%d, 
							example_type=%d,
							example_number=%d,
							delete_flg=%d, ",
							$intQuestionSeq,
							$strExampleType,
							$intExampleNumber,
							$intDeleteFlg
				);
	if(!is_null($strContents)){
		$strQuery .= sprintf(" contents='%s', ",quote_smart(trim($strContents)));
	}
	if(!is_null($strSubjectiveAnswer)){
		$strQuery .= sprintf(" subjective_answer='%s', ",quote_smart($strSubjectiveAnswer));
	}
	$strQuery .= sprintf(" modify_date=now() WHERE seq=%d ",$intQuestionExampleSeq);
}
//print_r($strQuery);
//exit;
?>	