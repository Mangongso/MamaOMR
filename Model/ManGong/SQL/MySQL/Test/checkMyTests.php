<?php
$strQuery = sprintf("SELECT *
						FROM test_published
						WHERE delete_flg=0
						AND test_seq=%d
						AND (test_seq IN (SELECT test_seq FROM test_join_user WHERE user_seq=%d and delete_flg=0)
								or group_list_seq IN (SELECT DISTINCT(group_seq) FROM group_user_list WHERE student_seq=%d )) ",$intTestsSeq,$intMemberSeq,$intMemberSeq);
?>