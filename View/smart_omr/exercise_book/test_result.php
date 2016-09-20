<? $viewID = "SOMR_EXERCISE_BOOK_TEST_RESULT"; ?>
<? include("../_common/include/header.php"); ?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
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
							<p class="sr-only">해커스 톡 기초영어</p> </a>
					</div>
					<div class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
						<ul>
							<li><h3>
							<?=$arr_output['book_info'][0]['title']?>
								</h3></li>
							<li><h3>
							<?=$arr_output['test_info'][0]['subject']?>
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
				
				<!---------------------------------------->
				<div class="sub_contents_test_body sub_contents_test_result">
					<!---------------------------------------->
				<ul class="nav nav-tabs sub_content_top_menu">
				  <li class="active" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('study_report');"><a href="javascript:void(0);"><i class="fa fa-bar-chart" aria-hidden="true"></i> 학습리포트</a></li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('answer_omr');"><a href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> 입력답안</a></li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_note');"><a href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> 오답노트</a></li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_test');">
				  	<a href="javascript:void(0);">
				  		<i class="fa fa-times" aria-hidden="true"></i> 오답문제풀이  <i onclick="objWAN.getWrongNoteTest('<?=$_GET['t']?>','<?=$_GET['revision']?>');" style="cursor: pointer;" class="fa fa-refresh" aria-hidden="true" t="<?=$_GET['t']?>" revision="<?=$_GET['revision']?>" ></i>
				  	</a>
				  </li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('comment');">
				  	<a href="javascript:void(0);">
				  		<i class="fa fa-times" aria-hidden="true"></i> 댓글 <i onclick="objCommon.getComment($('#comment_div').attr('comment_seq'),$('#comment_div').attr('bbs_seq'));" style="cursor: pointer;" class="fa fa-refresh" aria-hidden="true" t="<?=$_GET['t']?>" revision="<?=$_GET['revision']?>" ></i>
				  	</a>
				  </li>
				  
				  <!-- 
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('question_community');"><a href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i> 문제커뮤니티</a></li>
				   -->
				</ul>
				<!---------------------------------------->
				<div class="h_dot">
				<div class="h_dot_box test_score"><h2><?=$arr_output['record'][0]['user_score']?>/<?=$arr_output['record'][0]['total_score']?><small>점</small></h2></div>
				</div>
				
				<!--#########################학습리포트############################-->
				<!--#########################학습리포트############################-->
				<!--#########################학습리포트############################-->
				<!--#########################학습리포트############################-->
				<!--#########################학습리포트############################-->
				<!--#########################학습리포트############################-->
				<!--------------------------->
				<div class="study_report sub_tabs" id="study_report">
				<!-------------------------------->
				<? if(count($arr_output['record_tags'])){ ?>
				<? foreach($arr_output['record_tags'] as $intKey=>$arrRecordTags){ ?>
				<div class="uk-width-1-1">
				<h4 class="uk-width-2-10 pull-left"><span><?=$arrRecordTags['tag']?$arrRecordTags['tag']:'미지정'?></span><br/><?=$arrRecordTags['corect_cnt']?>/<?=$arrRecordTags['tag_cnt']?></h4>
				<div  class="uk-width-8-10 pull-right">
					<div class="progress">
					  <div class="progress-bar progress-bar-info progress-bar-striped uk-width-1-1" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?=(int)$arrRecordTags['corect_percent']?>%">
					    <span class="sr-only"><?=(int)$arrRecordTags['corect_percent']?>% Complete</span>
					  </div>
					</div>
				</div>
				</div>
				<? } ?>
				<? }else{ ?>
				<div class="h_dot" >
					<div class="h_dot_box" style="top:0px;border-top:0px;">태그가 지정되어 있지 않습니다.</div>
				</div>
				<? } ?>
				<!-------------------------------->
				</div>
				<!--------------------------->
				<!--#########################입력답안############################-->
				<!--#########################입력답안############################-->
				<!--#########################입력답안############################-->
				<!--#########################입력답안############################-->
				<!--#########################입력답안############################-->
				<!--#########################입력답안############################-->
				<!--------------------------->
				<div id="answer_omr" style="display:none;" class="sub_tabs">
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>
				<div class="uk-width-1-1 <?=$arr_output['user_answer'][$intKey]['result_flg']?'test_right_answer':'test_wrong_answer'?>" id="question_<?=$arrQuestionInfo['question_seq']?>" question_seq="<?=$arrQuestionInfo['question_seq']?>" >
					<h4 class="uk-width-2-10 pull-left"><i class="fa fa-<?=$arr_output['user_answer'][$intKey]['result_flg']?'circle-o':'times'?>" aria-hidden="true"></i><br><?=$arrQuestionInfo['order_number']?></h4>
					<div  class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrQuestionInfo['question_type'];?>" data-toggle="buttons">
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
						<label class="uk-width-<?=$intExampleWidth?>-10 btn btn-default <?=$arrExample['seq']==$arr_output['user_answer'][$intKey]['user_answer']?'active':'disabled'?>">
					    <input type="radio" value="<?=$arrExample['seq'];?>" id="example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>" autocomplete="off"><?=$arrExample['example_number'];?>
					  	</label>
					  	<? } ?>
					</div>
				</div>
				<? } ?>
				</div>
				<!--------------------------->
				<!--#########################오답노트############################-->
				<!--#########################오답노트############################-->
				<!--#########################오답노트############################-->
				<!--#########################오답노트############################-->
				<!--#########################오답노트############################-->
				<!--#########################오답노트############################-->
				<!--------------------------->				
				<?php 
// 				print "<pre>";
// 				print_r($arr_output['wrong_answer']);
// 				print "</pre>";
				?>
				<div id="wrong_answer_note" style="display:none;" class="sub_tabs">
				<? include('../exercise_book/_elements/wrong_note_list.php');?>	
				</div>
				
				<div id="wrong_answer_test" style="display:none;" class="sub_tabs">
				<? include('../exercise_book/_elements/wrong_note_test.php');?>	
				</div>
				
				<div id="comment" style="display:none;" class="sub_tabs">
					<!-- comment -->
					<div class="h_dot">
						<div id="comment_div" comment_seq="<?=$arr_output['test_info'][0]['seq'];?>" bbs_seq="4"></div>
					</div>
				</div>
				
				
				
				
				<!-- 
				<div id="question_community" style="display:none;" class="sub_tabs">
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>
				<div class="uk-width-1-1 <?=$arr_output['user_answer'][$intKey]['result_flg']?'test_right_answer':'test_wrong_answer'?>" id="question_<?=$arrQuestionInfo['question_seq']?>" question_seq="<?=$arrQuestionInfo['question_seq']?>" >
					<h4 class="uk-width-2-10 pull-left" style="top:0px;"><i class="fa fa-<?=$arr_output['user_answer'][$intKey]['result_flg']?'circle-o':'times'?>" aria-hidden="true"></i><br><?=$arrQuestionInfo['order_number']?></h4>
					<div class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrQuestionInfo['question_type'];?>" data-toggle="buttons">
						<div><span>1111</span> <span>1111</span> <span>1111</span></div>
					</div>
					<div id="question_detail" class="question_community">
						<form class="_d_question_form">
							
						</form>
					</div>
				</div>
				<? } ?>
				</div>
				 -->
				
				<a href="/smart_omr/exercise_book/test.php?t=<?=$arr_output['str_test_seq']?>" class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt"><i class="fa fa-arrow-left" aria-hidden="true"></i> 다시 풀기 </a>
				<a href="/smart_omr/exercise_book/list.php" class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt"><i class="fa fa-bars" aria-hidden="true"></i> 문제집목록 </a>
				<div style="height: 60px;"></div>
				<!--------------------------->
				</div>        
<? include("../_common/include/foot_menu.php"); ?>
</div>
<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
<? include("../_common/modal/wrong_answer_editor.php"); ?>
<? include("../_common/modal/wrong_answer_photo.php"); ?>

<script>
/*
$('#modal-wa-photo').on({

    'show.uk.modal': function(event, ui){
    	//$(this).data(event);
        console.log("Modal is visible.");
    },

    'hide.uk.modal': function(){
        console.log("Element is not visible.");
    }
});
*/
</script>


<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>