<?php
$strQuery = sprintf(
"INSERT INTO record (user_seq,revision,test_seq,user_name,sex,user_score,total_score,create_date,right_count,wrong_count)
SELECT %d,IFNULL(MAX(revision)+1 ,1),%d,'%s','%s',%d,%d,now(),%d,%d FROM record WHERE user_seq=%d AND test_seq=%d",
$intMemberSeq,$intTestsSeq,$strUserName,$strSex,$intUserScore,$intTotalScore,$intRightCount,$intWrongCount,$intMemberSeq,$intTestsSeq);
?>