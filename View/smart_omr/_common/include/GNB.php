<!-- Menu toggle -->
<nav class="uk-navbar h-nav">
<!-- a href="#GNB" class="uk-navbar-toggle" data-uk-offcanvas></a-->
<div class="">
<div class="h-gnb-lms pull-left hidden-xs"></div>
<div class="h-gnb pull-left visible-xs"></div>
<a href="/smart_omr/"><img src="/smart_omr/_images/mama-omr-h-logo.png"/></a>
<a href="#"><i class="fa fa-cog" aria-hidden="true"></i></a>
<? if($_SESSION['smart_omr']['member_key']){ ?>
<button class="uk-button uk-navbar-flip _d_logout"><i class="uk-icon-sign-out"></i></button>
<? }else{ ?>
<button class="uk-button uk-navbar-flip" data-uk-offcanvas="{target:'#LOGIN'}"><i class="uk-icon-sign-in"></i></button>
<? } ?>

</div>
</nav>
    <a href="#menu" id="menuLink" class="menu-link h-gnb">
        <p></p>
        <p></p>
        <p></p>
    </a>
    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="/smart_omr/"><i class="fa fa-arrow-left" aria-hidden="true"></i>Home</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item <?=$viewID=="SOMR_INTRODUCE_INDEX"?'active':''?>"><a href="/smart_omr/introduce/" class="pure-menu-link"><i class="fa fa-pencil" aria-hidden="true"></i>MAMA OMR 소개</a></li>
                <? if($_SESSION['smart_omr']['member_key']){ ?>
                <li class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_REGISTRATION"?'active':''?>"><a href="/smart_omr/exercise_book/registration" class="pure-menu-link"><i class="fa fa-plus" aria-hidden="true"></i>문제집 등록</a></li>
                <? }else{ ?>
                <li class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_REGISTRATION"?'active':''?>"><a href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');" class="pure-menu-link"><i class="fa fa-plus" aria-hidden="true"></i>문제집 등록</a></li>
                <? } ?>
                

                <li class="pure-menu-item <?=$viewID=="SOMR_EXERCISE_BOOK_LIST"?'active':''?>" class="menu-item-divided pure-menu-selected">
                    <a href="/smart_omr/exercise_book/list" class="pure-menu-link"><i class="fa fa-bars" aria-hidden="true"></i>문제집 목록</a>
                </li>
				  <!--  
                <li class="pure-menu-item <?=$viewID=="SOMR_BBS_FAQ"?'active':''?>"><a href="/smart_omr/bbs/faq" class="pure-menu-link"><i class="fa fa-comments" aria-hidden="true"></i>자주 묻는 질문</a></li>
                  -->
                <li class="pure-menu-item <?=$viewID=="SOMR_MAIL_QUESTION"?'active':''?>"><a href="/smart_omr/mail/question" class="pure-menu-link"><i class="fa fa-envelope" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i>이메일 문의</a></li>
                <? if($_SESSION['smart_omr']['member_key']){ ?>
                <li class="pure-menu-item <?=$viewID=="SOMR_REG_MANAGER"?'active':''?>"><a href="/smart_omr/registration/manager" class="pure-menu-link"><i class="fa fa-stumbleupon-circle" aria-hidden="true"></i>학습매니저등록</a></li>
                <li class="pure-menu-item <?=$viewID=="SOMR_MY_PAGE_INDEX"?'active':''?>"><a href="/smart_omr/my_page/index" class="pure-menu-link"><i class="fa fa-user" aria-hidden="true"></i>마이 페이지</a></li>
                <? }else{ ?>
                <li class="pure-menu-item"><a href="javascript:halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');" class="pure-menu-link"><i class="fa fa-user" aria-hidden="true"></i>마이 페이지</a></li>
                <? } ?>
                <!--li class="pure-menu-item"><a href="#LOGIN'" class="pure-menu-link" data-uk-offcanvas><i class="fa fa-unlock-alt" aria-hidden="true"></i>로그인</a></li-->
            </ul>
        </div>
    </div>