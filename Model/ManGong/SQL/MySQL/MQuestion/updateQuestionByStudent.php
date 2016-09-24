<?php
$strQuery = sprintf("update question set contents='%s' where seq=%d",quote_smart(trim($strContents)),$intQuestionSeq);
?>