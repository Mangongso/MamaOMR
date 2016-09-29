<?php
$strQuery = sprintf("update user_answer set delete_flg=1 where user_email='%s' and test_seq=%d",$strUserEmail, $intTestsSeq);
if($intPublishedSeq){$strQuery = $strQuery.sprintf(" and published_seq=%d",$intPublishedSeq);}
?>