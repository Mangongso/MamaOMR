<?php
$strQuery = sprintf("update question_example set delete_flg=1 where question_seq=%d and seq not in (%s)",$intQuestionSeq,join(",",$arrExceptExampleSeq));
?>