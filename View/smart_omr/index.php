<? $viewID = "SOMR_INDEX"; ?>
<? include("./_common/include/header.php"); ?>
<!-- GNB -->
<div id="layout">
	<!--###############################################################-->
	<!--######################### GNB Include #########################-->
	<!--###############################################################-->
	<!-- GNB START -->
<? include("./_common/include/GNB.php"); ?>
<!-- GNB END -->

	<!--#################################################################-->
	<!--######################### CONTENTS BODY #########################-->
	<!--#################################################################-->
	<div id="main">
		<!--##################################################################-->
		<!--######################### Search Include #########################-->
		<!--##################################################################-->
		<!-- Search START -->
		<div class="main_search"><? include("./_common/elements/search.php"); ?></div>
		<!-- Search END -->
		<!--##################################################################################-->
		<!--######################### Contents Upload Button Include #########################-->
		<!--##################################################################################-->
		<!-- Upload Button START -->
		<a class="btn btn-success btn-lg work_book_reg_bt"
			href="/smart_omr/exercise_book/registration.php" title="컨텐츠 추가"><i
			class="fa fa-plus" aria-hidden="true"></i><span class="sr-only">컨텐츠 추가</span></a>
		<!-- Upload Button END -->
		<!--########################################################################-->
		<!--######################### Contents TOP Include #########################-->
		<!--########################################################################-->
		<!-- Contents TOP START -->
		<? foreach($arr_output['header'] as $intKey=>$arrHeader){ ?>
		<div class="content_header">
			<div class="content_header_area">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_header_img">
					<div class="fit_target"
						style="height: 272px; width: 220px; margin: 0 auto;">
						<a
							href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrHeader['seq'])?>" title="<?=$arrHeader['title']?>"><img
							class="fit_image" src="<?=$arrHeader['book_cover_img']?>"
							alt="<?=$arrHeader['title']?>" />
							<p class="sr-only">
							<?=$arrHeader['title']?>
							</p> </a>
					</div>
				</div>
				<div
					class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_header_list">
					<ul>
						<li class="border-none"><h3>
						<?=$arrHeader['title']?>
							</h3></li>
						<li><span><i class="fa fa-ticket" aria-hidden="true"></i> 테스트 수</span><?=count($arrHeader['book_test_list'])?> </li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 테스트 문항 수</span> <?=$arrHeader['question_count']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span> <?=$arrHeader['total_record'][0]['user_count']?><small>명</small></li>
						<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span> <?=$arrHeader['avarage_score']?><small>점</small></li>
						<li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span><?=substr($arrHeader['create_date'],0,10)?></li>
					</ul>
					<a
						href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrHeader['seq'])?>"
						class="pure-button pure-form_in col-lg-12 btn-lg btn-block content_header_list_bt"  title="테스트 참여"><i
						class="fa fa-check" aria-hidden="true"></i> 테스트 참여</a>
				</div>

			</div>
		</div>
		<? } ?>
<!-- Contents TOP END -->
		<!--#########################################################################-->
		<!--######################### Contents BODY Include #########################-->
		<!--#########################################################################-->
		<!-- Contents BODY START -->
		<div class="container-fluid" style="padding: 0px;">
			<? if(count($arr_output['book_list'])){ ?>
			<div class="row content_body" id="book_list_div">
			<? include("./_common/elements/book_list_body.php");?>
			</div>
			<? }else{ ?>
			<div class="row content_body" id="book_list_div">
				<div class="workbook_cover col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<span>등록된 문제집이 없습니다. 마마OMR의 첫 문제집을 등록해 보세요~</span><br>
					<button onclick="location.href='/smart_omr/exercise_book/registration'">문제집 등록하기</button>
				</div>
			</div>
			<? } ?>
			<div id="loading" class="text-center">
				<img src="/smart_omr/_images/loading.gif" alt="loading..." />
			</div>
		</div>
		<!-- Contents BODY END -->
		<!--#########################################################################-->
		<!--######################### Contents FOOT Include #########################-->
		<!--#########################################################################-->
		<!-- Contents FOOT START -->
		<? include("./_common/include/foot_menu.php"); ?>
<!-- Contents FOOT END -->
	</div>
</div>
<!--###########################################################################-->
<!--######################### Contents BOTTOM Include #########################-->
<!--###########################################################################-->
<? include("./_common/include/footer.php"); ?>
<? include("./_common/include/bottom.php"); ?>