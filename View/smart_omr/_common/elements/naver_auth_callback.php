<? $viewID = "NAVER_AUTH_CALLBACK"; ?>
<? include("../include/header.php"); ?>

<!-- 네이버아디디로로그인 Callback페이지 처리 Script -->
<script type="text/javascript">
	var naver_id_login = new naver_id_login(naverClientID , naverCallBackURL );
	// 네이버 사용자 프로필 조회 이후 프로필 정보를 처리할 callback function
	function naverSignInCallback() {
		// naver_id_login.getProfileData('프로필항목명');
		// 프로필 항목은 개발가이드를 참고하시기 바랍니다.
		$.ajax({
			url : '/_connector/yellow.501.php',
			data:{
				'viewID':'SOCIAL_AUTH',
				'social_login_type':'na',
				'access_token': naver_id_login.getAccessToken()
				//'user_img_path': arr_sns_reply_user_info.user_img_path
			},
			type: 'POST',
			dataType: 'json',
			success: function(jsonResult){
				if(jsonResult.result){
					window.opener.location.reload()
				}
				self.close();
		  	}
		});
	}

	// 네이버 사용자 프로필 조회
	naver_id_login.get_naver_userprofile("naverSignInCallback()");
</script>
<!-- //네이버아디디로로그인 Callback페이지 처리 Script -->
<? include("../include/footer.php"); ?>