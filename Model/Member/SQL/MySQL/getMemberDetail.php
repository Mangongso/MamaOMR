<?php
if(is_array($arr_input[column])){
	$query = sprintf("SELECT %s FROM member_basic_info where member_seq = %d ",$arr_input[column],$member_seq);
}else{
	$query = sprintf("SELECT R1.*,R2.cphone,R2.address1,R2.address2,R2.zcode1,R2.zcode2 FROM member_basic_info as R1 left join member_extend_info as R2 on R1.member_seq = R2.member_seq where R1.member_seq = %d ",$member_seq);
}

?>