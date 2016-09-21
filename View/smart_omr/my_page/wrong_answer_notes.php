<? $viewID = "SOMR_WRONG_ANSWER_NOTE"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--###############################################################-->
	<!--######################### Test Result #########################-->
	<!--###############################################################-->
<div id="main">
	<!-- Exercise book header START -->
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
						</ul>
					</div>
	
				</div>
			</div>
			<!-- Exercise book header END -->
			<!-- ############### -->
				<div class="sub_contents_test_body sub_contents_test_result">
				<ul class="nav nav-tabs sub_content_top_menu">
				  <li class="active" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_note');"><a href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i> 오답노트</a></li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_test');">
				  	<a href="javascript:void(0);">
				  		<i class="fa fa-undo" aria-hidden="true"></i> 오답문제풀이 <small><i onclick="objWAN.getWrongNoteTest('<?=$_GET['t']?>','<?=$_GET['revision']?>');" style="cursor: pointer;color: #ccc;"" class="fa fa-refresh" aria-hidden="true" t="<?=$_GET['t']?>" revision="<?=$_GET['revision']?>" ></i></small>
				  	</a>
				  </li>
				  <li class="" onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('comment');">
				  	<a href="javascript:void(0);">
				  		<i class="fa fa-comment fa-flip-horizontal" aria-hidden="true"></i> 댓글 <small><i onclick="objCommon.getComment($('#comment_div').attr('comment_seq'),$('#comment_div').attr('bbs_seq'));" style="cursor: pointer;color: #ccc;"" class="fa fa-refresh" aria-hidden="true" t="<?=$_GET['t']?>" revision="<?=$_GET['revision']?>" ></i></small>
				  	</a>
				  </li>
				</ul>
				<!--##########################################################-->
				<!--#########################오답노트############################-->
				<!--##########################################################-->
				<!-- ############### -->			
				<div id="wrong_answer_note" class="sub_tabs">
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