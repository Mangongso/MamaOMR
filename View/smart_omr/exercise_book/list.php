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
				href="javascript:void(0);" onclick="objCommon.frmReset();$('#frmSearch .search-bt').click();"
				class="pure-menu-link pure-menu-heading">전체</a>
			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(32);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 초등</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(33);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 중등</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(34);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 고등</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(35);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 수능</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(36);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 대학교제</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(37);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 취업/수험서</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(38);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 외국어</a></li>
				<li class="pure-menu-item"><a
					href="javascript:void(0);" onclick="$('#frmSearch #category_seq').val(39);$('#frmSearch .search-bt').click();"
					class="pure-menu-link"><i class="uk-icon-caret-right"></i> 기타</a></li>
			</ul>
		</div>
		<!-- ################################################################# -->
		<div class="container-fluid" style="padding: 0px;">
		<? if(count($arr_output['book_list'])){ ?>
			<div class="row content_body" id="book_list_div">
			<? include("../_common/elements/book_list_body.php");?>
			</div>
			<? }else{ ?>
			<div class="row content_body" id="book_list_div">
				<div class="workbook_cover col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<span>조회된 내역이 없습니다.</span>
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