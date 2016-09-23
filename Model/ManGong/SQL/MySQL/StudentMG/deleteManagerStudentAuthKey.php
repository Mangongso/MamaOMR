<?php
$strQuery = sprintf("update student_manager set auth_key='', modify_date=now() where md5(student_seq)='%s' and auth_key='%s' ",$strStudentSeq,$strAuthKey);
?>