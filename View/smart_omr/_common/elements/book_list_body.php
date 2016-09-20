<? 
if(!$viewID){
	$viewID = "SOMR_EXERCISE_BOOK_LIST";
	include($_SERVER['DOCUMENT_ROOT']."/_connector/yellow.501.php");
}
?>
				<? if(count($arr_output['book_list'])){ ?>
				<? foreach($arr_output['book_list'] as $intKey=>$arrBook){ ?>
				<div class="workbook_cover col-xs-12 col-sm-6 col-md-4 col-lg-3">
						<div class="workbook_cover_box">
							<div class="fit_target" style="height:290px;">
								<a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrBook['seq'])?>"><img class="fit_image" src="<?=$arrBook['book_cover_img']?>" alt="<?=$arrBook['title']?>"/><p class="sr-only"><?=$arrHeader['title']?></p></a>
							</div>
							
							<div class="col-xs-12 content_body_list">
							<ul>
					        	<li><h3><?=$arrBook['title']?></h3></li>
					        	<li><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span><?=$arrBook['question_count']?> <small>문항</small></li>
					        	<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span><?=$arrBook['total_record'][0]['user_count']?><small>명</small></li>
					        	<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span><?=$arrBook['avarage_score']?><small>점</small></li>
					        	<li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span><?=substr($arrBook['create_date'],0,10)?></li>
					        	<!-- 
					        	<li class="border-none"><span><i class="fa fa-user" aria-hidden="true"></i> 생성자</span><?=$arrBook['writer_info'][0]['name']?></li>
					        	 -->
					    </ul>
					    <a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrBook['seq'])?>" class="pure-button pure-form_in col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check"aria-hidden="true"></i> 테스트 참여</a>
					    </div>
					</div>
				</div>
				<? } ?>
				<? } ?>