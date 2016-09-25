<?php
$strQuery = sprintf("
		INSERT INTO user_answer
		(user_email,test_seq,published_seq,question_seq,example_seq,matrix_example_seq,user_answer,create_date,answer_type)
		VALUES
		('%s',%d,%d,%d,%d,%d,'%s',now(),%d)
		",$strUserEmail,$intTestsSeq,$intPublishedSeq,$intQuestionSeq,$intUserAnswerExampleSeq,$intUserAnswerMatrixExampleSeq,$strUserAnswer,$intAnswerType);
?>