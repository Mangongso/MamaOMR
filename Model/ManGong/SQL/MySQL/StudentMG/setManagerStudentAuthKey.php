<?php
$strQuery = sprintf("INSERT INTO student_manager set student_seq=%d, auth_key='%s', create_date=now() ",$intStudentSeq,$strAuthKey);
?>