$(document).ready(function() {
	objCommon = new Common();
	objCommon.init();
});

function Common() {
	this.page = 2;
	this.before_page = 1;
	this.init = function() {
		if($('#book_list_div').length){
			$( window ).scroll(function() {
				var window_bottom = $(window).scrollTop() + $(window).height();
				var show_list_bottom = $('#book_list_div').position().top+$('#book_list_div').outerHeight(true);
				if(window_bottom > show_list_bottom){
					if(objCommon.page > objCommon.before_page){
						$('#loading').css('display','block');
						objCommon.before_page++;
						$.ajax({
							url: '/smart_omr/_common/elements/book_list_body.php',
							data:{'page':objCommon.page,search_key:$('#frmSearch #before_search_key').val()},
							type: 'POST',
							dataType: 'html',
							success: function(htmlResult){
								if($.trim(htmlResult)==''){
									$('#loading').css('display','none');
								}else{
									$('#book_list_div').append(htmlResult);
									$('#loading').css('display','none');
									objCommon.page++;
									objCommon.fitImages();
								}
						  	}
						});
					}
				}
			});
		}
		if($('#frmSearch').length){
			$('#frmSearch .search-bt').on('click',function(){objCommon.frmSearch();});
			$('#frmSearch #search_key').on('keyup', function(e) {if(e.which == 13) {objCommon.frmSearch();}});
		}
		if($('._d_logout').length){
			$('._d_logout').on('click',function(){objCommon.logOut();});
		}
		if($('.fit_image').length){
			objCommon.fitImages();
		}
		if($('.pure-menu-item').length){
			$('.pure-menu-item').on('click',function(){
				$('.pure-menu-item').removeClass('active');
				$(this).addClass('active');
			});
		}
		//set comment div
		if($('#comment_div[comment_seq]').length>0){
			objCommon.getComment($('#comment_div').attr('comment_seq'),$('#comment_div').attr('bbs_seq'));
		}	
		
		// OMR upload
		if($('[data-test-key]').length>0){
			objCommon.initUploadOMR();
		}
	};
	this.displayTab = function(blockID){
		$('.sub_tabs').css('display','none');
		$('#'+blockID).css('display','block');
	};
	this.fitImages = function(){
		function findTarget(ele){
			eleFind = ele.parent('.fit_target');
			if(eleFind.length>0){
				return(eleFind);
			}else{
				return(findTarget(ele.parent()));
			};
		};
		$('.fit_image[fit_size!=1]').each(function(){
//			var objImage = new Image();
			var objTarget = findTarget($(this));	
//			objImage.src = $(this).attr('src');

//			var intWidth = objImage.width;
//			var intHeight =  objImage.height;	
			var intWidth = $(this).width();
			var intHeight =  $(this).height();			
			var intNewWidth,intNewHeight;
			/*
			intNewWidth = $('.workbook_cover_box').eq(0).width();
			if(!intNewWidth){
				objTarget.each(function(){
					intNewWidth = $(this).width();
				});			
			}
			*/
			objTarget.each(function(){
				intNewWidth = $(this).width();
				intNewHeight = $(this).height();	
			});
			console.log(intNewHeight);
			/*
			if($(this).attr('src')=='http://files.itworld.co.kr/image/avatar/article/2013/June/just2kill@gmail.com/DT_Haswell_i7_FB_500_0.jpg'){
				alert(intNewWidth);
			}
			*/			
			var targetRate = intNewWidth/intNewHeight;
			var imageRate = intWidth/intHeight;
			
			if(targetRate < imageRate){
				var widthRate = intNewHeight/intHeight;
				var margin = 0 - (intWidth*widthRate) / 2;
				//$(this).css({'padding-left':'50%'});
				//$(this).css({'margin-left':margin+'px'});
				$(this).css({'height':'100%'});
			}else{
				var heightRate = intNewWidth/intWidth;
				var margin = 0 - (((intHeight*heightRate) - intNewHeight)/2);
				$(this).css({'margin-top':margin});				
				$(this).css({'width':'100%'});
			}
			$(this).css({ 'display':'' });
			$(this).attr({'fit_size':1});
		});			
	};
	this.frmSearch = function(){
		var eleForm = $('#frmSearch');
		var options = { 
				url: '/smart_omr/_common/elements/book_list_body.php',
				data:{},
				dataType: 'html',
				resetForm:false,
				type:	'post',       // 'get' or 'post', override for form's
				// 'method' attribute
				beforeSubmit:function(){
				},
				success:function(htmlResult){
					if($.trim(htmlResult)!=''){
						$('#book_list_div').html(htmlResult);
						objCommon.fitImages();
					}else{
						$('#book_list_div').html('<div class="workbook_cover col-xs-12 col-sm-12 col-md-12 col-lg-12"><span>조회 내역이 없습니다.</span></div>');
					}		 			
					$('#loading').css('display','none');
					$('#frmSearch #before_search_key').val($('#frmSearch #search_key').val());
					objCommon.page = 2;
					objCommon.before_page = 1;
				}
		}; 		
		eleForm.ajaxSubmit(options);			
	};
	this.frmReset = function(){
		$("#frmSearch input").each(function(){
			$('#frmSearch #'+this.id).val('');
		});
	};
	this.logOut = function(){
		$.ajax({
			url: '/_connector/yellow.501.php',
			data:{'viewID':'SOMR_LOG_OUT'},
			type: 'POST',
			dataType: 'json',
			beforeSend:function(){
			},
			success: function(jsonResult){
				location.href='/smart_omr';
			}
		});
	};
	this.sendMail = function(strType){
		var eleForm = $('#frmMail');
		var options = { 
				url: '/_connector/yellow.501.php',
				data:{'viewID':'SEND_MAIL','mail_type':strType},
				dataType: 'json',
				resetForm:false,
				type:	'POST',       // 'get' or 'post', override for form's
				beforeSubmit:function(){
					if( $('#frmMail #sender_email').length>0 && !$('#frmMail #sender_email').val().match(/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)){
						alert("이메일 주소가 유효하지 않습니다");
						$('#frmMail #sender_email').focus();
						return false;
					}
					if( $('#frmMail #receiver_email').length>0 && !$('#frmMail #receiver_email').val().match(/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)){
						alert("이메일 주소가 유효하지 않습니다");
						$('#frmMail #receiver_email').focus();
						return false;
					}
					if(!objRegistration.userFrmChk('frmMail')){
						return false;
					}
				},
				success:function(jsonResult){
					if(jsonResult.result){
						halert(jsonResult.message);
						location.href='/smart_omr/';
					}else{
						halert('전송오류! 잠시후 다시 시도해 주세요');
					}		 			
				}
		}; 		
		eleForm.ajaxSubmit(options);			
	};
	/********************
	 *  comment
	 * ******************/
	this.saveComment = function(){
		var options = {
			url : '/_connector/yellow.501.php',
			data : {
				'viewID' : "SAVE_SOMR_COMMENT"
			},
			dataType : 'json',
			resetForm : true,
			type : 'post', // 'get' or 'post', override for form's 'method'
							// attribute
			beforeSubmit : function() {
				if($.trim($('#frmComment #comment').val())==''){
					$('#frmComment #comment').focus();
					return false;
				}
				return (true);
			},
			success : function(jsonResult) {
				if (jsonResult.boolResult) {
					objCommon.getComment(jsonResult.post_seq,jsonResult.bbs_seq);
				}else{
				}
			}
		};
		$('#frmComment').ajaxSubmit(options);
	};
	this.getComment = function(post_seq,bbs_seq){
		$.ajax({
			url : '/smart_omr/_common/elements/comment.php',
			data : {
				'post_seq' : post_seq,
				'bbs_seq' : bbs_seq
			},
			type : 'POST',
			dataType : 'text',
			beforeSend: function() {
			},
			success : function(resultHtml) {
				$('#comment_div').html(resultHtml);
				$("#frmComment #comment").keypress(function(e){
				    if(e.which==13){
					     $('#btn_comment').click();
					     e.preventDefault()
				    }
				});
			}
		});
	};
	this.deleteComment = function(post_seq,bbs_seq,comment_seq){
		$.ajax({
			url : '/_connector/yellow.501.php',
			data : {
				'viewID' : 'DELETE_SOMR_COMMENT',
				'comment_seq' : comment_seq,
				'post_seq' : post_seq,
				'bbs_seq' : bbs_seq
			},
			type : 'POST',
			dataType : 'json',
			beforeSend: function() {
			},
			success : function(jsonResult) {
				if(jsonResult.result){
					objCommon.getComment(post_seq,bbs_seq);
				}
			}
		});
	};
	this.initUploadOMR = function(){
		var upload_button = $('[data-test-key]');
		$('[data-test-key]').each(function(){
			var eleBtn = $(this);
			new AjaxUpload(eleBtn, {
				action: '/_connector/yellow.501', 
			    data: {
			        'viewID': 'SOMR_UPLOAD_OMR',
			        'test_key': eleBtn.data('test-key')
			    },			
			   responseType: 'json',
				name: 'OMR',
				onSubmit : function(file, ext){
					if(ext.toLowerCase()!="jpeg" && ext.toLowerCase()!="jpg" && ext.toLowerCase()!="png"){
						alert("jpg 또는 png 파일만 업로드 가능합니다.");
						return(false);
					}
					this.disable();
				},
				onComplete: function(file,response){
					this.enable();
					if(response.boolResult){
						halert('답안이 전송되었습니다.');
						location.href="/smart_omr/exercise_book/test_result.php?t="+response.str_test_seq;
					}else{
						halert("테스트 저장 오류!");
					}
				}
			});		
		});
	}
}


function hconfirm(strMessage,fncTrueCallBack,fncFalseCallack){
	if(confirm(strMessage)){
		console.log(fncTrueCallBack);
		//fncTrueCallBack.call();
	}else{
		console.log(fncFalseCallack);
		//fncFalseCallack.call();
	}
}
function halert(strMessage,fncOkCallBack){
	alert(strMessage);
	if(fncOkCallBack!="undefined"){
		//fncOkCallBack.call();
	}else{
		// modal close
	}
}