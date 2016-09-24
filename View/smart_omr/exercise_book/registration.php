<? $viewID = "SOMR_EXERCISE_BOOK_REGISTRATION"; ?>
<? include("../_common/include/header.php"); ?>
<div id="layout">
<!-- GNB START -->
<? include("../_common/include/GNB.php"); ?>
<!-- GNB END -->
	<!--##############################################################################-->
	<!--######################### Exercise book Registration #########################-->
	<!--##############################################################################-->
	<script>
    $(function(){
        var progressbar = $("#progressbar"),
            bar         = progressbar.find('.uk-progress-bar'),
            settings    = {

            action: '/', // upload url

            allow : '*.(jpg|jpeg|gif|png)', // allow only images

            loadstart: function() {
                bar.css("width", "0%").text("0%");
                progressbar.removeClass("uk-hidden");
            },

            progress: function(percent) {
                percent = Math.ceil(percent);
                bar.css("width", percent+"%").text(percent+"%");
            },

            allcomplete: function(response) {

                bar.css("width", "100%").text("100%");

                setTimeout(function(){
                    progressbar.addClass("uk-hidden");
                }, 250);

                alert("Upload Completed")
            }
        };

        var select = UIkit.uploadSelect($("#upload-select"), settings),
            drop   = UIkit.uploadDrop($("#upload-drop"), settings);
    });
</script>
	<div id="main">
		<div class="container-fluid sub_container-fluid">
			<!--####################################################################################-->
			<div
				class="row content_body content_small_body contents_registration_body">
				<div class="sub_contents_body_box">
					<form name="frmIsbn" id="frmIsbn" onsubmit="return false;">
						<span class="barcode_box"> <i class="fa fa-barcode"
							aria-hidden="true"></i><i class="fa fa-barcode"
							aria-hidden="true"></i><i
							class="fa fa-barcode fa-flip-horizontal" aria-hidden="true"></i><br />
							ISBN CODE
						</span>
						<h2>
							문제집 ISBN번호 등록<span></span>
						</h2>
						<!-- ##cover-img## -->
						<div class="cover_img_title">
							<div class="cover_img">
								<img class="_d_cover_img" src="/smart_omr/_images/default_cover.png" alt="문제집 커버">
							</div>
							<div
								class="col-xs-12 col-sm-12 col-md-12 col-lg-12 _d_book_info content_body_list sub_content_body_list"
								style="display: none;">
								<ul class="text-center">
									<!-- li class="text-center"><h3 id="book_title"></h3></li-->
									<li><span><i class="fa fa-users" aria-hidden="true"></i> 출판사</span><span
										style="width: auto;" id="book_publisher"></span></li>
									<li><label for="category_seq" class="sr-only">카테고리 선택</label><select class="form-control input-sm _d_chk_input"
										name="category_seq" id="category_seq">
											<option value="">카테고리 선택</option>
											<option value="32">초등</option>
											<option value="33">중등</option>
											<option value="34">고등</option>
											<option value="35">수능</option>
											<option value="36">대학교재</option>
											<option value="37">취업/수험서</option>
											<option value="38">외국어</option>
											<option value="39">기타</option>
									</select></li>
								</ul>
								<!--a href="/smart_omr/exercise_book/detail.php" class="btn btn-danger col-lg-12 btn-lg btn-block content_header_list_bt"><i class="fa fa-check"aria-hidden="true"></i> 테스트 참여</a-->
							</div>
							<div id="progressbar" class="uk-progress uk-hidden"
								style="margin-top: 0px; border-radius: 0px;">
								<div class="uk-progress-bar" style="width: 0%;">...</div>
							</div>
						</div>
						<!-- ##cover-img## -->
						<label for="isbn_code" class="sr-only">ISBN 번호 입력</label> <input
							type="text"
							class="form-control input-lg text-center _d_chk_input"
							name="isbn_code" id="isbn_code" placeholder="ISBN 번호 입력" />
				<? if($_SESSION['smart_omr']['member_key']){ ?>
				<button type="button"
							onclick="objRegistration.checkBook('frmIsbn');"
							class="pure-button pure-form_in btn-block _d_btn_chk_isbn">
							<i class="fa fa-check" aria-hidden="true"></i> 확인
						</button>
				<? }else{ ?>
				<button type="button"
							onclick="halert('로그인이 필요합니다.');UIkit.offcanvas.show('#LOGIN');"
							class="pure-button pure-form_in btn-block _d_btn_chk_isbn">
							<i class="fa fa-check" aria-hidden="true"></i> 확인
						</button>
				<? } ?>
				<button type="button"
							class="pure-button pure-form_in btn-block _d_btn_reg_isbn"
							style="display: none;">
							<i class="fa fa-check" aria-hidden="true"></i> 등록
						</button>
					</form>
				</div>
				<div class="bt_set">
				</div>
				<form>
					<div class="collapse col-xs-12 col-sm-12 col-md-12 col-lg-12"
						id="collapseExample">
						<!-- ##cover-img## -->
						<div class="cover_img_title">
							<div id="upload-drop" class="uk-placeholder cover_img">
								<span><i class="fa fa-arrow-down" aria-hidden="true"></i> 커버 이미지
									선택</span><br />
								<i class="fa fa-plus" aria-hidden="true"></i><label for="upload-file" class="sr-only">Upload File</label><input id="upload-file" type="file" />
							</div>

							<div id="progressbar2" class="uk-progress uk-hidden"
								style="margin-top: 0px; border-radius: 0px;">
								<div class="uk-progress-bar" style="width: 0%;">...</div>
							</div>
						</div>
						<!-- ##cover-img## -->
						<div class="registered_title">
							<label for="e_b-name" class="sr-only">문제집 이름 입력</label><input type="text" class="form-control input-lg" id="e_b-name" placeholder="문제집 이름 입력" />
							<label for="publisher-name" class="sr-only">출판사 명</label><input type="text" class="form-control input-lg" id="publisher-name" placeholder="출판사 명" style="margin-top: -1px;" />
							<button type="button" class="btn btn-primary btn-lg btn-block"><i class="fa fa-check" aria-hidden="true"></i> 등록</button>
						</div>
					</div>
				</form>
				<div class="h_dot h_dot_li">
					<div class="h_dot_box">
						<ul>
							<li>등록하시려는 문제집 ISBN번호를 확인하신 후 해당 ISBN번호를 입력하여 주십시오.</li>
							<li>ISBN번호는 문제집 뒷면의 바코드부분에 있습니다.</li>
							<li>ISBN 번호를 입력 후 등록 버튼을 클릭하시면 문제집 정보가 자동으로 등록됩니다.</li>
						</ul>
					</div>
				</div>
			</div>
			<!--####################################################################################-->

		</div>
        
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
</div>
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>