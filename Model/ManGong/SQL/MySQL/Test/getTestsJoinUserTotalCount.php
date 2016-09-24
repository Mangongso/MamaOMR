<?php
if(count($arrTestsSeq)){
	$strQuery = sprintf("select count(*) as count from test_join_user where test_seq in (".join(',',$arrTestsSeq).") and delete_flg=0");
}else{
	$strQuery = sprintf("select count(*) as count from test_join_user where test_seq=%d and delete_flg=0",$intTestsSeq);
}
?>