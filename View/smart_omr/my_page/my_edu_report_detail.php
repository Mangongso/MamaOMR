<? $viewID = "SOMR_MY_PAGE_MY_EDU_REPORT_DETAIL"; ?>
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
	<!--? include("../_common/elements/search.php"); ?-->
	<a class="btn btn-success btn-lg work_book_reg_bt" href="/smart_omr/exercise_book/registration.php"><i class="fa fa-plus"aria-hidden="true"></i></a>
			<!---------------------------------------->
			<? include($_SERVER['DOCUMENT_ROOT']."/smart_omr/_common/elements/sub_content_common_header.php"); ?>
			<!---------------------------------------->
			
        	<div class="container-fluid sub_container-fluid">
        		<!--####################################################################################-->
			<div class="row content_body content_small_body">
				<!------------------------------------------------------------->
				<? foreach($arr_output['book_test_list'] as $intKey=>$arrTest){ ?>
				<div class="sub_contents_body_box">
				<h4><?=$arrTest['subject']?><a class="uk-float-right uk-button uk-button-mini">오답노트 <i class="fa fa-arrow-right" aria-hidden="true"></i></a></h4>
				<ul>
					<li class="col-lg-4"><span><i class="fa fa-bars" aria-hidden="true"></i> 문항 수</span><?=$arrTest['test_question_cnt']?> <small>문항</small></li>
			        <li class="col-lg-4"><span><i class="fa fa-users" aria-hidden="true"></i> 참가자</span><?=$arrTest['test_record'][0]['user_count']?><small>명</small></li>
			        <li class="col-lg-4"><span><i class="fa fa-line-chart" aria-hidden="true"></i> 평균점수</span><?=round($arrTest['score_avarage'])?><small>점</small></li>
					<li>
					<div class="my_page_sub_menu">
			  		<!--h2><a href="/smart_omr/"><img src="/smart_omr/_images/top_logo.png" class="foot_logo"/></a></h2-->
			  			<div class="pure-menu pure-menu-horizontal pure-menu-scrollable scrollable-menu">
					    <ul id="foot_menu-tabs" class="pure-menu-list" >
					    	<? if(count($arrTest['my_record_list'])){?>
					    	<? foreach($arrTest['my_record_list'] as $intSubKey=>$arrMyRecordList){ ?>
					        <li class="pure-menu-item">
					        	<? if($_GET['view']=='manager'){ ?>
					        	<a href="/smart_omr/exercise_book/test_result.php?t=<?=md5($arrTest['test_seq'])?>&revision=<?=$arrMyRecordList['revision']?>&view=manager&ms=<?=$_GET['ms']?>" class="pure-menu-link"><i class="uk-icon-caret-right"></i><b><?=$intSubKey+1?>-</b><?=$arrMyRecordList['user_score']?><small>점</small></a>
					        	<? }else{ ?>
					        	<a href="/smart_omr/exercise_book/test_result.php?t=<?=md5($arrTest['test_seq'])?>&revision=<?=$arrMyRecordList['revision']?>" class="pure-menu-link"><i class="uk-icon-caret-right"></i><b><?=$intSubKey+1?>-</b><?=$arrMyRecordList['user_score']?><small>점</small></a>
					        	<? } ?>
					        </li>
					        <? } ?>
					        <? }else{ ?>
					        <li class="pure-menu-item"><small>문제풀이 내역이 없습니다.</small></li>
					        	
					        <? } ?>
					    </ul>
					  </div>
					</div>
					</li>
				</ul>
				</div>
				<? } ?>
				<!------------------------------------------------------------->
			</div>
			<!--####################################################################################-->
		
        </div>
        
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>