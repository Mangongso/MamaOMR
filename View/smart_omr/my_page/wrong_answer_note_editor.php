<?php
$viewID = "SOMR_GET_WRONG_ANSWER";
include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
?>
<?php

$strWrongNoteContents = $arr_output ['wrong_note'] [0] ['question'] ? $arr_output ['wrong_note'] [0] ['question'] : $arr_output ['question'] [0] ['contents'];
$strWrongNoteFileName = $arr_output ['wrong_note'] [0] ['file_name'] ? $arr_output ['wrong_note'] [0] ['file_name'] : $arr_output ['question'] [0] ['file_name'];
?>
<!--##############################################################################-->
<!--######################### Wrong Answer Note Editor ###########################-->
<!--##############################################################################-->
<!-- ########################## -->
<form id="frm_wrong_note">
	<input type="hidden" name="answer_key" id="answer_key"
		value="<?=$arr_output['answer'][0]['answer_key']?>" /> <input
		type="hidden" name="wrong_note_key" id="wrong_note_key"
		value="<?=$arr_output['wrong_note'][0]['wrong_note_key']?>" /> <input
		type="hidden" name="wrong_note_file_name" id="wrong_note_file_name"
		value="" /> <input type="hidden" name="wrong_note_upload_key"
		id="wrong_note_upload_key" value="" />
<? if($arr_output['wrong_note'][0]['file_name']){?>
<img class="question_img"
		src="../_images/question.php?b=<?=$arr_output['book_seq'];?>&t=<?=$arr_output['wrong_note'][0]['test_seq']?>&q=<?=$arr_output['wrong_note'][0]['question_seq']?>&f=<?=$arr_output['wrong_note'][0]['file_name'];?>"
		data-img_mode="real" />
<? }else if($strWrongNoteFileName){ ?>
<img class="question_img"
		src="../_images/question.php?b=<?=$arr_output['book_seq'];?>&t=<?=$arr_output['test_seq']?>&q=<?=$arr_output['question'][0]['seq']?>&f=<?=$strWrongNoteFileName;?>"
		data-img_mode="real" />
<? }else{?>
<img class="question_img" src="../_images/default_wt_cover.png"
		style="width: 100%; height: 250px;" data-img_mode=""/>
<? } ?>
	<? if($arr_output['editble']){ ?>
<p>
		<button id="btn_upload" type="button"
			class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt">
			<i class="fa fa-picture-o" aria-hidden="true"></i> 사진 등록
		</button>
		<button id="btn_ocr" type="button"
			class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt">
			<i class="fa fa-random" aria-hidden="true"></i> 텍스트 추출
		</button>
	</p>
	<div class="h_dot_box info-box-ul" style="padding: 25px 10px 5px 10px;">
		<ul>
			<li>오답 문제를 스마트 폰으로 촬영 하신 후 "사진 등록" 버튼을 클릭 하시고 오답 사진을 업로드 하여 주십시오.</li>
			<li>사진은 최대한 밝고 외곡 없이 촬영하여 주십시오.</li>
			<li>사진을 올리신 후 텍스트 추출 버튼을 클릭하시면 이미지 상의 문제가 텍스트화 됩니다.</li>
			<li>저장 버튼을 클릭하시면 문제가 저장됩니다.</li>
		</ul>
	</div>
	<textarea name="question" id="question"
		style="width: 100%; height: 100px; padding: 20px;"><?=trim($strWrongNoteContents)?></textarea>
	<div class="m-modal-bt-box">
		<button type="submit"
			class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt">
			<i class="fa fa-check" aria-hidden="true"></i> 저장
		</button>
		<button type="button"
			class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt uk-modal-close">
			<i class="fa fa-times" aria-hidden="true"></i> 닫기
		</button>
	</div>
	<? }else{ ?>
	<div class="h_dot_box info-box-ul" style="padding: 20px;text-align:left;">
	<?=nl2br(trim($strWrongNoteContents));?>
	</div>
	<div class="m-modal-bt-box">
		<button type="button"
			class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-12 col-lg-12 btn-lg content_header_list_bt uk-modal-close">
			<i class="fa fa-times" aria-hidden="true"></i> 닫기
		</button>
	</div>	
	<? } ?>
</form>
<!-- ########################## -->