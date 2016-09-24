<?php
if(!$intWriterSeq){
	if(is_numeric($mixTestsSeq)){
		$strQuery = sprintf("select * from test where seq=%d",$mixTestsSeq);
	}else{
		$strQuery = sprintf("select * from test where md5(seq)='%s'",$mixTestsSeq);
	}
}else{
	if(is_numeric($mixTestsSeq)){
		$strQuery = sprintf("select * from test where seq=%d and (writer_seq=%d or sub_master=%d) ",$mixTestsSeq,$intWriterSeq,$intWriterSeq);
	}else{
		$strQuery = sprintf("select * from test where md5(seq)='%s' and (writer_seq=%d or sub_master=%d) ",$mixTestsSeq,$intWriterSeq,$intWriterSeq);
	}
}
?>