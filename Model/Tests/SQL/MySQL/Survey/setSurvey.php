<?php
if(is_null($intTestsSeq) || !$intTestsSeq){
	$strQuery = sprintf("INSERT INTO test 
						SET writer_seq=%d, 
							type=%d, 
							subject='%s', 
							contents='%s', 
							create_date=now(), 
							example_numbering_style=%d,
							modify_date=now(), 
							delete_flg=default,
							tags='%s'",
							$intWriterSeq,
							$intTestsType,
							quote_smart($strSubject),
							quote_smart($strContents),
							$intExampleNumberingStyle,
							quote_smart($strTags)
				);
}else{
	$strQuery = sprintf("UPDATE test 
						SET type=%d, 
							subject='%s', 
							contents='%s', 
							example_numbering_style=%d,
							modify_date=now(),
							tags='%s' 
						WHERE seq=%d",
							$intTestsType,
							quote_smart($strSubject),
							quote_smart($strContents),
							$intExampleNumberingStyle,
							quote_smart($strTags),
							$intTestsSeq
				);
}
?>