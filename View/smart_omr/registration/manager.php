<? $viewID = "SOMR_REG_MANAGER"; ?>
<? include("../_common/include/header.php"); ?>
<!-- GNB -->
<div id="layout">
<? include("../_common/include/GNB.php"); ?>
<div id="main">
			

        	<div class="container-fluid sub_container-fluid">
        		<!--####################################################################################-->
			<div class="row content_body content_small_body contents_registration_body">
				<!------------------------------------------------------------->
				<div class="sub_contents_body_box">
				<form name="frmMail" id="frmMail" onsubmit="return false;">
				<!-----------------------cover-img-------------------------------------->
				<input type="text" class="form-control _d_chk_input" name="receiver_email" id="receiver_email" placeholder="학습매니저의 이메일로 매니저요청 메일이 발송됩니다.">
				<button type="button" onclick="objCommon.sendMail('send_manager_request');" class="btn btn-warning btn-lg btn-block _d_btn_chk_isbn"><i class="fa fa-paper-plane" aria-hidden="true"></i> 전송</button>
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