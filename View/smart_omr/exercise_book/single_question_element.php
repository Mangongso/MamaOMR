<?
if (! $viewID) {
	$viewID = "GET_SINGLE_QUESTION";
	include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
	$intQuestionSeq = $arr_output ['question_seq'];
	$arrQuestion = $arr_output ['question'] [0];
	$arrQuestionExample = $arrQuestion ['arr_question_example'] ['type_1'];
}
?>
<!--#############################################################################-->
<!--######################### Single Question Element ###########################-->
<!--#############################################################################-->
<!-- ################################################################# -->
<div class="uk-width-1-1 question_div"
	id="question_<?=$intQuestionSeq?>" data-question-seq="<?=$intQuestionSeq?>">
	<input type="hidden" name="question_seq[<?=$intQuestionSeq?>]"
		id="question_seq_<?=$intQuestionSeq?>" value="<?=$intQuestionSeq?>"
		class="question_seq"> <input type="hidden"
		name="answer[<?=$intQuestionSeq?>][seq]"
		id="answer_<?=$intQuestionSeq?>_seq"
		value="<?=$arrQuestion['answer']['seq'];?>" /> <input
		class="order_number" type="hidden"
		name="order_number[<?=$intQuestionSeq?>]"
		id="order_number_<?=$intQuestionSeq?>"
		value="<?=$arrQuestion['order_number']?>"> <input
		class="question_score" type="hidden"
		name="question_score[<?=$intQuestionSeq?>]"
		id="question_score_<?=$intQuestionSeq?>"
		value="1<?//=$arrQuestion['question_score']?>"> <input
		class="question_type" type="hidden"
		data-question-seq="<?=$intQuestionSeq?>"
		name="question_type[<?=$intQuestionSeq?>]"
		id="question_type_<?=$intQuestionSeq?>"
		value="<?=$arrQuestion['question_type']?>">

	<h4 class="uk-width-2-10 pull-left order_number_sub"><?=$arrQuestion['order_number']?></h4>
	<div
		class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrQuestion['question_type'];?>"
		data-toggle="buttons">
						<?
						switch ($arrQuestion ['question_type']) {
							case (2) :
								$intExampleWidth = 3;
								break;
							case (3) :
							case (4) :
								$intExampleWidth = 2;
								break;
							case (11) :
								$intExampleWidth = 5;
								break;
						}
						?>
						<? foreach($arrQuestionExample as $intExampleKey=>$arrExample){ ?>
						<?
							switch ($arrQuestion ['question_type']) {
								case (2) :
									if ($intExampleKey > 2)
										$boolContinueFlg = true;
									break;
								case (3) :
									if ($intExampleKey > 3)
										$boolContinueFlg = true;
									break;
								case (4) :
									if ($intExampleKey > 4)
										$boolContinueFlg = true;
									break;
								case (11) :
									if ($intExampleKey > 1)
										$boolContinueFlg = true;
									break;
							}
							if ($boolContinueFlg) {
								continue;
							}
							?>
						<label
			class="uk-width-<?=$intExampleWidth?>-10 btn btn-default <?=$arrExample['answer_flg']?'active':''?>"
			onclick="$('#answer_<?=$intQuestionSeq?>_seq').val($('#example_<?=$intQuestionSeq?>_<?=$arrExample['seq'];?>').val());">
			<input type="radio" value="<?=$arrExample['seq'];?>"
			id="example_<?=$intQuestionSeq?>_<?=$arrExample['seq'];?>"
			autocomplete="off"><?=$arrExample['example_number'];?>
					  	</label>
					  	<? } ?>
					</div>
	<div class="question_info">
		<div class="uk-width-1-10 uk_pd_init pull-left">
			<button type="button"
				onclick="objRegistration.insertSingleQuestion(<?=$arrQuestion['question_seq'];?>);"
				class="btn btn-default btn-sm uk-width-1-1" title="문제추가">
				<i class="fa fa-plus" aria-hidden="true"></i>
			</button>
		</div>
		<div class="uk-width-1-10 uk_pd_init pull-left"
			style="border-left: none;">
			<button type="button"
				onclick="objRegistration.deleteSingleQuestion(<?=$arrQuestion['question_seq'];?>);"
				class="btn btn-default btn-sm uk-width-1-1" title="문제삭제">
				<i class="fa fa-minus" aria-hidden="true"></i>
			</button>
		</div>
		<div class="uk-width-8-10 answer_tag pull-left">
			<input type="text" class="form-control input-sm"
				name="question_tags[<?=$arrQuestion['question_seq'];?>]"
				id="question_tags<?=$arrQuestion['question_seq'];?>"
				placeholder="유형태그를 콤마(,)로 구분하여 입력하세요"
				value="<?=htmlentities($arrQuestion['tags'],ENT_QUOTES,'UTF-8');?>">
		</div>
	</div>
</div>
<!-- ################################################################# -->