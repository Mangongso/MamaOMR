<?php
$strQuery = sprintf("REPLACE INTO tag (tag, type, member_seq, create_date) VALUES ('%s', %d, %d, now())",quote_smart($strTagName),$intTagType,$intMemberSeq);
?>