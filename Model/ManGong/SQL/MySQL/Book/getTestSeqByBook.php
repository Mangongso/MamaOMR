<?php
$strQuery = sprintf("SELECT GROUP_CONCAT(s.seq) as group_seq
					FROM test s, test_published sp 
					WHERE s.seq=sp.test_seq AND s.delete_flg=0 AND sp.delete_flg=0 AND book_seq=%d ",$intBookSeq);
?>