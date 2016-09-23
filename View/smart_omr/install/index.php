<? include("../_common/include/header.php"); ?>
<!--####################################################################-->
<!--######################### MAMA OMR INSTALL #########################-->
<!--####################################################################-->
<div class="row">
	<div class="col-md-3 install_body etc_login">
		<ul>
			<li><img src="/smart_omr/_images/mama-omr-h-logo.png"
				class="install_logo" /></li>
			<li>
				<div class="h_dot_box info-box-ul install-info">
					<ul>
						<li>오답 문제를 스마트 폰으로 촬영 하신 후 "사진 등록" 버튼을 클릭 하시고 오답 사진을 업로드 하여 주십시오.</li>
						<li>사진은 최대한 밝고 외곡 없이 촬영하여 주십시오.</li>
						<li>사진을 올리신 후 텍스트 추출 버튼을 클릭하시면 이미지 상의 문제가 텍스트화 됩니다.</li>
						<li>저장 버튼을 클릭하시면 문제가 저장됩니다.</li>
					</ul>
				</div>
			</li>
			<li><a href="javascript:void(0);" sns_type="facebook"
				event_type="login" class="list-group-item login_facebook _d_sns_btn"><i
					class="fa fa-facebook-official" aria-hidden="true"></i>페이스북 아이디로
					로그인</a></li>
			<li><a href="javascript:$('#naver_id_login_anchor').click();"
				class="list-group-item login_naver"><span><img
						src="/smart_omr/_images/naver_logo.png" /></span>네이버 아이디로 로그인</a></li>
			<li><a href="javascript:loginWithKakao();"
				class="list-group-item login_kakao"><span><img
						src="/smart_omr/_images/kakao_logo.png" /></span>카카오톡 아이디로 로그인</a></li>
			<li><input class="form-control input-lg" type="text"
				placeholder="MySQL Host"></li>
			<li><input class="form-control input-lg" type="text"
				placeholder="MySQL User" style="margin-top: -1px;"></li>
			<li><input class="form-control input-lg" type="text"
				placeholder="MySQL Password" style="margin-top: -1px;"></li>
			<li style="padding: 1px;"><button type="button"
					class="pure-button pure-form_in btn-lg btn-block install_bt"
					onclick="location.href='install.php'";>
					<i class="fa fa-cog fa-spin"></i> Install
				</button></li>
		</ul>
	</div>
</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>