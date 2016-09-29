<?php
if($intTagSeq){
	$strQuery = sprintf("delete from test_tags where test_seq=%d and seq",$intTagSeq);
}else{
	$strQuery = sprintf("delete from test_tags where test_seq=%d",$intTestsSeq);
}
?>