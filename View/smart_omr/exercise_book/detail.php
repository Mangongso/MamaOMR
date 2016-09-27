<? $viewID = "SOMR_EXERCISE_BOOK_DETAIL"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
	<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--########################################################################-->
	<!--######################### Exercise book Detail #########################-->
	<!--########################################################################-->
	<div id="main">
		<!--##################################################################################-->
		<!--######################### Contents Upload Button Include #########################-->
		<!--##################################################################################-->
		<!-- Upload Button START -->
		<a class="btn btn-success btn-lg work_book_reg_bt"
			href="/smart_omr/exercise_book/registration.php" title="컨텐츠 추가"><i
			class="fa fa-plus" aria-hidden="true"></i><span class="sr-only">컨텐츠
				추가</span></a>
		<!-- Upload Button END -->
		<!-- Exercise book header START -->
		<? include($_SERVER['DOCUMENT_ROOT']."/smart_omr/_common/elements/sub_content_common_header.php"); ?>
		<!-- Exercise book header END -->
		<div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div class="row content_body content_small_body">
			<? if(count($arr_output['book_test_list'])){ ?>
			<? foreach($arr_output['book_test_list'] as $intKey=>$arrTest){ ?>
				<!-- ### -->
				<div class="sub_contents_body_box">
					<h4 class="uk-clearfix">
						<i class="fa fa-arrow-down" aria-hidden="true"></i> <?=$arrTest['subject']?><br
							class="visible-xs" /> <span><a
							href="../_images/omr/mama-omr-e<?=$arrTest['example_count']?>.jpg"
							target="_blank"><i class="fa fa-arrow-down" aria-hidden="true"></i>
								OMR 다운로드</a></span>
					</h4>
					<ul>
						<li class="col-lg-4"><span><i class="fa fa-bars"
								aria-hidden="true"></i> 문항 수</span> <?=$arrTest['test_question_cnt']?> <small>문항</small></li>
						<li class="col-lg-4"><span><i class="fa fa-users"
								aria-hidden="true"></i> 참가자</span> <?=$arrTest['test_record'][0]['user_count']?><small>명</small></li>
						<li class="col-lg-4"><span><i class="fa fa-line-chart"
								aria-hidden="true"></i> 평균점수</span> <?=$arrTest['score_avarage']?><small>점</small></li>
					</ul>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<a
						href="/smart_omr/exercise_book/test.php?t=<?=md5($arrTest['test_seq'])?>"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-check" aria-hidden="true"></i> 스마트 OMR</a> <a
						href="/exercise_book/detail.php"
						data-test-key="<?=md5($arrTest['test_seq'])?>"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-arrow-up" aria-hidden="true"></i> OMR 업로드</a> <a
						href="/smart_omr/exercise_book/registration_detail_activation.php?test_seq=<?=md5($arrTest['test_seq'])?>&book_seq=<?=md5($arr_output['book_info'][0]['seq'])?>"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-undo" aria-hidden="true"></i> OMR 수정</a>
					<? }else{ ?>
					<a
						href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-check-square" aria-hidden="true"></i> 스마트 OMR</a> <a
						href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-file" aria-hidden="true"></i> OMR 업로드</a> <a
						href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
						class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i
						class="fa fa-undo" aria-hidden="true"></i> OMR수정</a>
					<? } ?>
				</div>
				<!-- ### -->
				<? } ?>
				<div>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<button type="button" class="pure-button pure-form_in btn-block"
						data-toggle="modal" data-target="#registration_test">
						<i class="fa fa-plus" aria-hidden="true"></i> OMR 정답 등록
					</button>
					<? }else{ ?>
					<button type="button" class="pure-button pure-form_in btn-block"
						onclick="halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');">
						<i class="fa fa-plus" aria-hidden="true"></i> OMR 정답 등록
					</button>
					<? } ?>
				</div>
				<? }else{ ?>
				<div class="sub_contents_body_box">

					<div class="h_dot">
						<div class="h_dot_box info-box">
							<h4
								style="text-align: center; margin-top: 10px; margin-bottom: 10px; border-bottom: 0px;">등록된
								테스트가 없습니다.</h4>
							아래의 OMR 등록 버튼을 클릭하신 후 답을 등록하여 주십시오.<br /> <i
								class="fa fa-arrow-down" aria-hidden="true"></i>
						</div>
					</div>
					<div class="info-img">
						<img src="/smart_omr/_images/info-basic.png" />
					</div>
				</div>
				<div>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<button type="button" class="pure-button pure-form_in btn-block"
						data-toggle="modal" data-target="#registration_test">
						<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
					</button>
					<? }else{ ?>
					<button type="button" class="pure-button pure-form_in btn-block"
						onclick="halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');">
						<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
					</button>
					<? } ?>
				</div>
				<? } ?>
			</div>
			<!--####################################################################################-->
		</div>
		<? include("../_common/include/foot_menu.php"); ?>
	</div>
</div>
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/modal/registration_test.php");?>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>
		