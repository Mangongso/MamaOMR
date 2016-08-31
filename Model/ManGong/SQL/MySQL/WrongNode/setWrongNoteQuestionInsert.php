<?php
$strQuery = sprintf("insert into wrong_note_list (
wrong_note_seq,user_seq,record_seq,test_seq,question_seq,user_answer,create_date,delete_flg,file_name,question) 
values 
(%d,%d,%d,%d,%d,%d,now(),0,'%s','%s')",
$intNoteSeq,$intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq,$intUserAnswer,$strWrongNoteFileName,$strQuestion	
);
?>