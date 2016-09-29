<?php
$arrValues = array();
foreach($arrTags as $intKey=>$strTags){
	if(trim($strTags)){
		array_push($arrValues,sprintf("(%d,'%s',now())",$intTestsSeq,$strTags));
	}
}
$strQuery = "insert into test_tags (test_seq,tag,create_date) values ".join(',',$arrValues);
?>