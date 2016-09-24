<?php
if(is_array($mixQuestionTag)){
	$strQuery = sprintf("update question set tags='%s' where seq=%d",join(',',$mixQuestionTag),$intQuestoinSeq);
}else{
	$strQuery = sprintf("update question set tags='%s' where seq=%d",$mixQuestionTag,$intQuestoinSeq);
}
?>