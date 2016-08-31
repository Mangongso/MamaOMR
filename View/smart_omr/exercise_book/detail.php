<? $viewID = "SOMR_EXERCISE_BOOK_DETAIL"; ?>
<? include("../_common/include/header.php"); ?>
<?
$arrBookInfo = $arr_output['book_info'][0];
?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
	<!-- GNB -->
	<!-- CONTENTS BODY -->
	<div id="main">
		<a class="btn btn-success btn-lg work_book_reg_bt" href="/smart_omr/exercise_book/registration.php"><i class="fa fa-plus" aria-hidden="true"></i> </a>
		<!------------------------------------------------------------------>
		<? include($_SERVER['DOCUMENT_ROOT']."/smart_omr/_common/elements/sub_content_common_header.php"); ?>
		<!---------------------------------------->

		<div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div class="row content_body content_small_body">
			<? if(count($arr_output['book_test_list'])){ ?>
			<? foreach($arr_output['book_test_list'] as $intKey=>$arrTest){ ?>
				<!------------------------------------------------------------->
				<div class="sub_contents_body_box">
					<h4 class="uk-clearfix">
					<span class="uk-float-left"><?=$arrTest['subject']?></span><span class="uk-float-right uk-button uk-button-mini"><a href="/smart_omr/_common/document/pdf/?t=<?=md5($arrTest['test_seq'])?>" target="_blank">OMR 다운로드</a></span>
					</h4>
					<ul>
						<li class="col-lg-4"><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span> <?=$arrTest['test_question_cnt']?> <small>문항</small></li>
						<li class="col-lg-4"><span><i class="fa fa-users" aria-hidden="true"></i> 참가자</span> <?=$arrTest['test_record'][0]['user_count']?><small>명</small></li>
						<li class="col-lg-4"><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span> <?=$arrTest['score_avarage']?><small>점</small></li>
						</ul>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<a href="/smart_omr/exercise_book/test.php?t=<?=md5($arrTest['test_seq'])?>" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-check-square" aria-hidden="true"></i> 스마트 OMR</a> 
					<a href="#" data-test-key="<?=md5($arrTest['test_seq'])?>" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-file" aria-hidden="true"></i> OMR 업로드</a>
					<a href="/smart_omr/exercise_book/registration_detail_activation.php?test_seq=<?=md5($arrTest['test_seq'])?>&book_seq=<?=md5($arr_output['book_info'][0]['seq'])?>" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-undo" aria-hidden="true"></i> OMR 수정</a>
					<? }else{ ?>
					<a href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-check-square" aria-hidden="true"></i> 스마트 OMR</a> 
					<a href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-file" aria-hidden="true"></i> OMR 출력</a> 
					<a href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');" class="pure-button pure-form_in col-xs-12 col-sm-12 col-md-4 col-lg-4"><i class="fa fa-undo" aria-hidden="true"></i> OMR수정</a>
					<? } ?>
				</div>
				<!------------------------------------------------------------->
				<? } ?>
				<div>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<button type="button" class="pure-button pure-form_in btn-block" data-toggle="modal" data-target="#registration_test">
						<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
					</button>
					<? }else{ ?>
					<button type="button" class="pure-button pure-form_in btn-block" onclick="halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');">
						<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
					</button>
					<? } ?>
				</div>
				<? }else{ ?>
				<div class="sub_contents_body_box">
					<h4 style="text-align:center; margin-top:10px;margin-bottom:10px;border-bottom:0px;">
					등록된 테스트가 없습니다. 
					</h4>
				</div>
				<div>
					<? if($_SESSION['smart_omr']['member_key']){ ?>
					<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#registration_test">
						<i class="fa fa-check" aria-hidden="true"></i> OMR 등록
					</button>
					<? }else{ ?>
					<button type="button" class="btn btn-primary btn-lg btn-block" onclick="halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');">
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
	<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/modal/registration_test.php");?>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>
		