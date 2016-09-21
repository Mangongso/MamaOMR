<!--############################################################################-->
<!--######################### Toggle Menu(GNB) Include #########################-->
<!--############################################################################-->
<!-- Top GNB Area START-->
<!-- Top GNB Gray Area START-->
<nav class="uk-navbar h-nav">
	<div class="">
		<div class="h-gnb-lms pull-left hidden-xs"></div>
		<div class="h-gnb pull-left visible-xs"></div>
		<a href="/smart_omr/" title="MamaOMR Home"><img
			src="/smart_omr/_images/mama-omr-h-logo.png" alt="MamaOMR Home" /></a>
<? if($_SESSION['smart_omr']['member_key']){ ?>
<button class="uk-button uk-navbar-flip _d_logout">
			<i class="fa fa-user" aria-hidden="true"></i>
		</button>
<? }else{ ?>
<button class="uk-button uk-navbar-flip"
			data-uk-offcanvas="{target:'#LOGIN'}">
			<i class="fa fa-user" aria-hidden="true"></i>
		</button>
<? } ?>
</div>
</nav>
<!-- Top GNB Gray Area END -->
<!-- Small GNB Tab START-->
<a href="#menu" id="menuLink" class="menu-link h-gnb"
	title="Main Menu Tab"><span class="sr-only">Main Menu Tab</span>
	<p></p>
	<p></p>
	<p></p>
</a>
<!-- Small GNB Tab END -->
<!-- Side Menu List START-->
<div id="menu">
	<div class="pure-menu">
		<a class="pure-menu-heading" href="/smart_omr/" title="Home"><i
			class="fa fa-arrow-left" aria-hidden="true"></i>Home</a>
		<!-- GNB MENU LIST START -->
		<ul class="pure-menu-list">
			<li
				class="pure-menu-item <?=$viewID=="SOMR_INTRODUCE_INDEX"?'active':''?>"><a
				href="/smart_omr/introduce/" class="pure-menu-link"
				title="MAMA OMR 소개"><i class="fa fa-pencil" aria-hidden="true"></i>MAMA
					OMR 소개</a></li>
                <? if($_SESSION['smart_omr']['member_key']){ ?>
                <li
				class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_REGISTRATION"?'active':''?>"><a
				href="/smart_omr/exercise_book/registration" class="pure-menu-link"
				title="문제집 등록"><i class="fa fa-plus" aria-hidden="true"></i>문제집 등록</a></li>
                <? }else{ ?>
                <li
				class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_REGISTRATION"?'active':''?>"><a
				href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
				class="pure-menu-link" title="문제집 등록"><i class="fa fa-plus"
					aria-hidden="true" title=""></i>문제집 등록</a></li>
                <? } ?>
                <li
				class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_LIST"?'active':''?>"><a
				href="/smart_omr/exercise_book/list" class="pure-menu-link"
				title="문제집 등록"><i class="fa fa-bars" aria-hidden="true"></i>문제집 목록</a></li>
			<li
				class="pure-menu-item <?=$viewID=="SOMR_MAIL_QUESTION"?'active':''?>"><a
				href="/smart_omr/mail/question" class="pure-menu-link"
				title="이메일 문의"><i class="fa fa-envelope" aria-hidden="true"
					data-toggle="modal" data-target="#myModal"></i>이메일 문의</a></li>
                <? if($_SESSION['smart_omr']['member_key']){ ?>
                <li
				class="pure-menu-item <?=$viewID=="SOMR_REG_MANAGER"?'active':''?>"><a
				href="/smart_omr/registration/manager" class="pure-menu-link"
				title="학습 매니저 등록"><i class="fa fa-eye" aria-hidden="true"></i>학습 매니저
					등록</a></li>
			<li
				class="pure-menu-item <?=$viewID=="SOMR_MY_PAGE_INDEX"?'active':''?>"><a
				href="/smart_omr/my_page/index" class="pure-menu-link"
				title="마이 페이지"><i class="fa fa-user" aria-hidden="true"></i>마이 페이지</a></li>
                <? }else{ ?>
                <li class="pure-menu-item"><a
				href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
				class="pure-menu-link" title="마이 페이지"><i class="fa fa-user"
					aria-hidden="true"></i>마이 페이지</a></li>
                <? } ?>
		</ul>
		<!-- GNB MENU LIST END -->
	</div>
</div>
<!-- Side Menu List END-->
<!-- Top GNB Area END -->