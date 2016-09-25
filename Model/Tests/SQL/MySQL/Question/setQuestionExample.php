<?php
if(is_null($intQuestionExampleSeq)){
	$strQuery = sprintf("INSERT INTO question_example
						SET question_seq=%d,
							example_type=%d, 
							contents='%s',
							create_date=now(), 
							modify_date=default, 
							answer_flg=%d,
							goto_question_seq=%d,
							delete_flg=default",
							$intQuestionSeq,
							$strExampleType,
							$strContents,
							$intAnswerFlg,
							$intGotoQuestionSeq
				);
}else{
	$strQuery = sprintf("UPDATE question_example  
						SET question_seq=%d, 
							example_type=%d,
							contents='%s',
							modify_date=now(), 
							answer_flg=%d,
							goto_question_seq=%d
						WHERE seq=%d",
							$intQuestionSeq,
							$strExampleType,
							$strContents,
							$intAnswerFlg,
							$intGotoQuestionSeq,
							$intQuestionExampleSeq
				);
}
?>	