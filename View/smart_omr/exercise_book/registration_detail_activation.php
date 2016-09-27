<? $viewID = "SOMR_EXERCISE_BOOK_REGISTRATION_DETAIL_ACTIVATION"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
	<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--####################################################################################-->
	<!--######################### Registration Detail Activation ###########################-->
	<!--####################################################################################-->
	<div id="main">
		<!-- ################################################################# -->
		<div class="content_header sub_content_header">
			<div class="content_header_area sub_content_header_test_top">
				<div class="col-xs-4 col-sm-5 col-md-5 col-lg-5 content_header_img">
					<a
						href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arr_output['book_info'][0]['seq'])?>"><img
						src="<?=$arr_output['book_cover_img']?>"
						alt="<?=$arr_output['book_info'][0]['title']?>"
						class="content_cover_img" />
						<p class="sr-only"><?=$arr_output['book_info'][0]['title']?></p></a>
				</div>
				<div
					class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
					<ul>
						<li><h3><?=$arr_output['book_info'][0]['title']?></h3></li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span><?=$arr_output['book_total_question_cnt']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span><?=$arr_output['book_join_count']?><small>명</small></li>
						<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span><?=$arr_output['book_score_avarage']?><small>점</small></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- ################################################################# -->

		<div class="container-fluid">
			<!--####################################################################################-->
			<form id="frmQuestion">
				<input type="hidden" name="book_seq" id="book_seq"
					value="<?=$arr_output['book_info'][0]['seq']?>" /> <input
					type="hidden" name="test_seq" id="test_seq"
					value="<?=$arr_output['test_info'][0]['seq']?>" /> <input
					type="hidden" name="published_seq" id="question_seq"
					value="<?=$arr_output['test_info'][0]['publish'][0]['seq']?>" />
				<div
					class="row content_body content_small_body contents_registration_body">
					<!-- ################################################################# -->
					<div class="sub_contents_body_box text-left padding-0-10px">
						<h4><?=$arr_output['test_info'][0]['subject']?></h4>
				<? if(false){ ?>
				<ul>
							<li><a href="/smart_omr/exercise_book/test.php"
								class="btn btn-info col-xs-6 col-sm-6 col-md-6 col-lg-6 btn-lg content_header_list_bt"><i
									class="fa fa-times" aria-hidden="true"></i> 삭제</a></li>
						</ul>
				<? } ?>
				</div>
					<!-- ################################################################# -->
					<div class="sub_contents_test_body">
						<div class="h_dot">
							<div class="h_dot_box">
								해당 문제의 답을 아래에 체크하여 주십시오.<br /> <i class="fa fa-arrow-down"
									aria-hidden="true"></i>
							</div>
						</div>
						<!-- ########################## -->
				<? foreach($arr_output['test_question_list'] as $intKey=>$arrQuestionInfo){ ?>
				<div class="uk-width-1-1 question_div"
							id="question_<?=$arrQuestionInfo['question_seq']?>"
							data-question-seq="<?=$arrQuestionInfo['question_seq']?>">
							<input type="hidden"
								name="question_seq[<?=$arrQuestionInfo['question_seq']?>]"
								id="question_seq_<?=$arrQuestionInfo['question_seq']?>"
								value="<?=$arrQuestionInfo['question_seq']?>"
								class="question_seq"> <input type="hidden"
								name="answer[<?=$arrQuestionInfo['question_seq']?>][seq]"
								id="answer_<?=$arrQuestionInfo['question_seq']?>_seq"
								value="<?=$arrQuestionInfo['answer']['seq'];?>" /> <input
								class="order_number" type="hidden"
								name="order_number[<?=$arrQuestionInfo['question_seq']?>]"
								id="order_number_<?=$arrQuestionInfo['question_seq']?>"
								value="<?=$arrQuestionInfo['order_number']?>"> <input
								class="question_score" type="hidden"
								name="question_score[<?=$arrQuestionInfo['question_seq']?>]"
								id="question_score_<?=$arrQuestionInfo['question_seq']?>"
								value="1<?//=$arrQuestionInfo['question_score']?>"> <input
								class="question_type" type="hidden"
								data-question-seq="<?=$arrQuestionInfo['question_seq']?>"
								name="question_type[<?=$arrQuestionInfo['question_seq']?>]"
								id="question_type_<?=$arrQuestionInfo['question_seq']?>"
								value="<?=$arrQuestionInfo['question_type']?>">

							<h4 class="uk-width-2-10 pull-left order_number_sub"><?=$arrQuestionInfo['order_number']?></h4>
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
									class="uk-width-<?=$intExampleWidth?>-10 btn btn-default <?=$arrExample['answer_flg']?'active':''?> <?=$arr_output['editble']?'':'disabled'?>"
									onclick="$('#answer_<?=$arrQuestionInfo['question_seq']?>_seq').val($('#example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>').val());"
									for="example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>">
									<input type="radio" value="<?=$arrExample['seq'];?>"
									id="example_<?=$arrQuestionInfo['question_seq']?>_<?=$arrExample['seq'];?>"><?=$arrExample['example_number'];?>
					  	</label>
					  	<? } ?>
					</div>
							<div class="question_info">
								<div class="uk-width-1-10 uk_pd_init pull-left">
									<button type="button"
										onclick="objRegistration.insertSingleQuestion(<?=$arrQuestionInfo['question_seq'];?>);"
										class="btn btn-default btn-sm uk-width-1-1 <?=$arr_output['editble']?'':'disabled'?>" title="문제추가">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
								</div>
								<div class="uk-width-1-10 uk_pd_init pull-left"
									style="border-left: none;">
									<button type="button"
										onclick="objRegistration.deleteSingleQuestion(<?=$arrQuestionInfo['question_seq'];?>);"
										class="btn btn-default btn-sm uk-width-1-1 <?=$arr_output['editble']?'':'disabled'?>"  title="문제삭제">
										<i class="fa fa-minus" aria-hidden="true"></i>
									</button>
								</div>
								<div class="uk-width-8-10 answer_tag pull-left">
									<label
										for="question_tags<?=$arrQuestionInfo['question_seq'];?>"
										class="sr-only">유형태그 입력</label><input type="text"
										class="form-control input-sm"
										name="question_tags[<?=$arrQuestionInfo['question_seq'];?>]"
										id="question_tags<?=$arrQuestionInfo['question_seq'];?>"
										placeholder="유형태그를 콤마(,)로 구분하여 입력하세요"
										value="<?=htmlentities($arrQuestionInfo['tags'],ENT_QUOTES,'UTF-8');?>">
								</div>
							</div>
						</div>
				<? } ?>
						<!-- ########################## -->
				 <? if($_GET['test_seq']){ ?>
				<button type="button"
							onclick="objRegistration.saveTestQuestionWithAnswer();"
							class="pure-button pure-form_in btn-lg col-xs-12 col-sm-12 col-md-12 col-lg-12 <?=$arr_output['editble']?'':'disabled'?>"
							data-toggle="modal" data-target="#registration_test">
							<i class="fa fa-check" aria-hidden="true"></i> 테스트 수정
						</button>
				<? }else{ ?>
				<button type="button"
							onclick="objRegistration.saveTestQuestionWithAnswer();"
							class="pure-button pure-form_in btn-lg col-xs-12 col-sm-12 col-md-12 col-lg-12 <?=$arr_output['editble']?'':'disabled'?>"
							data-toggle="modal" data-target="#registration_test">
							<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
						</button>
				<? } ?>
				<br class="hidden-sm hidden-xs" /> <br class="hidden-sm hidden-xs" />
					</div>
				</div>
			</form>
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
	</div>
	</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>