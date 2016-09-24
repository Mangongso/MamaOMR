<?php
$strQuery = sprintf("update wrong_note set note_title='%s' where user_seq=%d and seq=%d",$strNoteTitle,$intUserSeq,$intWrongNoteSeq);
?>