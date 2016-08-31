<?php
$strQuery = sprintf("SELECT bi.*,GROUP_CONCAT(ssp.test_seq) AS group_test_seq ,COUNT(ssp.test_seq) AS test_join_cnt FROM book_info bi, 
	(SELECT sp.* FROM test s ,test_published sp 
  WHERE s.seq=sp.test_seq AND s.delete_flg=0  
  AND s.seq IN (SELECT DISTINCT(test_seq) FROM test_join_user 
	WHERE md5(user_seq)='%s' AND delete_flg=0) 
  AND sp.book_seq>0) ssp
  WHERE bi.seq=ssp.book_seq GROUP BY ssp.book_seq
",$strMemberSeq);
// print_r($strQuery);exit;
?>