<?php
$strQuery = sprintf("select *,md5(student_seq) as student_key from student_manager where delete_flg=0 and md5(manager_seq)='%s'",$strManagerSeq);
		if(!is_null($strStudentSeq)){
			$strQuery .= sprintf(" and md5(manager_seq)='%s'",$strStudentSeq);
		}
?>