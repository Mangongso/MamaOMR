<?php
$strQuery = sprintf("SELECT count(*) as cnt FROM wrong_note_list wn, test su WHERE wn.test_seq=su.seq AND wn.delete_flg=0 AND wn.user_seq=%d ",$intUserSeq);
if($mixTeacherSeq && $boolMD5){
	$strQuery .= sprintf(" AND md5(su.writer_seq)='%s' ",$mixTeacherSeq);
}else if($mixTeacherSeq && !$boolMD5){
	$strQuery .= sprintf(" AND su.writer_seq=%d ",$mixTeacherSeq);
}
if(count($arrSearch)>0){
	$strQuery .= " AND (wn.note like '%".$arrSearch['note']."%' or su.subject like '%".$arrSearch['subject']."%') ";
}
?>