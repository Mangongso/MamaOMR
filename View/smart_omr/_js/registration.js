$(document).ready(function() {
	objRegistration = new Registration();
	objRegistration.init();
});

function Registration() {
	this.intWindowHeight = $(window).height();
	this.init = function() {
		if($('[data-toggle="popover"]').length){
			$('[data-toggle="popover"]').popover({ html : true });
		}
		if($('#frmIsbn').length){
			$('#frmIsbn #isbn_code').on('keyup', function(e) {if(e.which == 13) {objRegistration.checkBook('frmIsbn');}});
		}
	};
	/* 해당 기능 보류
	this.animateBtn = function(ele){
		//animate top 58
		var eTop = ele.offset().top;
		console.log('eTop'+eTop);
		var intEleWindowPosition = eTop - $(window).scrollTop();
		console.log('intEleWindowPosition'+intEleWindowPosition);
		console.log('objRegistration.intWindowHeight/2'+objRegistration.intWindowHeight/2);
		
		var windowBottom = $(window).scrollTop() + $(window).height();
		var submitBtnBottom = $('.submit_btn').offset().top+$('.submit_btn').outerHeight();;
		console.log('winbot :'+windowBottom);
		console.log('submitbottom : '+submitBtnBottom);
		if( (objRegistration.intWindowHeight/2 <= intEleWindowPosition) ){
			//animate next question div to window center
			var nextETop = ele.next().offset().top;
			var intEleScrollerTop = nextETop - objRegistration.intWindowHeight/2;
			if( (windowBottom<submitBtnBottom) ){
				$('html, body').animate({scrollTop:intEleScrollerTop}, 200);
			}else{
				$('html, body').animate({scrollTop: (submitBtnBottom-$(window).height()) }, 200);
			}
		}
	};
	*/
	this.setBook = function(strFrm){
		$.ajax({
			url : '/_connector/yellow.501.php',
			data : {
				'viewID' : "SOMR_SET_BOOK",
				'title'	 : $.trim($('#'+strFrm+' #title').val()),
				'pub_name'	 : $.trim($('#'+strFrm+' #pub_name').val()),
				'isbn_code'	 : $.trim($('#'+strFrm+' #isbn_code').val()),
				'category_seq'	 : $('#'+strFrm+' #category_seq').val()
			},
			type : 'POST',
			dataType : 'json',
			beforeSend: function() {
				if(!objRegistration.userFrmChk(strFrm)){
					return false;
				}
			},
			success : function(jsonResult) {
				if(jsonResult.boolResult){
					halert('등록이 완료 되었습니다.');
					// location.href="/smart_omr/exercise_book/registration_detail.php?bs="+jsonResult.str_book_seq;
					location.href="/smart_omr/exercise_book/detail.php?bs="+jsonResult.str_book_seq;
				}else{
					switch(jsonResult.err_code){
					case(1):
						halert(jsonResult.err_msg);
						break;
					default:
						halert("ISBN 코드번호를 확인하세요");
						$('#'+strFrm+' ._d_btn_chk_isbn').css('display','block');
						$('#'+strFrm+' ._d_btn_reg_isbn').css('display','none');
						$('#'+strFrm+' ._d_btn_reg_isbn').unbind( "click" );
						$('#'+strFrm+' ._d_cover_img').attr('src','/smart_omr/_images/default_cover.png');
						break;
					}
				}
			}
		});
	};
	this.checkBook = function(strFrm){
		$.ajax({
			url : '/_connector/yellow.501.php',
			data : {
				'viewID' : "SOMR_CHECK_BOOK",
				'isbn_code'	 : $.trim($('#'+strFrm+' #isbn_code').val())
			},
			type : 'POST',
			dataType : 'json',
			beforeSend: function() {
				if(!objRegistration.userFrmChk(strFrm)){
					return false;
				}
			},
			success : function(jsonResult) {
				if(jsonResult.boolResult){
					$('#'+strFrm+' ._d_btn_reg_isbn').css('display','block');
					$('#'+strFrm+' ._d_btn_reg_isbn').on('click',function(){objRegistration.setBook(strFrm);});
					$('#'+strFrm+' ._d_btn_chk_isbn').css('display','none');
					$('#'+strFrm+' #isbn_code').attr('disabled',true);
					if(jsonResult.cover_url == "/smart_omr/_images/no_cover.png"){
						$('#'+strFrm+' #no_cover_img').css('display','block');
					}else{
						$('#'+strFrm+' #no_cover_img').css('display','none');
					}
					$('#'+strFrm+' ._d_cover_img').attr('src',jsonResult.cover_url);
					$('#'+strFrm+' ._d_book_info').css('display','block');
					$('#'+strFrm+' ._d_book_info #book_title').html(jsonResult.title);
					$('#'+strFrm+' ._d_book_info #book_publisher').html(jsonResult.pub_name);
				}else{
					halert(jsonResult.err_msg);
				}
			}
		});
	};
	this.saveTest = function(){
		var options = {
				url : '/_connector/yellow.501.php',
				data : {
					'viewID' : 'SOMR_SAVE_TEST'
				},
				dataType : 'json',
				resetForm : false,
				type : 'post', // 'get' or 'post', override for form's 'method'
								// attribute
				beforeSubmit : function() {
					if(!objRegistration.userFrmChk('frmTest')){
						return false;
					}
				},
				success : function(jsonResult) {
					if (jsonResult.boolResult) {
						$('body').append('<form id="frmRegDetail"></form>');
						$('#frmRegDetail').append('<input type="hidden" name="test_seq" value="'+jsonResult.test_seq+'">');
						$('#frmRegDetail').append('<input type="hidden" name="book_seq" value="'+jsonResult.book_seq+'">');
						$('#frmRegDetail').attr('method','post');
						$('#frmRegDetail').attr('action','/smart_omr/exercise_book/registration_detail_activation.php');
						$('#frmRegDetail').submit();
					}else{
						if(typeof jsonResult.error_msg!='undefined' && jsonResult.error_msg!=''){
							halert(jsonResult.error_msg);
						}else{
							halert("테스트 저장 오류!");
						}
						
					}
				}
			};
			$('#frmTest').ajaxSubmit(options);
	};
	this.saveTestQuestionWithAnswer = function(){
		var options = {
				url : '/_connector/yellow.501.php',
				data : {
					'viewID' : 'SOMR_SAVE_TEST_QUESTION_WITH_ANSWER'
				},
				dataType : 'json',
				resetForm : false,
				type : 'post', // 'get' or 'post', override for form's 'method'
								// attribute
				beforeSubmit : function() {
					return (true);
				},
				success : function(jsonResult) {
					if (jsonResult.boolResult) {
						$(location).attr('href','/smart_omr/exercise_book/detail.php?bs='+jsonResult.str_book_seq);
					}else{
						halert("테스트 저장 오류!");
					}
				}
			};
			$('#frmQuestion').ajaxSubmit(options);			
	};
	this.submitQuestionAnswer = function(){
		var options = {
				url : '/_connector/yellow.501.php',
				data : {
					'viewID' : 'SOMR_SUBMIT_QUESTION_ANSWER'
				},
				dataType : 'json',
				resetForm : true,
				type : 'post', // 'get' or 'post', override for form's 'method'
								// attribute
				beforeSubmit : function() {
					if(!$('.ans_correct .btn-default.active').length){return confirm('한문제도 선택되지 않앗습니다. 그대로 제출하시겠습니까?');}
				},
				success : function(jsonResult) {
					if (jsonResult.boolResult) {
						halert('답안이 전송되었습니다.');
						location.href="/smart_omr/exercise_book/test_result.php?t="+jsonResult.str_test_seq;
					}else{
						halert("테스트 저장 오류!");
					}
				}
			};
			$('#frmUserAnswer').ajaxSubmit(options);
	};
	this.insertSingleQuestion = function(intQuestionSeq){
		if(hconfirm('해당 문제 밑에 문제를 추가 하시겠습니까?')){
			return false;
		}
		//get last question seq
		intQuestionSeq = intQuestionSeq?intQuestionSeq:$('.question_div').eq(($('.question_div').length)-1).attr('data-question-seq');
		//get first order_number for reset order number
		var firstOrderNumber = $('.order_number').eq(0).val();
		console.log('question::'+intQuestionSeq);
		console.log('order::'+firstOrderNumber);
		console.log('order_number:::'+$('#question_'+intQuestionSeq+' #order_number_'+intQuestionSeq).val());
		var sendData = {
				'viewID':'INSERT_SINGLE_QUESTION',
				'test_seq':$('#frmQuestion #test_seq').val(),
				'order_number':$('#question_'+intQuestionSeq+' #order_number_'+intQuestionSeq).val(),
				'question_score':$('#question_'+intQuestionSeq+' #question_score_'+intQuestionSeq).val(),
				'question_type':$('#question_'+intQuestionSeq+' #question_type_'+intQuestionSeq).val()
				
		}
		$.ajax({
			url : '/_connector/yellow.501.php',
			data : sendData,
			type : 'POST',
			dataType : 'json',
			success : function(jsonResult) {
				if(jsonResult.boolResult){
				console.log("return questionseq : "+jsonResult.question_seq);
					objRegistration.appendSingleQuestion(sendData.test_seq,intQuestionSeq,jsonResult.question_seq,function(){
						objRegistration.autoSetOrderNumber(jsonResult.question_seq,jsonResult.order_number,firstOrderNumber);	
						$('#question_'+jsonResult.question_seq).effect( "highlight",{},2000 );
						/*
						$('.question_score').unbind('keyup');
						$('.question_score').keyup(function(){objRegistration.updateQuestionTotalInfo();});
						$('.order_number').unbind('keyup');
						$('.order_number').keyup(function(){
							var intStartIndex = $('.order_number').index($(this));
							for(i=intStartIndex;i<$('.order_number').length;i++){
								if(i==intStartIndex){
									var intOrderNo = eval($('.order_number').eq(i).val()) + 1;
								}else{
									$('.order_number').eq(i).val(intOrderNo++);
								}
							}
						});
						*/
					});
				}
			}
		});			
	};
	this.appendSingleQuestion = function(intTestsSeq,intBaseQuestionSeq,intAppendQuestionSeq,callBack){
		$.ajax({
			url : '/smart_omr/exercise_book/single_question_element.php',
			data : {
				'test_seq':intTestsSeq,
				'question_seq':intAppendQuestionSeq
			},
			type : 'POST',
			success : function(resultHtml) {
				$('#question_'+intBaseQuestionSeq).after(resultHtml);
				//objRegistration.changeAnswerSelector($('#question_'+intAppendQuestionSeq+' #question_type_'+intAppendQuestionSeq));
				//objRegistration.updateQuestionTotalInfo();			
				callBack.call();
			}
		});	
	};
	this.autoSetOrderNumber = function(intQuestionSeq,intMarkNumber,firstOrderNumber){
		var intOrderNumber = firstOrderNumber?firstOrderNumber-1:0;
		$('#frmQuestion .question_div .order_number').each(function(index){
			intOrderNumber ++;
			$(this).val(intOrderNumber);
			console.log('order:'+index);
			$('.order_number_sub').eq(index).html(intOrderNumber);
		});		
	};
	this.deleteSingleQuestion = function(intQuestionSeq){ 
		if(hconfirm('해당 문제 삭제 하시겠습니까?')){
			return false;
		}
		//get first order_number for reset order number
		var firstOrderNumber = $('.order_number').eq(0).val();
		var sendData = {
				'viewID':'DELETE_SINGLE_QUESTION',
				'test_seq':$('#frmQuestion #test_seq').val(),
				'question_seq':intQuestionSeq
		}		
		$.ajax({
			url : '/_connector/yellow.501.php',
			data : sendData,
			type : 'POST',
			dataType : 'json',
			success : function(jsonResult) {
				if(jsonResult.boolResult){
					$('#question_'+intQuestionSeq).remove();
					//objRegistration.updateQuestionTotalInfo();
					objRegistration.autoSetOrderNumber();
					objRegistration.autoSetOrderNumber(null,null,firstOrderNumber);
				}
			}
		});			
	};
	this.updateQuestionTotalInfo = function(){
		var intQuestionCount = $('.question_seq').length;
		var intTestTotalScore = Number($('#test_total_score').val());
		var intQuestionTotalScore = 0;
		$('.question_score').each(function(){
			intQuestionTotalScore = intQuestionTotalScore + Number($(this).val());
		});
		$('#question_total_info #test_total_question').html(intQuestionCount);
		if(intQuestionTotalScore > intTestTotalScore){
			$('#question_total_info #test_total_score').val(intQuestionTotalScore);
			//$('#test_over_score').html(intQuestionTotalScore-intTestTotalScore);
		}else{
			$('#question_total_info #test_total_score').val(intQuestionTotalScore);
			//$('#test_over_score').html(0);
		}
	};
	this.userFrmChk = function(strFrmId){
		var boolReturn = true;
		$("#"+strFrmId+" ._d_chk_input").each(function(){
			if( (typeof($(this).val())=="undefined" || $.trim($(this).val())=="") && $(this).is(":visible") ){
				alert($(this).attr('placeholder')+"은(는) 필수 항목입니다.");
				$(this).focus();
				return boolReturn = false;
			}
			if($(this).hasClass('_d_chk_input_email'))boolReturn=objValid.isValidEmailAddress($.trim($(this).val()));
			if($(this).hasClass('_d_chk_input_number'))boolReturn=objValid.isValidNumber($.trim($(this).val()));
			if($(this).hasClass('_d_chk_input_password'))boolReturn=objValid.isValidPassword($.trim($(this).val()));
			if($(this).hasClass('_d_chk_input_bizid'))boolReturn=objValid.isValidBizID($.trim($(this).val()));
			if(!boolReturn){
				alert($(this).attr('placeholder_cs'));
				$(this).focus();
				return boolReturn = false;
			}
		});
		return(boolReturn);
	};
}