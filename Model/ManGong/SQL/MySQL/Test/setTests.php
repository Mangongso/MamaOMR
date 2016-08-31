<?php
if(is_null($intTestsSeq) || !$intTestsSeq){
	if($intSubMasterSeq){
		$strAddQuery = sprintf(" ,sub_master=%d ",$intSubMasterSeq);
	}else{
		$strAddQuery = "";
	}
	$strQuery = sprintf("INSERT INTO test 
						SET writer_seq=%d, 
							type=%d, 
							subject='%s', 
							contents='%s', 
							create_date=now(), 
							example_numbering_style=%d,
							modify_date=now(), 
							delete_flg=default,
							tags='%s'".$strAddQuery,
							$intWriterSeq,
							$intTestsType,
							quote_smart($strSubject),
							quote_smart($strContents),
							$intExampleNumberingStyle,
							quote_smart($strTags)
				);
}else{
	/*
	 * 1.$intMasterSeq 가 null인 경우는 마스터가 없는경우 - submaster null
	 * 2.$intMasterSeq 가 null이 아닐 경우는 마스터가 있으면서 선택햇을경우 - submaster = 01920102  
	 * 3.$intMasterSeq 가 ""인 경우는 마스터있으면서 선택하지 않앗을경우 - - submaster = ''
	 * */
	if(!is_null($intSubMasterSeq)){
		$strAddQuery = sprintf(" ,writer_seq=%d ,sub_master=%d ",$intWriterSeq, $intSubMasterSeq);
	}else{
		$strAddQuery = "";
	}
	$strQuery = sprintf("UPDATE test 
						SET type=%d, 
							subject='%s', 
							contents='%s', 
							example_numbering_style=%d,
							modify_date=now(),
							tags='%s'".$strAddQuery." 
						WHERE seq=%d",
							$intTestsType,
							quote_smart($strSubject),
							quote_smart($strContents),
							$intExampleNumberingStyle,
							quote_smart($strTags),
							$intTestsSeq
				);
}
//print_r($strQuery);
?>