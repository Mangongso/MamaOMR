	<!--###########################################################################-->
	<!--######################### Registration Test Modal #########################-->
	<!--###########################################################################-->
<!-- Modal START -->
<div id="registration_test" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="registration_test" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-th-list" aria-hidden="true"></i> OMR 등록</h4>
      </div>
      <div class="modal-body">
        <!-- ########################################################## -->
			<form class="form-horizontal" id="frmTest">
			<input type="hidden" name="book_md5_seq" value="<?=$_GET['bs']?>" />
		  <div class="form-group">
		    <label for="subject" class="col-xs-12 sr-only text-left">테스트 명</label>
		    <div class="col-sm-12">
		      <input type="text" class="form-control input-lg _d_chk_input" name="subject" id="subject" placeholder="테스트 명 입력">
		    </div>
		  </div>
		  <div class="form-group form-group-top-10">
		    <label for="question_type" class="col-xs-12 sr-only  text-left">보기 형식 선택</label>
		    <div class="col-sm-12">
		      <select class="form-control input-lg _d_chk_input" name="question_type" id="question_type">
		      	  <option value="">보기 형식 선택</option>
				  <option value="11">2지 선다</option>
				  <option value="2">3지 선다</option>
				  <option value="3">4지 선다</option>
				  <option value="4">5지 선다</option>
				</select>
		    </div>
		  </div>
		</form>
		<div class="h_dot h_dot_li h_dot_mp-0">
				<div class="h_dot_box">
				<ul>
					<li>테스트 명 입력 란에 테스트 명을 입력하십시오. (ex.1과목 데이타 베이스의 이해) </li>
					<li>보기 형식 선택에서 테스트에 사용될 객관식 답의 형식을 선택하십시오.</li>
				</ul>
				</div>
        <!-- ########################################################## -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-lg  col-xs-6" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> 닫기</button>
        <button type="button" onclick="objRegistration.saveTest();" class="btn btn-primary btn-lg  col-xs-6"><i class="fa fa-plus" aria-hidden="true"></i> 만들기</button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Modal END -->