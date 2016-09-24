var accessToken,authRes;

Kakao.init(ka_client_id);
var ka_token = Kakao.Auth.getAccessToken();
var session_token = ka_session_token;
if(ka_token==null || ka_token=='' || ka_token=='undefined'){
	/*
	if(Kakao.Auth.getRefreshToken()!=null){
		//call refresh token
	}
	*/
}else{
	if( (ka_token && session_token=='undefined') || ka_token!=session_token){//비정상 로그인
		Kakao.Auth.logout(function(obj){
			if(obj==true){
				location.href='/smart_omr';
			}
		});
	}
}
function loginWithKakao() {
    // 로그인 창을 띄웁니다.
    Kakao.Auth.login({
      success: function(authObj) {
    	// 로그인 성공시 API를 호출합니다.
		Kakao.API.request({
		url: '/v1/user/me',
		success: function(res) {
			accessToken = authObj.access_token;
			authRes = res;
			authKakaoUser();
		},
		fail: function(error) {
		}
		});
      },
      fail: function(err) {
        alert(JSON.stringify(err))
      }
    });
};
function authKakaoUser() {
	$.ajax({
		url: '/_connector/yellow.501.php',
		data:{'viewID':'SOCIAL_AUTH','access_token':accessToken,'auth_data':authRes,'social_login_type':'ka','admin_flg':$('#admin_flg').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend:function(){
		},
		success: function(jsonResult){
			if(jsonResult.result){
				if($('#admin_flg').val()){
					location.href='/';
				}else{
					location.reload();
				}
			}else{
				alert('인증오류');
				objCommon.logOut();
			}
		}
	});	
// 	console.log(authRes);
// 	return false;
// 	if(cookieSaveFlg){
// 		$.cookie("sns_info", {'sns_type':'ka','name':authRes.properties.nickname});
// 		location.href='adm_setting_2.php';
// 	}else{
		
// 	}
};
function checkMGUser() {
    // 로그인 창을 띄웁니다.
	$.ajax({
		url: '/_connector/yellow.501.php',
		data:{'viewID':'CHECK_SOCIAL_USER','auth_data':authRes,'social_login_type':'kakao'},
		type: 'POST',
		dataType: 'json',
		beforeSend:function(){
		},
		success: function(jsonResult){
			if(jsonResult.result){
				authKakaoUser();
			}else{
				//$('#vcModal').modal('show');
				authKakaoUser();
			}
		}
	});	
};
function agreeUser() {
	if(!$('#agree1').is(':checked')){
		alert('이용약관에 동의하셔야 합니다.');
	}else if(!$('#agree2').is(':checked')){
		alert('개인정보취급방침에 동의하셔야 합니다.');
	}else{
		authKakaoUser();
	}
};
