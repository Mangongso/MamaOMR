<?php
$strQuery = "select count(*) as cnt from question_group where delete_flg=0";
$strWhere = $this->buildQuestionGroupSearchQuery($arrSearch);
if($strWhere){
	$strQuery = $strQuery.' and '.$strWhere;
}
?>