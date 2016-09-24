/***** face init end ******/
window.fbAsyncInit = function() {
	FB.init({
		appId : fa_client_id,
		xfbml : true,
		version : 'v2.7'
	});
	FB.Event.subscribe('comment.create',function(comment_data){
	    fb_fetch_comment(comment_data, 'create') 
	  });	
	  FB.Event.subscribe('comment.remove',function(comment_data){
	    fb_fetch_comment(comment_data, 'remove') 
	  });
	  function fb_fetch_comment(comment_data, comment_action) {
		console.log(comment_data);
		console.log(comment_action);
		/*
		$.ajax({
 			url : '/_connector/yellow.501.php',
 			data:{
 				'viewID':"SET_COMMENT",
 				'comment_type': "facebook",
 				'comment_action': comment_action,
 				'comment_id': comment_data.commentID,
 				'url': comment_data.href,
 				'message': comment_data.message,
 				'q': $('.comment_div').attr('q')
 			},
 			type: 'POST',
 			dataType: 'json',
 			success: function(jsonResult){
 		  	}
 		});
 		*/
		/* 라이브시 적용*/
		FB.api('/me',{ locale: 'en_US', fields: 'name, email' },function(user){
			console.log(user);	
			FB.api('/me?fields=picture&type=large',function(data){
	        	 console.log(data);
	        	 console.log(data.picture.data.url);
	        	 $.ajax({
	     			url : '/_connector/yellow.501.php',
	     			data:{
	     				'viewID':"SET_COMMENT",
	     				'comment_type': "facebook",
	     				'comment_action': comment_action,
	     				'comment_id': comment_data.commentID,
	     				'url': comment_data.href,
	     				'message': comment_data.message,
	     				'user_name':user.name,
	     				'user_img_path':data.picture.data.url,
	     				'q': $('.comment_div').attr('q')
	     			},
	     			type: 'POST',
	     			dataType: 'json',
	     			success: function(jsonResult){
	     		  	}
	     		});
			 });
		 });
	  }
};

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/ko_KR/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
/** *** face init end ***** */

//var arrRequestParameter = {}; 
var arr_sns_reply_user_info;
var arrSnsInfo = {};
$(document).ready(function(){
	if($('._d_sns_btn').length)$('._d_sns_btn').click(function(){checkSnsLogin($(this).attr('sns_type'),$(this).attr('event_type'),$(this));});
	/*
	var strParmeter = ((window.location.href.split("?"))[1]);
	var arrRequest;
	var arrTemp;
	if(typeof strParmeter!='undefined'){
		arrRequest = ((window.location.href.split("?"))[1]).split('&');
		$.each( arrRequest, function( key, value ) {
			arrTemp = value.split('=');
			arrRequestParameter[arrTemp[0]] = arrTemp[1];
		});
	}
	*/
});
function checkSnsLogin(sns_type, event_type, objectSnsBtn) {
	//set sns send info
	setSnsSendInfo(sns_type,event_type,objectSnsBtn);
	console.log(arrSnsInfo);
	//chk login status
	switch (sns_type) {
	case ('facebook'):
		FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
				    // the user is logged in and has authenticated your
				    // app, and response.authResponse supplies
				    // the user's ID, a valid access token, a signed
				    // request, and the time the access token 
				    // and signed request each expire
				    //var uid = response.authResponse.userID;
				    //var accessToken = response.authResponse.accessToken;
					//open dialog
					//openSnsDialog(sns_type,event_type);
					setUserSnsInfo(sns_type,event_type);
					/*
					switch(event_type){
						case('login'):
							setUserSnsInfo(sns_type,event_type);
						break;
						case('maker_quiz_share'):
						case('solver_quiz_retult_share'):
							openSnsDialog(sns_type,event_type);
						break;
					}
					*/
				  } else if (response.status === 'not_authorized') {
				    // the user is logged in to Facebook, 
				    // but has not authenticated your app
					  console.log('not_authorized');
					  openSnsLogin(sns_type,event_type);
				  } else {
				    // the user isn't logged in to Facebook.
					  console.log('user isnt logged');
					  openSnsLogin(sns_type,event_type);
				  }
			 });
		break;
	}
};
function setUserSnsInfo(sns_type,event_type){
	switch(sns_type){
		case('facebook'):
			FB.api('/me',{ locale: 'en_US', fields: 'name, email' },function(user){
				console.log(user);
		         FB.api('/me/picture?type=large',function(data){
					 console.log(data.data);
				 	arr_sns_reply_user_info = {'user_id':user.id,'user_email':user.email,'user_name':user.name,'user_img_path':data.data.url};
				 	switch(event_type){
				 		case('login'):
				 			snsLogin(sns_type,event_type);
				 		break;
				 		case('maker_quiz_share'):
						case('solver_quiz_retult_share'):
							openSnsDialog(sns_type,event_type);
						break;
				 	}
				 });
			 });
		break;
	}
};
/*
 * event_type : login - snsLogin 함수 실행
 * */
function openSnsLogin(sns_type,event_type){
	switch(sns_type){
		case('facebook'):
			FB.login(function(response) {
			   if (response.authResponse) {
			     console.log('Welcome!  Fetching your information.... ');
			     switch(event_type){
			     	case('login'):
			     		setUserSnsInfo(sns_type,event_type);
			    	break;
			     	case("maker_quiz_share"):
			     	case("solver_quiz_retult_share"):
			     		openSnsDialog(sns_type, event_type);
			     	break;
			     }
			   } else {
			     console.log('User cancelled login or did not fully authorize.');
			   }
			// },{scope: 'publish_actions'});
			},{scope: 'email,user_likes',return_scopes: true});
		break;
	}
	
};
function snsLogin(sns_type,event_type){
	//console.log(arr_sns_reply_user_info);
	//return;
	switch(sns_type){
		case('facebook'):
			$.ajax({
				url : '/_connector/yellow.501.php',
				data:{
					'viewID':'SOCIAL_AUTH',
					'social_login_type':'fa',
					'sns_type': sns_type,
					'fa_id': arr_sns_reply_user_info.user_id,
					'fa_email': arr_sns_reply_user_info.user_email,
					'fa_name': arr_sns_reply_user_info.user_name,
					'admin_flg':$('#admin_flg').val()
					//'user_img_path': arr_sns_reply_user_info.user_img_path
				},
				type: 'POST',
				dataType: 'json',
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
		break;
	}
	
};
function openSnsDialog(sns_type,event_type){
	switch(sns_type){
		case('facebook'):
			snsFacebookSend(sns_type,event_type);
		break;
	}
};
function snsFacebookSend(sns_type,event_type){
	console.log(arrSnsInfo);
	switch(event_type){
		case('maker_quiz_share'):
		case('solver_quiz_retult_share'):
			FB.ui({
				method: 'share',
			    href: arrSnsInfo.link,
			    picture: arrSnsInfo.picture,
			    title: arr_sns_reply_user_info.user_name+" 님의 "+arrSnsInfo.sns_title+" 점수는!?",
			    description: arrSnsInfo.description,
			    caption: arrSnsInfo.caption
			  }, function(response){
				if (response && !response.error_code) {
			      console.log('Posting completed.');
			      //get user profile
			    } else {
			      console.log('Error while posting.');
			    }
			});
		break;
		case('sns_invite'):
			console.log(arrSnsInfo);
			FB.ui({
			    method: 'send',
			    link: arrSnsInfo.sns_share_link,
			    picture: arrSnsInfo.sns_share_img,
			    name: arrSnsInfo.sns_name,
			    description: arrSnsInfo.sns_msg
			  }, function(response){
				if (response && !response.error_code) {
			      console.log('Posting completed.');
			      //get user profile
			     FB.api('/me',function(user){
					 console.log(user);
					 if(user.id!=""){
						//save event share log
					    setSnsLog(sns_type,event_type,user.id,user.name);
					 }else{
						//fail
					     console.log('save event share fail');
					 }
				 });
				 /*
				 FB.api('/me/picture?type=large',function(data){
					 var myImg = data.data.url;
					 console.log(myImg);
				 });
				 */
			    } else {
			      console.log('Error while posting.');
			    }
			});
		break;
		case('sns_reply'):
			//1. FB.api sns 게시
			FB.api('/me/feed', 'post', { message: $('.reply_header .textarea').val() , scope: 'publish_actions'}, function(response) {
			  if (!response || response.error) {
			    alert('소셜 인증이 필요합니다.');
			  } else {
				//2. set local sns comment
			     setSnsReply(sns_type,event_type,response.id);
			  }
			});
		break;
	}
};
function setSnsSendInfo(sns_type,event_type,objectSnsBtn){
	arrSnsInfo['link'] = objectSnsBtn.attr('link');
	arrSnsInfo['picture'] = objectSnsBtn.attr('picture');
	arrSnsInfo['sns_title'] = objectSnsBtn.attr('sns_title');
	arrSnsInfo['description'] = objectSnsBtn.attr('description');
	arrSnsInfo['caption'] = objectSnsBtn.attr('caption');
	/*
	var arrReturn;
	//jquery get request parameter
	$.ajax({
		url : '/_connector/yellow.501.php',
		data:{
			'viewID':'GET_SNS_INFO',
			'quiz_seq':arrRequestParameter['q'],
			'sns_type': sns_type,
			'event_type': event_type,
			'record_seq': arrRequestParameter['record_seq']
		},
		type: 'POST',
		dataType: 'json',
		success: function(jsonResult){
			console.log(jsonResult);
			openSnsDialog(sns_type,event_type,jsonResult);
	  	}
	});
	return arrReturn;
	*/
};
function getSnsReply(intPage){
	$.ajax({
		url : '/client/template/_elements/sns_reply_element.php',
		data:{
			'ad_site_seq':objTemplate.strSiteSeq,
			'page': intPage,
			'user_id': arr_sns_reply_user_info?arr_sns_reply_user_info.user_id:null
		},
		type: 'POST',
		dataType: 'html',
		success: function(htmlResult){
			if(intPage==1){//initial
				$('#comment_div').html(htmlResult);
			}else{
				$('#comment_div').append(htmlResult);
			}
	  	}
	});
};
function sendSnsReply(sns_type,event_type){
	if(sns_type!="" && event_type!=""){
		checkSnsLogin(sns_type,event_type)
	}else{
		halert('로그인 후 작성이 가능합니다.');
	}
};
function setSnsReply(sns_type,event_type,post_id){
	$.ajax({
		url : '/_connector/yellow.501.php',
		data:{
			'viewID':'SET_SNS_REPLY',
			'ad_site_seq':objTemplate.strSiteSeq,
			'sns_type': sns_type,
			'event_type': event_type,
			'post_id': post_id,
			'user_id': arr_sns_reply_user_info.user_id,
			'user_name': arr_sns_reply_user_info.user_name,
			'user_img_path': arr_sns_reply_user_info.user_img_path,
			'comment': $('.reply_header .textarea').val()
		},
		type: 'POST',
		dataType: 'json',
		success: function(jsonResult){
			if(jsonResult.result){
				//get sns reply
				getSnsReply(1);
			}
	  	}
	});
};
function deleteSnsReply(sns_type,post_id,comment_seq){
	//delete facebook graph api
	FB.api(post_id, 'delete', function(response) {
	  if (!response || response.error) {
	    alert('소셜 인증이 필요합니다.');
	  } else {
		  $.ajax({
				url : '/_connector/yellow.501.php',
				data:{
					'viewID':'SET_SNS_REPLY',
					'ad_site_seq':objTemplate.strSiteSeq,
					'post_id': post_id,
					'user_id': arr_sns_reply_user_info.user_id,
					'delete_flg': 1
				},
				type: 'POST',
				dataType: 'json',
				success: function(jsonResult){
					//remove comment div
					$('#comment_'+comment_seq).remove();
			  	}
			}); 
	  }
	});
};
function setSnsLog(sns_type,event_type,user_id,user_name){
	switch(sns_type){
		case('facebook'):
			$.ajax({
				url : '/_connector/yellow.501.php',
				data:{
					'viewID':'SET_VIRAL_SNS_LOG',
					'sns_type':sns_type,
					'event_type':event_type,
					'str_site_seq':objTemplate.strSiteSeq,
					'user_id':user_id,
					'user_name':user_name,
					'cphone':$('#frmInviteSns #cphone').val()
				},
				type: 'POST',
				dataType: 'json',
				success: function(jsonResult){
					if(jsonResult.result){
						alert('이벤트에 참여해 주셔서 감사합니다.');
					}else{
						alert(jsonResult.err_msg);
					}
					
			  	}
			});
		break;
	}
};

