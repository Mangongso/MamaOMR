<?php
$strQuery = sprintf("select * from student_manager where delete_flg=0 and md5(manager_seq)='%s'",$strManagerSeq);
		if(!is_null($strStudentSeq)){
			$strQuery .= sprintf(" and md5(manager_seq)='%s'",$strStudentSeq);
		}
?>