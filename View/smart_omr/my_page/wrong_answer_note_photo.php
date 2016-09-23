<?php
$viewID = "SOMR_GET_WRONG_ANSWER";
include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
?>
<!--#############################################################################-->
<!--######################### Wrong Answer Note Photo ###########################-->
<!--#############################################################################-->
<!-- ########################## -->
<form>
<? if(count($arr_output['wrong_note'])>0){?>
<img
		src="./_images/question.php?p=<?=$arr_output['wrong_note'][0]['wrong_note_key']?>" />
	<input type="hidden" name="wrong_note_key"
		value="<?=$arr_output['wrong_note'][0]['wrong_note_key']?>" />
	<button type="button">다시등록</button>
	<button type="button">택스트추출</button>

<? }else{ ?>
<img width="300" src="../_images/default_cover.png" />
	<p>이미지를 클릭해서 사진을 등록 할 수 있도록 할 것</p>
<? } ?>
<button type="button">닫기</button>
</form>
<!-- ########################## -->