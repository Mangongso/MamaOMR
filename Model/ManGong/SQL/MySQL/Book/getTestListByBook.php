<?php
$strQuery = sprintf("SELECT * 
					FROM test s, test_published sp 
					WHERE s.seq=sp.test_seq AND s.delete_flg=0 AND sp.delete_flg=0 AND MD5(book_seq)='%s' ",$strBookSeq);
//print_r($strQuery);
?>