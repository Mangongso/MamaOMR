<? $viewID = "SOMR_EXERCISE_BOOK_LIST"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
	<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--######################################################################-->
	<!--######################### Exercise book List #########################-->
	<!--######################################################################-->
	<div id="main">
	<? include("../_common/elements/search.php"); ?>
		<!--##################################################################################-->
		<!--######################### Contents Upload Button Include #########################-->
		<!--##################################################################################-->
		<!-- Upload Button START -->
		<a class="btn btn-success btn-lg work_book_reg_bt"
			href="/smart_omr/exercise_book/registration.php" title="컨텐츠 추가"><i
			class="fa fa-plus" aria-hidden="true"></i><span class="sr-only">컨텐츠
				추가</span></a>
		<!-- Upload Button END -->
		<!-- ################################################################# -->
		<div class="pure-menu pure-menu-horizontal pure-menu-scrollable">
			<a
				href="#" onclick="objCommon.frmReset();$('#frmSearch .search-bt').click();"
				class="pure-menu-link pure-menu-heading">전체</a>
			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(32);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 초등</a></li>
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(33);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 중등</a></li>
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(34);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 고등</a></li>
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(35);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 수능</a></li>
				<!-- 
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(36);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 대학교재</a></li>
				 -->
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(37);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 취업/수험서</a></li>
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(38);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 외국어</a></li>
				<li class="pure-menu-item"><a
					href="#" onclick="$('#frmSearch #category_seq').val(39);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 기타</a></li>
			</ul>
		</div>
		<!-- ################################################################# -->
		<div class="container-fluid" style="padding: 0px;">
		<? if(count($arr_output['book_list'])){ ?>
			<div class="row content_body" id="book_list_div">
			<? include("../_common/elements/book_list_body.php");?>
			</div>
			<? }else if(!count($arr_output['book_list']) && $arr_output['search_flg']==1){ ?>
			<div class="row content_body" id="book_list_div">
				<div class="h_dot_box info_box" style="top: 0px;">
				<i class="fa fa-exclamation-circle" aria-hidden="true"></i><br>
				검색된 정보가 없습니다.
				</div>
			</div>
			<? }else{ ?>
			<div class="row content_body" id="book_list_div">
				<div class="h_dot_box info_box" style="top: 0px;">
					<i class="fa fa-exclamation-circle" aria-hidden="true"></i><br> 
					<span>등록된 문제집이 없습니다. 마마OMR의 첫 문제집을 등록해 보세요~</span><br><br>
					<button type="button" class="btn btn-primary btn-md search-bt" onclick="location.href='/smart_omr/exercise_book/registration'">문제집 등록하기</button>
				</div>
			</div>
			<? } ?>
			<div id="loading" class="text-center" style="display: none;">
				<img src="/smart_omr/_images/loading.gif" alt=" " />
			</div>
		</div>
		<? include("../_common/include/foot_menu.php"); ?>
	</div>
</div>
<? include("../_common/include/footer.php"); ?>
		<? include("../_common/include/bottom.php"); ?>