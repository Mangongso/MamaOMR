<?php
$strQuery = sprintf("INSERT INTO question_history  (question_seq,writer_seq,contents,question_type,example_type,create_date,delete_flg,hint,commentary,tags,file_name) SELECT seq,writer_seq,contents,question_type,example_type,NOW(),delete_flg,hint,commentary,tags,file_name FROM question WHERE seq=%d",$intQuestionSeq);
?>