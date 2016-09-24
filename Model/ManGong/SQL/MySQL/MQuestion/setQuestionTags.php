<?php
$strQuery = sprintf("insert into question_tag set question_seq=%d,tag='%s',create_date=now()",$intQuestionSeq,$strQuestionTag);
?>