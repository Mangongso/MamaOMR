<!-- ############################################################# -->
<!-- ######################### LOGIN Area ######################### -->
<!-- ############################################################# -->
<!-- Login START -->
<div id="LOGIN" class="uk-offcanvas">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
		<div class="list-group etc_login">
            <? if($API_key ['facebook'] ['app_id'] != "APP_ID" && trim ( $API_key ['facebook'] ['app_id'])){?>
            <a href="#" class="list-group-item login_facebook _d_sns_btn" data-event-type="login" data-sns-type="facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i>페이스북 아이디로 로그인</a>
              <? } ?>
			  <? if($API_key ['naver'] ['client_id'] != "CLIENT_ID" && trim ( $API_key ['naver'] ['client_id'] )){ ?>
            <a href="#" onclick="$('#naver_id_login_anchor').click();" class="list-group-item login_naver"><span><img src="/smart_omr/_images/naver_logo.png" alt="Naver Logo" /></span>네이버 아이디로 로그인</a>
            <div id="naver_id_login" style="position:absolute;top:-1000px;"></div>
              <? } ?>
            <? if($API_key ['kakao'] ['client_id'] != "CLIENT_ID" && trim ( $API_key ['kakao'] ['client_id'])){?>
            <a href="#" onclick="loginWithKakao();" class="list-group-item login_kakao"><span><img src="/smart_omr/_images/kakao_logo.png" alt="Kakao Talk Logo" /></span>카카오톡 아이디로 로그인</a>
        	 <? } ?>
        </div>
	</div>
</div>
<!-- Login END -->