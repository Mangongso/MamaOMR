<?php
$strQuery = sprintf("SELECT *,wn.seq as wrong_note_list_seq FROM wrong_note_list wn, test su WHERE wn.test_seq=su.seq AND wn.delete_flg=0 AND wn.user_seq=%d AND su.delete_flg=0 ",$intUserSeq);
		if($mixTeacherSeq && $boolMD5){
			$strQuery .= sprintf(" AND md5(su.writer_seq)='%s' ",$mixTeacherSeq);
		}else if($mixTeacherSeq && !$boolMD5){
			$strQuery .= sprintf(" AND su.writer_seq=%d ",$mixTeacherSeq);
		}
		if(count($arrSearch)>0){
			if(array_key_exists('note', $arrSearch)){
				$strQuery .= " AND (wn.note like '%".$arrSearch['note']."%' or su.subject like '%".$arrSearch['subject']."%') ";
			}
			if(array_key_exists('record_seq', $arrSearch)){
				$strQuery .= sprintf(" AND md5(wn.record_seq)='%s' ",$arrSearch['record_seq']);
			}
		}
		$strQuery .= sprintf(" order by wn.create_date desc ");
	
		if($arrPaging){
			$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
		}
?>