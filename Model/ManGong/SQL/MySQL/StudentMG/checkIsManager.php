<?php
$strQuery = sprintf("select count(*) as cnt from student_manager where md5(manager_seq)='%s' and md5(student_seq)='%s'",$strManagerKey,$strStudentKey);
?>