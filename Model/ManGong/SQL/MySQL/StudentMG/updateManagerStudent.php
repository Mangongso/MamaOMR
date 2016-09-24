<?php
$strQuery = sprintf("update student_manager set manager_seq=%d, modify_date=now() where md5(student_seq)='%s' and auth_key='%s' ",$intManagerSeq,$strStudentSeq,$strAuthKey);
?>