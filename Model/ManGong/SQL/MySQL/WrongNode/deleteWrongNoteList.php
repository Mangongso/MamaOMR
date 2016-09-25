<?php
$strQuery = sprintf("update wrong_note_list set delete_flg=1 where user_seq=%d and seq=%d",$intUserSeq,$intWrongNoteListSeq);
?>