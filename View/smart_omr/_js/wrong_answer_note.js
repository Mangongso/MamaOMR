$(document).ready(function() {
	objWAN = new WrongAnswerNote();
	objWAN.init();
});
function WrongAnswerNote(){
	this.init = function(){
		if($('[data-wrong-answer]').length>0){
			this.intiWrongAnswerModal();
		}
		if($('.user_answer_radio label').length){
			$('.user_answer_radio label').on('click',function(){
				objWAN.nextQuestion($(this));
			});
		}
	}
	this.intiWrongAnswerModal = function(){
		$('[data-wrong-answer]').click(function(){
			var strModalTarget = "#modal-wa-editor";
			var strMOdalAjaxURL = "../my_page/wrong_answer_note_editor.php";	
			$(strModalTarget+' .uk-modal-body').load(strMOdalAjaxURL,{answer_seq:$(this).data('wrong-answer')},function(){
		    	if($('#btn_upload').length>0){
		    		objWAN.initUploadImage();
		    	}
		    	if($('#btn_ocr').length>0){
		    		$('#btn_ocr').click(function(){
		    			objWAN.runOrcImage();
		    		});
		    	}
				if($('#frm_wrong_note').length>0){
					objWAN.initWrongAnswerForm();
				}
			});
			UIkit.modal(strModalTarget).show();
			return;
		});
	}
	this.initWrongAnswerForm = function(){
	    var options = { 
				url: '/_connector/yellow.501.php',
				data:{'viewID':"SOMR_SAVE_WRONG_ANSWER"},
		        dataType: 'json',
		        resetForm:false,
		        type:	'post',       // 'get' or 'post', override for form's 'method' attribute 		    		
		 		beforeSubmit:function(){
		 			if(!$.trim($('#wrong_note_key').val()) && !$.trim($('#wrong_note_upload_key').val()) && !$.trim($('#question').val())){
		 				halert("사진을 업로드 하거나 문제를 입력해주세요");
		 				return(false);
		 			}
				},
		 		success:function(jsonResult){
		 			if(jsonResult.result){
		 				halert("저장이 완료 되었습니다.");
		 				$('#wrong_answer_note').load("../exercise_book/_elements/wrong_note_list.php",{t:$.url().param('t'),revision:$.url().param('revision')},function(){
		 					UIkit.modal($("#modal-wa-editor")).hide();
		 					objWAN.init();
		 					objWAN.getWrongNoteTest($('.sub_content_top_menu .fa-refresh').attr('t'),$('.sub_content_top_menu .fa-refresh').attr('revision'));
		 					
		 				});		 				
		 			}
				}
		    }; 		
		$('#frm_wrong_note').ajaxForm(options);			
	}
	this.initUploadImage = function(){
		var upload_payment_status_button = $('#btn_upload');
		new AjaxUpload(upload_payment_status_button, {
			action: '/_connector/yellow.501', 
		    data: {
		        'viewID': 'UPLOAD_WRONG_NOTE_IMAGE'
		    },			
		   responseType: 'json',
			name: 'upload_files',
			onSubmit : function(file, ext){
				if(ext!="jpg" && ext!="png"){
					alert("jpg 또는 png 파일만 업로드 가능합니다.");
					return(false);
				}
				this.disable();
			},
			onComplete: function(file,response){
				this.enable();
				if(response.result){
					$('#modal-wa-editor #question_img').attr({'src':'../_images/image_viewer.php?k='+response.uploaded_key});
					$('#modal-wa-editor #question_img').data('img_mode','tmp');
					$('#wrong_note_file_name').val(response.file_name);
					$('#wrong_note_upload_key').val(response.uploaded_key);
				}
			}
		});		
	};
	this.runOrcImage = function(){
		if(!$('#question_img').data('img_mode')){
			halert("이미지를 업로드 하세요");
			return(false);
		}
		var objDummy = new Image();
		objDummy.src = $('#question_img').attr('src');
		var strImgType = $('#question_img').data('img_mode');
		$.ajax({
			url: '/_connector/yellow.501.php',
			data:{'viewID':'SOMR_OCR','image':objDummy.src,'img_type':strImgType},
			type: 'POST',
			dataType: 'json',
			beforeSend:function(){
			},
			success: function(jsonResult){
				if(jsonResult.result){
					$('#question').val(jsonResult.question);
				}else{
					halert("변환할 이미지가 없습니다.");
				}
			}
		});		
	};
	this.nextQuestion = function(objQuestion){
		var questionCnt = $('.quiz-question').length;
		var selectQuestionIndex = $('.quiz-question:visible').index()-1;
		var selectQuestion = $('.quiz-question:visible').attr('question_seq');
		var selectAnswer = objQuestion.find('input').val();
		///check answer result
		if(typeof selectAnswer=='undefined'){
			var arrAnswer = arrAnswerSeq[selectQuestion].split("|");
			var arrSubSnswer;
			
			$('.quiz-question:visible').find('input[type=text]').each(function(index){
				arrSubSnswer = arrAnswer[index].split(",");
				if($.inArray($.trim($(this).val()).replace(/ /g, ''), arrSubSnswer)==-1){
					selectAnswer = "answer_wrong";
				}
			});
		}
		selectAnswer = $.trim(selectAnswer).replace(/ /g, '');
		//console.log(arrAnswerSeq[selectQuestion]+'||');
		//console.log(selectAnswer);
		if(selectAnswer=='' || typeof selectAnswer=='undefined'){
			alert('답을 선택해주세요.^o^');
			return false;
		}else if(arrAnswerSeq[selectQuestion].indexOf(selectAnswer) != -1){
			//resultMsg = recordViewFlg?'정답입니다!! (^0^)\n\n해설 : '+questionCommentary:'정답입니다!! (^0^)';
			resultMsg = "정답입니다.";
		}else{
			//resultMsg = recordViewFlg?'아쉽네요! 다음번엔 맞추실 수 있을 거에요! (^_^)\n\n해설 : '+questionCommentary:'아쉽네요! 다음번엔 맞추실 수 있을 거에요! (^_^)';
			resultMsg = "틀렸습니다.";
		}
		alert(resultMsg);
		$('.quiz-question').css('display','none');
		$('.quiz-question .ans_correct').removeClass('active');
		if(questionCnt==(selectQuestionIndex+1)){
			alert("마지막 문제 입니다. 처음부터 다시 시작합니다.");
			$('.quiz-question').eq(0).css('display','block');
		}else{
			$('.quiz-question').eq(selectQuestionIndex+1).css('display','block');
		}
	};
	this.getWrongNoteTest = function(t,revision){
		$.ajax({
			url: '/smart_omr/exercise_book/_elements/wrong_note_test.php',
			data:{'t':t,'revision':revision},
			type: 'POST',
			dataType: 'html',
			beforeSend:function(){
			},
			success: function(htmlResult){
				$('#wrong_answer_test').html(htmlResult);
				$('.user_answer_radio label').on('click',function(){
					objWAN.nextQuestion($(this));
				});
			}
		});		
	};
}