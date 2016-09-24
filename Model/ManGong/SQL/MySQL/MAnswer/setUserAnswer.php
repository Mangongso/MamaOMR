<?php
$strQuery = sprintf("
INSERT INTO user_answer
(user_seq,test_seq,question_seq,question_answer,user_answer,result_flg,create_date,user_name,sex,score,record_seq)
select %d,%d,%d,'%s','%s',%d,now(),'%s','%s',%d,seq from record where test_seq=%d and user_seq=%d and testing_time is null
",$intMemberSeq,$intTestsSeq,$intQuestionSeq,$strQuestionAnswer,quote_smart($userAnswer),$result_flg,$strUserName,$sex,$intScore,$intTestsSeq,$intMemberSeq);
?>