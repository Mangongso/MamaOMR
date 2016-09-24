<?php
$strQuery = sprintf(
				"insert into user_answer_discus set user_answer_seq=%d,test_seq=%d,record_seq=%d,question_seq=%d,user_seq=%d,discus_answer='%s'",
				$intUserAnswerSeq,
				$intTestSeq,
				$intRecordSeq,
				$intQuestionSeq,
				$intMemberSeq,
				quote_smart($strUserAnswer));
?>