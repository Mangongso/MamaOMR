<?php
$strQuery = sprintf("INSERT INTO question_tag 
					SET question_seq=%d, 
					tag_name='%s',
					create_date=now(), 
					modify_date=default, 
					delete_flg=default",
					$intQuestionSeq,
					$strTagName
			);
?>