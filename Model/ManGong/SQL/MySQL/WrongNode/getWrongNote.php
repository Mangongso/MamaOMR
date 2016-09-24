<?php
if($intNoteSeq){
	$strQuery = sprintf("select * from wrong_note where user_seq=%d and seq=%d",$intUserSeq,$intNoteSeq);
}else{
	$strQuery = sprintf("select * from wrong_note where user_seq=%d ",$intUserSeq);
}
?>