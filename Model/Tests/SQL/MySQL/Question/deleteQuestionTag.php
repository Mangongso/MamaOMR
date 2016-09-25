<?php
$strQuery = sprintf("UPDATE question_tag SET delete_flg=1,modify_date=now() WHERE seq=%d",$intQuestionTagSeq);
?>