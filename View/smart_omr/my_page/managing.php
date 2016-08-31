<?
$viewID = "SOMR_MY_PAGE_MANAGING";
include ("../_common/include/header.php");
$tabSelected = 3;
?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
<!-- GNB -->
	<!-- CONTENTS BODY -->
	<div id="main">
	<? include("../_common/elements/search.php"); ?>
        <div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div
				class="row content_body content_my_page_body contents_registration_body">
			<? include("./_elements/mypage_tab.php"); ?>
			<? if(count($arr_output['manager_student_list'])){ ?>
			<!-- 학습매니져로 등록된 학생 결과  -->
				<div class="sub_contents_body_box text-left">
					<h4>
						<i class="fa fa-user" aria-hidden="true"></i> 학습매니저 <br
							class="visible-xs" />
						<span>학습매니저로 등록된 사용자들의 테스트 목록 입니다.</span>
					</h4>
				</div>
				<!--#################################-->
			<? foreach($arr_output['manager_student_list'] as $intFirstKey=>$arrManagerStudent){ //foreach 1 ?>
			<div class="sub_contents_body_box">
					<h4 style="border-bottom: 0px;"><?=$arrManagerStudent['student_info'][0]['name']?></h4>
				</div>
			<? if(count($arrManagerStudent['join_book'])){ ?>
			
			<? foreach($arrManagerStudent['join_book'] as $intKey=>$arrBook){ //foreach 2 ?>
			<? if($intKey%2==0){ ?>
				<div class="my_page_box">
			<? } ?>
				<!---------------------------------------->
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 my_page_box_in">
						<div class="content_header_area sub_content_header_test_top">

							<div
								class="col-xs-4 col-sm-5 col-md-5 col-lg-5 content_header_img">
								<a
									href="/smart_omr/my_page/my_edu_report_detail.php?bs=<?=md5($arrBook['seq']);?>&view=manager"><img
									src="<?=$arrBook['book_cover_img']?>"
									alt="<?=$arrBook['title']?>" />
								<p class="sr-only"><?=$arrBook['title']?></p></a>
							</div>
							<div
								class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
								<ul>
									<li><h3>
											<a
												href="/smart_omr/my_page/my_edu_report_detail.php?bs=<?=md5($arrBook['seq']);?>&view=manager&ms=<?=md5($arrManagerStudent['student_seq'])?>"><?=$arrBook['title']?></a>
										</h3></li>
									<li><span><i class="fa fa-ticket" aria-hidden="true"></i> 테스트</span><?=$arrBook['test_join_cnt']?>/<?=$arrBook['test_count']?> <small>(참여/총)</small></li>
									<li><span><i class="fa fa-users" aria-hidden="true"></i> 전체평균</span><?=$arrBook['avarage_score']?>점<small>/<?=$arrBook['total_record'][0]['user_count']?>명참여</small></li>
									<li class="border-none"><span><i class="fa fa-line-chart"
											aria-hidden="true"></i> 나의 점수</span><?=$arrBook['my_record'][0]['total_user_score']?><small>점</small></li>
									<!--li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span>2016-12-25</li>
			        	<li class="border-none"><span><i class="fa fa-user" aria-hidden="true"></i> 생성자</span>산이아범</li-->
								</ul>
								<!--a href="/smart_omr/exercise_book/detail.php" class="btn btn-danger col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check"aria-hidden="true"></i> 테스트 참여</a-->
							</div>

						</div>
					</div>
			<? if($intKey%2==1){ ?>
			</div>
			<? } ?>
			<? } //foreach 2 ?>
			
			<? }else{ ?>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
					<span>참여 목록이 없습니다. </span>
				</div>
			<? } ?>
			<? } //foreach 1 ?>
			<? }//if end ?>
			</div>

		</div>
        
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
	<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>