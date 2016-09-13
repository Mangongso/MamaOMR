<? $viewID = "SOMR_MAIL_QUESTION"; ?>
<? include("../_common/include/header.php"); ?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
<div id="main">
			

        	<div class="container-fluid sub_container-fluid">
        		<!--####################################################################################-->
			<div class="row content_body content_small_body contents_registration_body">
				<!------------------------------------------------------------->
				<div class="info-header">
				<span class="fa-stack fa-lg fa-3x">
				  <i class="fa fa-circle fa-stack-2x"></i>
				  <i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
				</span>
				<br/>
				<h3>궁금한 사항을 물어보세요!<br/><span>마마OMR에 궁금한 모든 사항을 보내주시면 신속한 답변드리겠습니다.</span></h3>
				</div>
				<div class="sub_contents_body_box">
				<form name="frmMail" id="frmMail" onsubmit="return false;">
				<!-----------------------cover-img-------------------------------------->
				<input type="text" class="form-control _d_chk_input" name="sender_email" id="sender_email" placeholder="답변받을 이메일주소를 입력하세요">
				<textarea name="contents" class="form-control _d_chk_input" cols="" rows="10" placeholder="문의 내용을 입력하세요"></textarea>
				<button type="button" onclick="objCommon.sendMail();" class="pure-button pure-form_in btn-block _d_btn_chk_isbn"><i class="fa fa-check" aria-hidden="true"></i> 전송</button>
				</form>
				</div>
			</div>
			<!--####################################################################################-->
		
        </div>
        
        
<? include("../_common/include/foot_menu.php"); ?>
</div>
<!-- CONTENTS BODY -->
</div>
<!-- CONTENTS BODY -->
<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>