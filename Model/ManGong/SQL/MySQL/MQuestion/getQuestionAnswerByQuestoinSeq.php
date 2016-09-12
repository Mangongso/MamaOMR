<?php
$strQuery = sprintf("SELECT * FROM question_example WHERE answer_flg=1 AND delete_flg=0 AND question_seq=%d",$intQuestionSeq);
?>