<? $viewID = "INSTALL_ADM_SETTING"; ?>
<? include("../_common/include/header.php"); ?>
<input type="hidden" id="admin_flg" value="1"/>
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
						<li>아래 SNS 로그인을 통해 관리자 계정을 선택 합니다.</li>
						<li>관리자 계정은 전체 OMR을 수정 할 수 있습니다.</li>
						<li>관리자 설정이 완료되면 install 디렉터의 퍼미션을 0700 으로 변경해주세요.</li>
					</ul>
				</div>
			</li>
			<? if($arr_output['$API_key']['facebook']['app_id']!=''){ ?>
			<li><a href="#" class="list-group-item login_facebook _d_sns_btn" data-event-type="login" data-sns-type="facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i>페이스북 아이디로 로그인</a></li>
			<? }?>
			<? if($arr_output['$API_key']['naver']['client_id']!=''){ ?>
			<li>
			<a href="#" onclick="$('#naver_id_login_anchor').click();" class="list-group-item login_naver"><span><img src="/smart_omr/_images/naver_logo.png" /></span>네이버 아이디로 로그인</a>
			<div id="naver_id_login" style="position:absolute;top:-1000px;"></div>
			</li>
			<? } ?>
			<? if($arr_output['$API_key']['kakao']['client_id']!=''){ ?>
			<li><a href="#" onclick="loginWithKakao();" class="list-group-item login_kakao"><span><img src="/smart_omr/_images/kakao_logo.png" /></span>카카오톡 아이디로 로그인</a></li>
			<? } ?>
		</ul>
	</div>
</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>