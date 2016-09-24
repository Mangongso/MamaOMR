<?php
$strQuery = "INSERT INTO question_example (question_seq, example_type, contents, create_date, modify_date, answer_flg, delete_flg, example_number) values ";

switch($strExampleType){
	case(1):
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,1);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,2);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,3);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,4);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d)",$intQuestionSeq,$strExampleType,$strContents,5);
		break;
	case(2):
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,1);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,2);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,3);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d)",$intQuestionSeq,$strExampleType,$strContents,4);
		break;
	default:
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,1);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,2);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,3);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d),",$intQuestionSeq,$strExampleType,$strContents,4);
		$strQuery .= sprintf("(%d,%d,'%s',now(),default,0,default,%d)",$intQuestionSeq,$strExampleType,$strContents,5);
		break;
}
?>	
