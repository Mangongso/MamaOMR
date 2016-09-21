<?php
if($intStatusFlg==2){
	//set test_join_user
	$strQuery = sprintf("insert into test_join_user (user_group_seq,user_seq,test_published_seq,test_seq,test_status_flg,create_date,start_date) values (%d,%d,%d,%d,%d,now(),now())"
				,$intUserGroupSeq,$intMemberSeq,$intPublishSeq,$intTestsSeq,$intStatusFlg);
}else{
	//set test_join_user
	$strQuery = sprintf("insert into test_join_user (user_group_seq,user_seq,test_published_seq,test_seq,test_status_flg,create_date) values (%d,%d,%d,%d,%d,now())"
				,$intUserGroupSeq,$intMemberSeq,$intPublishSeq,$intTestsSeq,$intStatusFlg);
}
?>