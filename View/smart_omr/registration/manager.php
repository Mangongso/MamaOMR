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
								<li>iPhone 7은 iPhone 사용 경험의 가장 중요한 요소들을 극적으로 향상시켜줍니다. 새롭게 선보이는
									첨단 카메라 시스템, iPhone 사상 최고의 성능과 배터리 사용 시간, 몰입감 넘치는 사운드를 들려주는 스테레오
									스피커, 가장 밝고 가장 컬러풀한 iPhone 디스플레이, 생활 방수 기능 등.1 그리고 강력한 성능만큼이나
									강렬한 외양. 이것이 바로 iPhone 7입니다.</li>
								<li>물 묻는 것쯤 아무렇지 않은 고고한 매력.</li>
								<li>무엇과도 다른 제트 블랙 마감. 생활 방수 기능을 갖춘 외장. 완전히 재설계된 홈 버튼. 게다가 매끄러운
									느낌의 새로운 unibody 디자인 덕분에 iPhone 7은 만지는 감촉도 보이는 외양도 놀라울 뿐이죠.</li>
								<li>새로운 블랙 모델은 비드 블라스트 공법으로 표면 처리된 알루미늄으로 제작되어 진하고 깊은 무광 마감을
									선보입니다. 고광택 제트 블랙 마감은 디자인 설계의 새로운 쾌거라 부를 만합니다. 정밀한 9단계 양극 산화 및
									광택 공정을 거쳐 탄생되어 너무나 순수하고 거칠 것 없는 블랙의 매력을 뽐냅니다.2 알루미늄과 글래스의 경계가
									느껴지지 않을 정도로 말이죠. 자, 이제 어둠 속으로 빨려 들어갈 시간입니다.</li>
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