<?php
if(!$intQuestionSeq){
	$strQuery = sprintf("INSERT INTO question 
						SET writer_seq=%d,
							contents='%s',
							question_type=%d, 
							example_type=%d,
							create_date=now(), 
							modify_date=default, 
							delete_flg=default,
							hidden_flg=%d,
							required_flg=%d",
							$intWriterSeq,
							$strContents,
							$intQuestionType,
							$intExampleType,
							$intHiddenFlg,
							$intRequired
				);
}else{
	$strQuery = sprintf("UPDATE question 
						SET writer_seq=%d,
							contents='%s', 
							question_type=%d, 
							example_type=%d, 
							modify_date=now(),
							hidden_flg=%d,
							required_flg=%d
						WHERE seq=%d",
							$intWriterSeq,
							$strContents,
							$intQuestionType,
							$intExampleType,
							$intHiddenFlg,
							$intRequired,
							$intQuestionSeq
				);
}
?>