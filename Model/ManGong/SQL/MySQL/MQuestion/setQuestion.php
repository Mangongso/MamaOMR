<?php
if(is_null($intQuestionSeq)){
	$strQuery = sprintf("INSERT INTO question 
						SET writer_seq=%d,
							contents='%s',
							question_type=%d, 
							example_type=%d,
							hint='%s',
							commentary='%s',
							create_date=now(), 
							modify_date=default, 
							delete_flg=default,
							tags='%s',
							file_name='%s'",
							$intWriterSeq,
							quote_smart(trim($strContents)),
							$intQuestionType,
							$intExampleType,
							quote_smart($strQuestionHint),
							quote_smart($strQuestionCommentary),
							quote_smart($strTags),
							$strFileName
				);
}else{
	$strQuery = sprintf("UPDATE question 
						SET writer_seq=%d,
							question_type=%d, 
							example_type=%d, ",
							$intWriterSeq,
							$intQuestionType,
							$intExampleType
				);
	if(!is_null($strContents)){
		$strQuery .= sprintf(" contents='%s', ",quote_smart(trim($strContents)));
	}
	if(!is_null($strQuestionHint)){
		$strQuery .= sprintf(" hint='%s', ",quote_smart($strQuestionHint));
	}
	if(!is_null($strQuestionCommentary)){
		$strQuery .= sprintf(" commentary='%s', ",quote_smart($strQuestionCommentary));
	}
	if(!is_null($strTags)){
		$strQuery .= sprintf(" tags='%s', ",quote_smart($strTags));
	}
	if(!is_null($strFileName)){
		$strQuery .= sprintf(" file_name='%s', ",quote_smart($strFileName));
	}	
	$strQuery .= sprintf(" modify_date=now() WHERE seq=%d ",$intQuestionSeq);
}
//print_r($strQuery);
//exit;
?>