<?php
if(is_numeric($mixAnswerSeq)){
	$strQuery = sprintf("select *,md5(seq) answer_key from user_answer where md5(user_seq)='%s' and seq=%d",$strMemberSeq,$mixAnswerSeq);
}else{
	$strQuery = sprintf("select *,md5(seq) answer_key from user_answer where md5(user_seq)='%s' and md5(seq)='%s'",$strMemberSeq,$mixAnswerSeq);
}
?>