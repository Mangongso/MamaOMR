<?php
$arrWhere = array();
		if($intTestsSeq){
			array_push($arrWhere,sprintf('test_seq=%d',$intTestsSeq));
		}
		if($intPublishedSeq){
			array_push($arrWhere,sprintf('published_seq=%d',$intPublishedSeq));
		}
		$strQuery = sprintf("select count(distinct(user_email)) as cnt from user_answer where question_seq=%d",$intQuestionSeq);
		if(count($arrWhere)>0){
			$strQuery = $strQuery." and ".join(" and ",$arrWhere);
		}
?>