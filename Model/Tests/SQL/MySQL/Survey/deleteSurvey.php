<?php
$strQuery = sprintf("UPDATE test SET delete_flg=1,modify_date=now() WHERE seq=%d",$intTestsSeq);
?>