<?php
$strQuery = "select * from member_basic_info A left outer join member_extend_info B on A.member_seq = B.member_seq where member_type='T' and A.member_seq=".$intTeacherSeq;
?>