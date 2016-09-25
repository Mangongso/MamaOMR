<?php
$strQuery = sprintf("insert into wrong_note (user_seq,note_title,create_date,last_update_date,delete_flg) values (%d,'%s',now(),now(),0)",$intUserSeq,quote_smart($strNoteTitle));
?>