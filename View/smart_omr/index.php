<? $viewID = "SOMR_INDEX"; ?>
<? include("./_common/include/header.php"); ?>
<?
/*
 echo "<pre>";
 var_dump($arr_output['manager']);
 echo "</pre>";
 exit;
*/
?>
<script>
$(document).ready(function(){
<? if(count($arr_output['manager'])){ ?>
alert('<?=$arr_output['manager']['manager_msg']?>');
<? }else if(!$_SESSION['smart_omr'] && $_GET['mat']){ ?>
alert('로그인 하시면 매니저로 등록됩니다.');
UIkit.offcanvas.show('#LOGIN');
<? } ?>
});
</script>
<!-- GNB -->
<div id="layout">
<? include("./_common/include/GNB.php"); ?>
	<!-- GNB -->
	<!-- CONTENTS BODY -->
	<div id="main">
	<!-- ------------------------------------------------------------------ -->
	<!-- 
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

	  <ol class="carousel-indicators">
	    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
	    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
	    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
	  </ol>
	
	  
	  <div class="carousel-inner" role="listbox" style="height: 300px;">
	    <div class="item active">
	      <img src="..." alt="...">
	      <div class="carousel-caption">
	        ...
	      </div>
	    </div>
	    <div class="item">
	      <img src="..." alt="...">
	      <div class="carousel-caption">
	        ...
	      </div>
	    </div>
	    ...
	  </div>
	  
	  
	  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>
	-->
	<!-- ------------------------------------------------------------------ -->
	<div class="main_search"><? include("./_common/elements/search.php"); ?></div>

		<a class="btn btn-success btn-lg work_book_reg_bt" href="/smart_omr/exercise_book/registration.php"><i class="fa fa-plus" aria-hidden="true"></i></a>
		<? foreach($arr_output['header'] as $intKey=>$arrHeader){ ?>
		<div class="content_header">
			<div class="content_header_area">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_header_img">
					<div class="fit_target" style="height: 272px; width: 220px; margin: 0 auto;">
						<a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrHeader['seq'])?>"><img class="fit_image" src="<?=$arrHeader['book_cover_img']?>" alt="<?=$arrHeader['title']?>" />
							<p class="sr-only">
							<?=$arrHeader['title']?>
							</p> </a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 content_header_list">
					<ul>
						<li class="border-none"><h3>
						<?=$arrHeader['title']?>
							</h3></li>
						<li><span><i class="fa fa-ticket" aria-hidden="true"></i> 테스트 수</span><?=count($arrHeader['book_test_list'])?> </li>
						<li><span><i class="fa fa-bars" aria-hidden="true"></i> 테스트 문항 수</span> <?=$arrHeader['question_count']?> <small>문항</small></li>
						<li><span><i class="fa fa-users" aria-hidden="true"></i> 참가자 수</span> <?=$arrHeader['total_record'][0]['user_count']?><small>명</small></li>
						<li><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span> <?=$arrHeader['avarage_score']?><small>점</small></li>
						<li><span><i class="fa fa-history" aria-hidden="true"></i> 생성일</span><?=substr($arrHeader['create_date'],0,10)?></li>
						<!-- 
						<li class="border-none"><span><i class="fa fa-user" aria-hidden="true"></i> 생성자</span> <?=$arrHeader['writer_info'][0]['name']?></li>
						 -->
					</ul>
					<a href="/smart_omr/exercise_book/detail.php?bs=<?=md5($arrHeader['seq'])?>" class="pure-button pure-form_in col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check" aria-hidden="true"></i> 테스트 참여</a>
				</div>

			</div>
		</div>
		<? } ?>
		<!--div class="content_class">
			  <div class="content_class_area">
			     
			      
			  </div>
			</div-->
		<div class="container-fluid" style="padding: 0px;">
			<div class="row content_body" id="book_list_div">
			<? include("./_common/elements/book_list_body.php");?>
			</div>
			<div id="loading" class="text-center">
				<img src="/smart_omr/_images/loading.gif" />
			</div>
		</div>


		<? include("./_common/include/foot_menu.php"); ?>
	</div>
	<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
		<? include("./_common/include/footer.php"); ?>
		<? include("./_common/include/bottom.php"); ?>