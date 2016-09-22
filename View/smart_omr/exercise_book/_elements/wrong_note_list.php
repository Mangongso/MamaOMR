<?php
if(!$viewID){
	$viewID = "SOMR_EXERCISE_BOOK_TEST_RESULT";
	include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
}
?>
<? foreach($arr_output['wrong_answer'] as $intKey=>$arrWrongAnswer){ ?>
<div
	class="uk-width-1-1 wrong_answer <?=$arrWrongAnswer['result_flg']?'test_right_answer':'test_wrong_answer'?>"
	id="question_<?=$arrWrongAnswer['question_seq']?>"
	question_seq="<?=$arrWrongAnswer['question_seq']?>">
	<h4 class="uk-width-2-10 pull-left"><?=$arrWrongAnswer['order_number']?></h4>
	<div
		class="uk-width-8-10 btn-group text-right ans_correct_<?=$arrWrongAnswer['question_type'];?>"
		data-toggle="buttons">
						<? if(!$arrWrongAnswer['wrong_note_list_seq']){ ?>
						<button type="button" class="pure-button pure-form_in"
			data-modal-type="editor"
			data-wrong-answer="<?=$arrWrongAnswer['seq'];?>"><i class="fa fa-arrow-up" aria-hidden="true"></i> 오답문제 입력</button>
						<? }else{ ?>
						<div class="uk-float-left date_posted">등록/<?=$arrWrongAnswer['wrong_note_date'];?></div>
		<div class="uk-float-right">
			<button type="button" class="pure-button pure-form_in" data-modal-type="editor"
				data-wrong-answer="<?=$arrWrongAnswer['seq'];?>"><i class="fa fa-undo" aria-hidden="true"></i> 수정</button>
		</div>
						<? } ?>
					</div>
</div>
<? } ?>		