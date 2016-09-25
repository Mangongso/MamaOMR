<?php
$strQuery = sprintf("UPDATE question_jimoon SET delete_flg=1,modify_date=now() WHERE seq=%d",$intQuestionJimoonSeq);
?>