<?php
if(is_numeric($strMemberSeq)){
	$strQuery = sprintf("select *,md5(seq) as wrong_note_key from wrong_note_list where user_seq=%d and record_seq=%d and test_seq=%d and question_seq=%d",$strMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq);
}else{
	$strQuery = sprintf("select *,md5(seq) as wrong_note_key from wrong_note_list where md5(user_seq)='%s' and record_seq=%d and test_seq=%d and question_seq=%d",$strMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq);
}
?>