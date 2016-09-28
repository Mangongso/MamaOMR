<?
$viewID = "SOMR_MY_PAGE_MANAGING";
include ("../_common/include/header.php");
$tabSelected = 3;
?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--####################################################################-->
	<!--######################### My Page Managing #########################-->
	<!--####################################################################-->
	<div id="main">
		<!-- ########################## -->
		<div class="sub_content_header">
			<div class="content_header_area sub_content_header_test_top">
				<!-- ############# Search START ############# -->
			<? include("../_common/elements/search.php"); ?>
			<!-- ############ Search END ############## -->
			</div>
		</div>
		<!-- ########################## -->
		<!-- ########################## -->
		<div class="container-fluid mypage_container-fluid">
			<div
				class="row content_body content_my_page_body contents_registration_body">
			<? include("./_elements/mypage_tab.php"); ?>
				<div class="sub_contents_body_box text-left">
					<h4>
						<i class="fa fa-arrow-right" aria-hidden="true"></i> 마이 페이지 / 나의
						메니징 <br class="visible-xs" /> <span>메니징 받고 있는 테스트 목록입니다.</span>
					</h4>
				</div>
			<? if(count($arr_output['manager_student_list'])){ ?>
			<?php 
			print "<pre>";
			print_r($arr_output['manager_student_list']);
			print "</pre>";
			?>
			<? foreach($arr_output['manager_student_list'] as $intFirstKey=>$arrManagerStudent){ //foreach 1 ?>
				<div class="sub_contents_body_box">
					<h4 style="border-bottom: 0px;"><?=$arrManagerStudent['student_info'][0]['name']?></h4>
				</div>
 	
 			<? if(count($arrManagerStudent['join_book'])){ ?>
 			<? foreach($arrManagerStudent['join_book'] as $intKey=>$arrBook){ //foreach 2 ?>
			<? if($intKey%2==0){ ?>
				<div class="my_page_box">
			<? } ?>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 my_page_box_in">
						<div class="content_header_area sub_content_header_test_top">
							<div
								class="col-xs-4 col-sm-5 col-md-5 col-lg-5 content_header_img">
								<a
									href="/smart_omr/my_page/my_managing_report_detail.php?bs=<?=md5($arrBook['seq']);?>&sk=<?=$arrManagerStudent['student_info'][0]['member_key']?>"><img
									src="<?=$arrBook['book_cover_img']?>"
									alt="<?=$arrBook['title']?>" class="content_cover_img" />
									<p class="sr-only"><?=$arrBook['title']?></p></a>
							</div>
							<div
								class="col-xs-8 col-sm-7 col-md-7 col-lg-7 content_body_list sub_content_body_list">
								<ul>
									<li><h3>
											<a
												href="/smart_omr/my_page/my_managing_report_detail.php?bs=<?=md5($arrBook['seq']);?>&sk=<?=$arrManagerStudent['student_info'][0]['member_key']?>"><?=$arrBook['title']?></a>
										</h3></li>
									<li><span><i class="fa fa-ticket" aria-hidden="true"></i> 테스트</span><?=$arrBook['test_join_cnt']?>/<?=$arrBook['test_count']?> <small>(참여/총)</small></li>
									<li><span><i class="fa fa-users" aria-hidden="true"></i> 전체평균</span><?=$arrBook['avarage_score']?>점<small>/<?=$arrBook['total_record'][0]['user_count']?>명참여</small></li>
									<li><span><i class="fa fa-line-chart" aria-hidden="true"></i>
											나의 점수</span><?=$arrBook['my_record'][0]['total_user_score']?><small>점</small></li>
								</ul>
							</div>

						</div>
					</div>
			<? if($intKey%2==1){ ?>
			</div>
			<? } ?>
			<? } ?>
			<? }else{ ?>
				<div class="my_page_box">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
					<span>참여 목록이 없습니다. </span>
				</div>
				</div>
			<? } ?>
			<? } ?>
			<? }else{ ?>
				<div class="my_page_box">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
					<span>메니져를 신청한 학생이 없습니다.</span>
				</div>
				</div>
			<? } ?>
			</div>
		</div>
		<!-- ########################## -->
<? include("../_common/include/foot_menu.php"); ?>
</div>
</div>
</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>