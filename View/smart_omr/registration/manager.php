<? $viewID = "SOMR_REG_MANAGER"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
	<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--#####################################################################-->
	<!--######################### Questions Request #########################-->
	<!--#####################################################################-->
	<div id="main">
		<div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div
				class="row content_body content_small_body contents_registration_body">
				<div class="sub_contents_body_box">
					<div class="h_dot">
						<div class="h_dot_box info-box info-box-ul">
							<h4
								style="text-align: center; margin-top: 10px; margin-bottom: 10px; border-bottom: 0px;">
								<i class="fa fa-arrow-right" aria-hidden="true"></i> 학습 매니저 등록안내
							</h4>
							<ul>
								<li>학습 매니저 등록이란? 학생의 테스트 결과 및 학습 기록을 체크하고 분석하여 학생의 학습을 도와 줄 수 있는 시스템입니다.</li>
								<li>아래의 이메일 입력 폼에 입력하여 등록한 이메일 주소로 로그인하여 해당 학생의  학습 기록을 열람할 수 있습니다.</li>
								<li>본인의 테스트 결과 및 학습 기록을 공유할 학습 매니저의 이메일 주소를 아래의 입력 폼에 입력 후 전송 버튼을 클릭하여 주십시오.</li>
								<li>학습 매니저 등록은 무제한으로 등록 가능합니다.</li>
							</ul>
						</div>
						<div class="info-img">
							<img src="/smart_omr/_images/info-basic.png" alt=" " />
						</div>
						<form name="frmMail" id="frmMail" onsubmit="return false;">
							<label for="receiver_email" class="sr-only">학습매니저 이메일 주소 입력</label><input
								type="text" class="form-control _d_chk_input"
								name="receiver_email" id="receiver_email"
								placeholder="학습매니저 이메일 주소 입력." />
							<button type="button"
								onclick="objCommon.sendMail('send_manager_request');"
								class="pure-button pure-form_in btn-block _d_btn_chk_isbn">
								<i class="fa fa-check" aria-hidden="true"></i> 전송
							</button>
						</form>
					</div>
				</div>
				<!--####################################################################################-->
			</div>
</div>
<? include("../_common/include/foot_menu.php"); ?>
	</div>
	</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>