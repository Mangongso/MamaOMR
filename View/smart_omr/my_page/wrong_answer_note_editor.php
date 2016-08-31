<?php
$viewID="SOMR_GET_WRONG_ANSWER";
include($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php");
?>
<?php 

// print "<pre>";
// print_r($arr_output);
// print "</pre>";
?>
<form id="frm_wrong_note">
<input type="hidden" name="answer_key" id="answer_key" value="<?=$arr_output['answer'][0]['answer_key']?>"/>
<input type="hidden" name="wrong_note_key" id="wrong_note_key" value="<?=$arr_output['wrong_note'][0]['wrong_note_key']?>"/>
<input type="hidden" name="wrong_note_file_name" id="wrong_note_file_name" value=""/>
<input type="hidden" name="wrong_note_upload_key" id="wrong_note_upload_key" value=""/>
<? if(count($arr_output['wrong_note'])>0 && $arr_output['wrong_note'][0]['file_name']){?>
<img id="question_img" src="../_images/question.php?b=<?=$arr_output['book_seq'];?>&t=<?=$arr_output['wrong_note'][0]['test_seq']?>&q=<?=$arr_output['wrong_note'][0]['question_seq']?>&f=<?=$arr_output['question'][0]['file_name']?>" data-img_mode="real"/>
<? }else{ ?>
<img id="question_img" src="../_images/default_cover.png" style="width:100%;height:250px;"/>
<? } ?>
<p>
<button id="btn_upload" type="button">사진등록</button>
<button id="btn_ocr" type="button">텍스트추출</button>
</p>
<textarea name="question" id="question" style="width:100%;height:250px;"><?=trim($arr_output['question'][0]['contents'])?></textarea>
<p>
<button type="submit">저장</button>
<button type="button">닫기</button>
</p>
</form>
<?php 
// print "<pre>";
// print_r($arr_output);
// print "</pre>";
?>