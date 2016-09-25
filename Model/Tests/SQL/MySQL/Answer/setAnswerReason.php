<?php
if(is_null($intReasonSeq)){
	$strQuery = sprintf("insert into user_answer_reason set answer_seq=%d, reason='%s'",$intAnswerSeq,$strReason);
}else{
	$strQuery = sprintf("update user_answer_reason set reason='%s' where seq=%d",$intReasonSeq);
}
?>