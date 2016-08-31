<?php
$strQuery = sprintf("
update wrong_note_list set 
wrong_note_seq=%d,
user_seq=%d,
record_seq=%d,
test_seq=%d,
question_seq=%d,
user_answer=%d,
file_name='%s',
question='%s'
where seq=%d",
$intNoteSeq,$intMemberSeq,$intRecordSeq,$intTestSeq,$intQuestionSeq,$intUserAnswer,$strWrongNoteFileName,$strQuestion,$arrWrongNote[0]['seq']
);
?>