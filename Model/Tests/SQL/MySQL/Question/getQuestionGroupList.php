<?php
$strWhere = $this->buildQuestionGroupSearchQuery($arrSearch);
		$strQuery = "select * from question_group where delete_flg=0";
		if($strWhere){
			$strQuery = $strQuery." AND ".$strWhere;
		}
		$strQuery .= " order by ".$arrOrder['type']." ".$arrOrder['sort'];
		if($arrPaging){
			$strQuery .= sprintf(" limit %d,%d",$arrPaging['limit_start'],$arrPaging['limit_offset']);
		}
?>