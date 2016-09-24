<?
$arrWhere = array();
array_push($arrWhere, sprintf(" seq=%d ",$intQuestonSeq));

$arrSetQuery = array();
$arrValue = array();
foreach($arr_input as $mixKey=>$mixValue){
	if(is_numeric($mixResult)){
		array_push($arrSetQuery,sprintf("%s=%d",$mixKey,$mixValue));
	}else{
		array_push($arrSetQuery,sprintf("%s='%s'",$mixKey,quote_smart($mixValue)));
	}
}
array_push($arrSetQuery,"modify_date=now()");

$strSetQuery = join(',',$arrSetQuery);

$strQuery = sprintf("update question set %s ",$strSetQuery);
$strQuery .= " where ".join(' and ',$arrWhere);
//print_r($strQuery);
//exit;
?>