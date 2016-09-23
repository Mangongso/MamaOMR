<?php
if(!$viewID){
	$viewID = "SOMR_EXERCISE_BOOK_TEST_RESULT";
	include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
}
?>
<script>
var arrAnswerSeq = <?php echo json_encode($arr_output['question_answer']) ?>;
console.log(arrAnswerSeq);
//var intAnswerCheckFlg = <? //echo $arr_output['test'][0]['publish'][0]['answer_check_flg'];?>;
</script>
<? 
/* echo "<pre>";
var_dump($arr_output['wrong_answer']);
echo "</pre>";
exit; */
?>
				<? $intIndex = 0;?>
				<? if(count($arr_output['wrong_answer'])){ ?>
				<? foreach($arr_output['wrong_answer'] as $intKey=>$arrWrongAnswer){ ?>
				<? $arrWrongQuestionDetail = $arr_output['wrong_questions'][$arrWrongAnswer['question_seq']];?>
				<div class="quiz-question"  question_seq="<?=$arrWrongAnswer['question_seq']?>" style="display:<?=$intIndex==0?'block':'none';?>">
				<? if($arrWrongAnswer['file_name']){?>
				<img id="question_img" src="../_images/question.php?b=<?=$arr_output['book_seq'];?>&t=<?=$arrWrongAnswer['test_seq']?>&q=<?=$arrWrongAnswer['question_seq']?>&f=<?=$arrWrongAnswer['file_name']?>" data-img_mode="real" alt=" " />
				<? }else if($arrWrongQuestionDetail['file_name']){?>
				<img id="question_img" src="../_images/question.php?b=<?=$arr_output['book_seq'];?>&t=<?=$arrWrongAnswer['test_seq']?>&q=<?=$arrWrongAnswer['question_seq']?>&f=<?=$arrWrongQuestionDetail['file_name']?>" data-img_mode="real" alt=" " />
				<? }else{ ?>
				<img id="question_img" src="../_images/default_wt_cover.png" style="width:100%;height:250px;" alt=" " />
				<? } ?>
				
				<? if(trim($arrWrongAnswer['question_contents'])!=''){ ?>
				<div class="wn-q-answer"><?=$arrWrongAnswer['order_number']?>. <?=nl2br($arrWrongAnswer['question_contents'])?></div>
				<? }else if(trim($arrWrongQuestionDetail['contents'])!=''){?>
				<div class="wn-q-answer"><?=$arrWrongAnswer['order_number']?>. <?=nl2br($arrWrongQuestionDetail['contents'])?></div>
				<? }else{ ?>
				<div class="h_dot">
				<div class="h_dot_box test_score"><?=$arrWrongAnswer['order_number']?>. 등록된 내용이 없습니다.</div></div>
				<? } ?>
				<div class="uk-width-1-1 user_answer_radio" id="question_<?=$arrWrongAnswer['question_seq']?>" question_seq="<?=$arrWrongAnswer['question_seq']?>" >
					<h4 class="uk-width-2-10 pull-left" style="padding-top: 6px;">
					<?=$arrWrongAnswer['order_number']?>
					</h4>
					<div class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrWrongQuestionDetail['question_type'];?>" data-toggle="buttons">
						<?
							switch($arrWrongQuestionDetail['question_type']){
								case(2):
									$intExampleWidth = 3;
								break;
								case(3):
								case(4):
									$intExampleWidth = 2;
								break;
								case(11):
									$intExampleWidth = 5;
								break;
							}
						?>
						<? foreach($arrWrongQuestionDetail['example']['type_1'] as $intExampleKey=>$arrExample){ ?>
						<label class="uk-width-<?=$intExampleWidth?>-10 btn btn-default" for="example_<?=$arrWrongAnswer['question_seq']?>_<?=$arrExample['seq'];?>">
					    <input type="radio" value="<?=$arrExample['seq'];?>" id="example_<?=$arrWrongAnswer['question_seq']?>_<?=$arrExample['seq'];?>" autocomplete="off"><?=$arrExample['example_number'];?>
					  	</label>
					  	<? } ?>
					</div>
				</div>
				</div>
				<? $intIndex++; ?>
				<? } ?>
				<? }else{ ?>
				<div class="h_dot">
				<div class="h_dot_box test_score">
					틀린 문제가 없습니다.
				</div></div>
				<? } ?>
