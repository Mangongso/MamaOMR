<? $viewID = "SOMR_EXERCISE_BOOK_TEST"; ?>
<? include("../_common/include/header.php"); ?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>

<? if($arr_output['device']['mobile_flg'] && 0){ // 해당 기능 보류 ?>
<script>
$(document).ready(function(){
	$('.ans_correct .btn-default').on('click',function(){
		var questionEle = $(this).parents('.question_div');
		objRegistration.animateBtn(questionEle);
	});
});
</script>
<? } ?>
	<!-- GNB -->
	<!-- CONTENTS BODY -->
	<div id="main">
		<!--? include("../_common/elements/search.php"); ?-->
		<!------------------------------------------------------------->
		<!---------------------------------------->
		<div class="content_header sub_content_header">
			<div class="content_header_area sub_content_header_test_top">
				<div class="col-xs-4 col-sm-5 col-md-5 col-lg-5 content_header_img">
					<a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arr_output['book_info'][0]['seq'])?>"><img src="<?=$arr_output['book_cover_img']?>" alt="<?=$arr_output['book_info'][0]['title']?>" />
						<p class="sr-only"><?=$arr_output['book_info'][0]['title']?></p> </a>
				</div>
				<div class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
					<ul>
						<li><h3>
						<?=$arr_output['book_info'][0]['title']?>
							</h3></li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span> <?=$arr_output['question_cnt']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span> <?=$arr_output['user_record'][0]['user_count']?><small>명</small></li>
						<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span> <?=$arr_output['user_score_avarage']?><small>점</small></li>
						<!--li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span>2016-12-25</li>
			        	<li class="border-none"><span><i class="fa fa-user" aria-hidden="true"></i> 생성자</span>산이아범</li-->
					</ul>
					<!--a href="/smart_omr/exercise_book/detail.php" class="btn btn-danger col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check"aria-hidden="true"></i> 테스트 참여</a-->
				</div>

			</div>
		</div>
		<!---------------------------------------->
			<div class="sub_contents_test_body">
			<form id="frmUserAnswer">
				<input type="hidden" name="book_seq" value="<?=$arr_output['book_info'][0]['seq']?>" /> 
				<input type="hidden" name="test_seq" value="<?=$arr_output['test_info'][0]['seq']?>" /> 
				<input type="hidden" name="published_seq" value="<?=$arr_output['test_info'][0]['publish'][0]['seq']?>" />
				
				<div class="sub_contents_body_box">
					<h4 class="border-none"><?=$arr_output['test_info'][0]['subject']?></h4>
				</div>
				<div class="h_dot">
					<div class="h_dot_box" style="top:0px;">
						해당 문제의 답을 아래에 체크하여 주십시오.<br /> <i class="fa fa-arrow-down" aria-hidden="true"></i>
					</div>
				</div>
				<!--------------------------->
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>
				<div class="uk-width-1-1 question_div" id="question_<?=$arrQuestionInfo['question_seq']?>" question_seq="<?=$arrQuestionInfo['question_seq']?>">
					<input type="hidden" name="question_seq[<?=$arrQuestionInfo['question_seq']?>]" id="question_seq_<?=$arrQuestionInfo['question_seq']?>" value="<?=$arrQuestionInfo['question_seq']?>" class="question_seq"> 
					<input type="hidden" name="user_answer[<?=$arrQuestionInfo['question_seq']?>]" id="user_answer_<?=$arrQuestionInfo['question_seq']?>" value="<?=$arrExample['seq']?>" class="question_seq"> 
					<input class="order_number" type="hidden" name="order_number[<?=$arrQuestionInfo['question_seq']?>]" id="order_number_<?=$arrQuestionInfo['question_seq']?>" value="<?=$arrQuestionInfo['order_number']?>"> 
					<input class="question_score" type="hidden" name="question_score[<?=$arrQuestionInfo['question_seq']?>]" id="question_score_<?=$arrQuestionInfo['question_seq']?>" value="<?=$arrQuestionInfo['question_score']?>"> 
					<input class="question_type" type="hidden" question_seq="<?=$arrQuestionInfo['question_seq']?>" name="question_type[<?=$arrQuestionInfo['question_seq']?>]"
						id="question_type_<?=$arrQuestionInfo['question_seq']?>" value="<?=$arrQuestionInfo['question_type']?>">

					<h4 class="uk-width-2-10 pull-left">
					<?=$arrQuestionInfo['order_number']?>
					</h4>
					<div class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrQuestionInfo['question_type'];?>" data-toggle="buttons">
					<?
					switch($arrQuestionInfo['question_type']){
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
					<? foreach($arrQuestionInfo['example']['type_1'] as $intExampleKey=>$arrExample){ ?>
						<label class="uk-width-<?=$intExampleWidth?>-10 btn btn-default " onclick="$('#user_answer_<?=$arrQuestionInfo['question_seq']?>').val($('#example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>').val());"> 
							<input type="radio" value="<?=$arrExample['seq'];?>" id="example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>" autocomplete="off"> <?=$arrExample['example_number'];?> 
						</label>
						<? } ?>
					</div>
				</div>
				<? } ?>
				<!--------------------------->
				<button type="button" onclick="objRegistration.submitQuestionAnswer();" class="pure-button pure-form_in btn-block submit_btn">답안지 전송 <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
				<!--------------------------->
				<div class="h_dot">
				<div class="h_dot_box" style="top:15px;">정답을 모두 체크하셨다면 답안지 전송버튼을 클릭하여 주십시오.</div>
				</div>
				
			</form>
				
			</div>
		<? include("../_common/include/foot_menu.php"); ?>
	</div>
	<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
		<? include("../_common/include/footer.php"); ?>
		<? include("../_common/include/bottom.php"); ?>