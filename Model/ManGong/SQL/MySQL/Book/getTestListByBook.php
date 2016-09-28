<?php
$strQuery = sprintf("SELECT *,(SELECT MAX(question_type)  FROM question WHERE seq IN (SELECT question_seq FROM test_question_list WHERE test_seq=s.seq)) AS question_type
					FROM test s, test_published sp 
					WHERE s.seq=sp.test_seq AND s.delete_flg=0 AND sp.delete_flg=0 AND MD5(book_seq)='%s' ",$strBookSeq);
?>