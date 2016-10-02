<? $viewID = "SOMR_EXERCISE_BOOK_TEST_RESULT"; ?>
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
					<a
						href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arr_output['book_info'][0]['seq'])?>"
						title="<?=$arr_output['book_info'][0]['title']?>"><img
						src="<?=$arr_output['book_cover_img']?>"
						alt="<?=$arr_output['book_info'][0]['title']?>" />
						<span class="sr-only"><?=$arr_output['book_info'][0]['title']?></span> </a>
				</div>
				<div
					class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
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
			<!-- XS menu START -->
					<div class="pure-menu pure-menu-horizontal pure-menu-scrollable scrollable-menu  visible-xs mypage_menu">
						<ul class="pure-menu-list">
							<li class="pure-menu-item" onclick="objCommon.displayTab('answer_omr');">
									<i class="uk-icon-caret-right"></i> 입력답안
							</li>
							<li class="pure-menu-item" onclick="objCommon.displayTab('study_report');">
									<i class="uk-icon-caret-right"></i> 학습리포트
							</li>
							<li class="pure-menu-item" onclick="objCommon.displayTab('wrong_answer_note');">
									<i class="uk-icon-caret-right"></i> 오답노트
							</li>
							<li class="pure-menu-item" onclick="objCommon.displayTab('wrong_answer_test');">
									<i class="uk-icon-caret-right"></i> 오답문제풀이 
									<small>
										<i onclick="objWAN.getWrongNoteTest('<?=$_GET['t']?>','<?=$arr_output['record'][0]['revision']?>');" style="cursor: pointer; color: #ccc;" class="fa fa-refresh" aria-hidden="true" data-t="<?=$_GET['t']?>" data-revision="<?=$arr_output['record'][0]['revision']?>"></i>
									</small>
							</li>
							<li class="pure-menu-item" onclick="objCommon.displayTab('comment');objCommon.getComment($('#comment_div').attr('data-comment-seq'),$('#comment_div').attr('data-bbs-seq'));">
									<i class="uk-icon-caret-right"></i> 댓글 
							</li>
						</ul>
					</div>
			
			<!-- XS menu END-->
		<div class="sub_contents_test_body sub_contents_test_result">
			<!-- ############### -->
			<ul class="nav nav-tabs sub_content_top_menu hidden-xs">
				<li class="active"
					onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('answer_omr');"><a
					href="javascript:void(0);" title="입력답안"><i class="fa fa-check"
						aria-hidden="true"></i> 입력답안</a></li>
				<li class=""
					onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('study_report');"><a
					href="javascript:void(0);" title="학습리포트"><i class="fa fa-bar-chart"
						aria-hidden="true"></i> 학습리포트</a></li>
				<li class=""
					onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_note');"><a
					href="javascript:void(0);" title="오답노트"><i class="fa fa-times"
						aria-hidden="true"></i> 오답노트</a></li>
				<? if(!$arr_output['student_info']){ ?>
				<li class=""
					onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('wrong_answer_test');">
					<a href="javascript:void(0);" title="오답문제풀이"> <i class="fa fa-undo"
						aria-hidden="true"></i> 오답문제풀이 <small><i
							onclick="objWAN.getWrongNoteTest('<?=$_GET['t']?>','<?=$arr_output['record'][0]['revision']?>');"
							style="cursor: pointer; color: #ccc;" class="fa fa-refresh"
							aria-hidden="true" data-t="<?=$_GET['t']?>"
							data-revision="<?=$arr_output['record'][0]['revision']?>"></i></small>
				</a>
				</li>
				<? } ?>
				<li class=""
					onclick="$('.sub_content_top_menu li').attr('class','');$(this).attr('class','active');objCommon.displayTab('comment');objCommon.getComment($('#comment_div').attr('data-comment-seq'),$('#comment_div').attr('data-bbs-seq'));">
					<a href="javascript:void(0);" title="댓글"> <i class="fa fa-comment fa-flip-horizontal" aria-hidden="true"></i>댓글 </a>
				</li>
			</ul>

			<!-- ############### -->
			<? if($arr_output['student_info']){ ?>
			<div class="row">
				<div class="col-xs-12">
				<span class="pull-left"><span class="show-xs">&nbsp;&nbsp;</span><i class="fa fa-mortar-board" aria-hidden="true"></i> <?=$arr_output['student_info'][0]['name'];?> 님<span class="hidden-xs">의 문제 풀이 결과 입니다.</span></span><span class="pull-right"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=$arr_output['record'][0]['modify_date'];?><span class="show-xs">&nbsp;&nbsp;</span></span>
				</div>
			</div>
			<? } ?>
			<div class="h_dot">
				<div class="h_dot_box test_score">
					<h2><?=$arr_output['record'][0]['user_score']?>/<?=$arr_output['record'][0]['total_score']?><small>점</small>
					</h2>
				</div>
			</div>
			
			<!--##########################################################-->
			<!--#########################입력답안############################-->
			<!--##########################################################-->
			<!-- ############### -->
			<div id="answer_omr" class="sub_tabs">
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>
				<div
					class="uk-width-1-1 <?=$arr_output['user_answer'][$intKey]['result_flg']?'test_right_answer':'test_wrong_answer'?>"
					data-question-seq="<?=$arrQuestionInfo['question_seq']?>">
					<h4 class="uk-width-2-10 pull-left">
						<i
							class="fa fa-<?=$arr_output['user_answer'][$intKey]['result_flg']?'circle-o':'times'?>"
							aria-hidden="true"></i><br><?=$arrQuestionInfo['order_number']?></h4>
					<div
						class="uk-width-8-10 btn-group ans_correct ans_correct_<?=$arrQuestionInfo['question_type'];?>"
						data-toggle="buttons">
						<?
					switch ($arrQuestionInfo ['question_type']) {
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
						<? foreach($arrQuestionInfo['example']['type_1'] as $intExampleKey=>$arrExample){ ?>
						<label
							class="uk-width-<?=$intExampleWidth?>-10 btn btn-default <?=$arrExample['seq']==$arr_output['user_answer'][$intKey]['user_answer']?'active':'disabled'?>">
							<input type="radio" value="<?=$arrExample['seq'];?>" /><?=$arrExample['example_number'];?>
					  	</label>
					  	<? } ?>
					</div>
				</div>
				<? } ?>
				</div>
				
			<!--###########################################################-->
			<!--#########################학습리포트############################-->
			<!--###########################################################-->
			<!-- ############### -->
			<div class="study_report sub_tabs" id="study_report" style="display: none;">
				<!-- ############### -->
				<? if(count($arr_output['record_tags'])){ ?>
				<? foreach($arr_output['record_tags'] as $intKey=>$arrRecordTags){ ?>
				<div class="uk-width-1-1">
					<h4 class="uk-width-2-10 pull-left">
						<span><?=$arrRecordTags['tag']?$arrRecordTags['tag']:'미지정'?></span><br /><?=$arrRecordTags['corect_cnt']?>/<?=$arrRecordTags['tag_cnt']?></h4>
					<div class="uk-width-8-10 pull-right">
						<div class="progress">
							<div class="progress-bar progress-bar-info progress-bar-striped uk-width-1-1" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?=(int)$arrRecordTags['corect_percent']?>%">
								<span class="sr-only"><?=(int)$arrRecordTags['corect_percent']?>% Complete</span>
							</div>
						</div>
					</div>
				</div>
				<? } ?>
				<? }else{ ?>
				<div class="h_dot">
					<div class="h_dot_box" style="top: 0px; border-top: 0px;">태그가 지정되어
						있지 않습니다.</div>
				</div>
				<? } ?>
				<!-- ############### -->
			</div>
			<!--##########################################################-->
			<!--#########################오답노트############################-->
			<!--##########################################################-->
			<!-- ############### -->
			<div id="wrong_answer_note" style="display: none;" class="sub_tabs">
				<? include('../exercise_book/_elements/wrong_note_list.php');?>	
				</div>

			<div id="wrong_answer_test" style="display: none;" class="sub_tabs">
				<? include('../exercise_book/_elements/wrong_note_test.php');?>	
				</div>

			<div id="comment" style="display: none;" class="sub_tabs">
				<!-- comment -->
				<div class="h_dot">
					<div id="comment_div"
						data-comment-seq="<?=$arr_output['test_info'][0]['seq'];?>" data-bbs-seq="4"></div>
				</div>
			</div>
			<!-- 
				<div id="question_community" style="display:none;" class="sub_tabs">
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>#############
				<div class="uk-width-1-1 <?=$arr_output['user_answer'][$intKey]['result_flg']?'test_right_answer':'test_wrong_answer'?>" id="question_<?=$arrQuestionInfo['question_seq']?>" data-question-seq="<?=$arrQuestionInfo['question_seq']?>" >
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
			<? if(!$arr_output['student_info']){ ?>
			<a
				href="/smart_omr/exercise_book/test.php?t=<?=$arr_output['str_test_seq']?>"
				class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt"
				title="다시 풀기"><i class="fa fa-arrow-left" aria-hidden="true"></i> 다시
				풀기 </a> <a href="/smart_omr/exercise_book/list.php"
				class="pure-button pure-form_in col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt"
				title="문제집목록"><i class="fa fa-bars" aria-hidden="true"></i> 문제집목록 </a>
			<? }else{ ?>
			<a href="/smart_omr/my_page/my_managing_report_detail.php?bs=<?=$arr_output['book_info'][0]['book_key']?>&sk=<?=$_GET['sk']?>"
				class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-12 col-lg-12 btn-lg content_header_list_bt"
				title="문제집목록"><i class="fa fa-bars" aria-hidden="true"></i> 테스트목록 </a>
			<? } ?>
			<div style="height: 60px;"></div>
			<!-- ############### -->
		</div>        
<? include("../_common/include/foot_menu.php"); ?>
</div>
</div>
<? include("../_common/modal/wrong_answer_editor.php"); ?>
<? include("../_common/modal/wrong_answer_photo.php"); ?>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>