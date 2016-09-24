<?php
if(is_null($intQuestionJimoonSeq)){
	$strQuery = sprintf("INSERT INTO question_jimoon
						SET contents='%s',
							create_date=now(), 
							modify_date=default, 
							delete_flg=default",
							$strContents
				);
}else{
	$strQuery = sprintf("UPDATE question_jimoon 
						SET contents='%s',
							modify_date=now(), 
							delete_flg=default 
						WHERE seq=%d",
							$strContents,
							$intQuestionJimoonSeq
				);
}
?>