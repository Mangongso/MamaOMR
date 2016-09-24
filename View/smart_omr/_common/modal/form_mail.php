<!-- Modal -->
<div id="form_mail" class="modal fade bs-example-modal-sm" tabindex="-1"
	role="dialog" aria-labelledby="form_mail" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">
					<i class="fa fa-th-list" aria-hidden="true"></i> OMR 등록
				</h4>
				<!-- h4 class="modal-title" id="myModalLabel"><i class="fa fa-th-list" aria-hidden="true"></i> OMR 등록</h4 -->
			</div>
			<div class="modal-body">
				<!-- ################################################################### -->
				<form>
					<div class="form-group">
						<label class="control-label" for="recipient-name2">Recipient:</label>
						<input type="text" class="form-control" id="recipient-name2" />
					</div>
					<div class="form-group">
						<label class="control-label" for="message-text2">Message:</label>
						<textarea class="form-control" id="message-text2"></textarea>
					</div>
				</form>
				<div class="h_dot h_dot_li h_dot_mp-0">
					<div class="h_dot_box">
						<ul>
							<li>테스트 명 입력 란에 테스트 명을 입력하십시오. (ex.1과목 데이타 베이스의 이해)</li>
							<li>보기 형식 선택에서 테스트에 사용될 객관식 답의 형식을 선택하십시오.</li>
						</ul>
					</div>
					<!-- ################################################################### -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-lg  col-xs-6"
						data-dismiss="modal">
						<i class="fa fa-times" aria-hidden="true"></i> 닫기
					</button>
					<button type="button"
						onclick="location='/smart_omr/exercise_book/registration_detail_activation.php'"
						class="btn btn-primary btn-lg  col-xs-6">
						<i class="fa fa-plus" aria-hidden="true"></i> 만들기
					</button>
				</div>
			</div>
		</div>
	</div>
</div>