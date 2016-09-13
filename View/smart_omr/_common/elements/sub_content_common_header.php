		<div class="content_header sub_content_header">
			<div class="content_header_area">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_header_img">
					<div class="fit_target" style="height: 237px; width: 200px; margin: 0 auto;">
						<a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrBookInfo['seq'])?>"><img class="fit_image" src="<?=$arr_output['book_cover_img']?>" alt="<?=$arrBookInfo['title']?>" />
							<p class="sr-only">
							<?=$arrBookInfo['title']?>
							</p> </a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_body_list sub_content_body_list">
					<ul>
						<li><h3>
						<?=$arrBookInfo['title']?>
							</h3></li>
						<li><span><i class="fa fa-ticket" aria-hidden="true"></i> 테스트 수</span> <?=count($arr_output['book_test_list'])?>
						</li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span> <?=$arr_output['book_total_question_cnt']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span> <?=$arr_output['book_join_count']?><small>명</small></li>
						<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span> <?=$arr_output['book_score_avarage']?><small>점</small></li>
						<li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span> <?=substr($arrBookInfo['create_date'],0,10)?></li>
						<!-- 
			        	<li class="border-none"><span><i class="fa fa-user" aria-hidden="true"></i> 생성자</span><?=$arrBookInfo['writer_info'][0]['name']?></li>
			        	 -->
					</ul>
					<!--button type="button" data-toggle="modal" data-target="#registration_test" class="btn btn-danger col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check"aria-hidden="true"></i> 테스트 추가</button-->
				</div>
			</div>
		</div>