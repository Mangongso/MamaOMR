<?php
if(is_null($intQuestionExampleSeq)){
	$strQuery = sprintf("INSERT INTO question_example_type1
						SET question_seq=%d, 
							contents='%s',
							create_date=now(), 
							modify_date=default, 
							answer_flg=0,
							delete_flg=default",
							$intQuestionSeq,
							$strContents,
							$intAnswerFlg
				);
}else{
	$strQuery = sprintf("UPDATE question_example_type1 
						SET question_seq=%d, 
							contents='%s',
							modify_date=now(), 
							answer_flg=%d
						WHERE seq=%d",
							$intQuestionSeq,
							$strContents,
							$intAnswerFlg,
							$intQuestionExampleSeq
				);
}
?>