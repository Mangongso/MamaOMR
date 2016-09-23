<? $viewID = "SOMR_EXERCISE_BOOK_REGISTRATION_DETAIL"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
	<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--#########################################################################-->
	<!--######################### Registration Detail ###########################-->
	<!--#########################################################################-->
	<div id="main">
		<!-- ################################################################# -->
		<div class="content_header sub_content_header">
			<div class="content_header_area sub_content_header_test_top">
				<div class="col-xs-4 col-sm-5 col-md-5 col-lg-5 content_header_img">
					<a href="#"><img src="<?=$arr_output['book_cover_img']?>"
						alt="<?=$arr_output['book_info'][0]['title']?>" />
					<p class="sr-only"><?=$arr_output['book_info'][0]['title']?></p></a>
				</div>
				<div
					class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
					<ul>
						<li><h3><?=$arr_output['book_info'][0]['title']?></h3></li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span><?=$arr_output['book_total_question_cnt']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span><?=$arr_output['book_join_count']?><small>명</small></li>
						<li class="border-none"><span><i class="fa fa-line-chart"
								aria-hidden="true"></i> 평균점수</span><?=$arr_output['book_score_avarage']?><small>점</small></li>
					</ul>
				</div>

			</div>
		</div>
		<!-- ################################################################# -->
		<div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div
				class="row content_body content_small_body contents_registration_body">
				<button type="button" class="btn btn-primary btn-lg btn-block"
					data-toggle="modal" data-target="#registration_test">
					<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
				</button>
				<div class="h_dot h_dot_li">
					<div class="h_dot_box">
						<ul>
							<li>아직 등록된 테스트가 없습니다.</li>
							<li>OMR 등록 버튼을 클릭하여 테스트를 등록하여 주십시오.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
</div>
<!-- ######################## OMR등록##################### -->
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/modal/registration_test.php");?>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>