<?php
$strQuery = sprintf("update test set tags='%s' where seq=%d ",quote_smart($strTags),$intTestsSeq);
?>