<?php
$strQuery = sprintf("update record set user_score=%d,modify_date=now(),right_count=%d,wrong_count=%d,testing_time='%s',start_date='%s',end_date='%s' where user_seq=%d and test_seq=%d and testing_time is null",$intUserScore,$intRightCount,$intWrongCount,$testingTime,$intStartDate,$intEndDate,$intMemberSeq,$intTestsSeq);
?>