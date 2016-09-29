<?php
$strQuery = sprintf("INSERT INTO test_join_user (test_seq, user_group_seq, user_seq, start_date, end_date, test_user_status_flg, delete_flg)
					  				VALUES (%d, %d, %d, '%s', '%s', default, default)",$intTestsSeq,$intUserGroupSeq,$intUserSeq,$strStartDate,$strEndDate);
?>