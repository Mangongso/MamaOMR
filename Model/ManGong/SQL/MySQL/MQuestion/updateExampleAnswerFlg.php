<?php
// 모든 보기를 answer_flg=0 정답이 아님 상태로 변경
$strQuery1 = sprintf("update question_example set answer_flg=0 where question_seq=%d",$intQuestionSeq);
// 정답인 보기의 answer_flg=1로 변경
if(!$intExampleSeq){
	$strQuery2 = sprintf("update question_example set answer_flg=1 where question_seq=%d and example_number=%d",$intQuestionSeq,$intExampleNumber);
}else{
	$strQuery2 = sprintf("update question_example set answer_flg=1 where question_seq=%d and seq=%d",$intQuestionSeq,$intExampleSeq);
}