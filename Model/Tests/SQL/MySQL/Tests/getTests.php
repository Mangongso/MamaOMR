<?php
if(!$intWriterSeq){
	$strQuery = sprintf("select * from test where seq=%d",$intTestsSeq);
}else{
	$strQuery = sprintf("select * from test where seq=%d and writer_seq=%d",$intTestsSeq,$intWriterSeq);
}
?>