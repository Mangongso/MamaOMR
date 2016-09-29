<?php
if($intContFlg){
			$strQuery = sprintf("select count(*) as cnt from question_group_list where group_seq=%d",$intGroupSeq);
		}else{
			$strQuery = sprintf("select q.contents as question, qgl.* from question_group_list as qgl left join question as q on qgl.question_seq=q.seq where qgl.group_seq=%d",$intGroupSeq);
		}
?>