<? $viewID = "INSTALL";?>
<? include("../_common/include/header.php"); ?>
<script>
function createConf(){
	$.ajax({
		url: '/_connector/yellow.501.php',
		data:{'viewID':'CREATE_CONF'},
		type: 'POST',
		dataType: 'json',
		beforeSend:function(){
		},
		success: function(jsonResult){
			if(jsonResult.result){
				alert("conf 파일 생성 완료하였습니다. 가이드를 참고 하셔서 conf파일을 설정해 주세요.");
				$('._d_btn_li').html('<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="chkInstall();"><i class="fa fa-cog fa-spin"></i> Install</button>');
				$('._d_label_conf').removeClass('label-warning');
				$('._d_label_conf').addClass('label-success');
			}else{
				alert('conf 파일 생성에 실패 하였습니다. 계속되는 오류시 다운받으신 git프로젝트에서 문의 바랍니다.');
			}
		}
	});
};

function chkInstall(){
	$.ajax({
		url: '/_connector/yellow.501.php',
		data:{'viewID':'CHK_INSTALL'},
		type: 'POST',
		dataType: 'json',
		beforeSend:function(){
		},
		success: function(jsonResult){
			if(jsonResult.result){
				alert("설정이 완료 되었습니다. 관리자를 설정해 주세요.");
				location.href='adm_setting.php';
			}else{
				switch(jsonResult.err_code){
					case(1):
						alert('conf 파일이 존재 하지 않습니다. 다시 설정해 주셔야 합니다.');
					break;
					case(2):
						alert('소셜 API Key 정보를 conf파일에 설정해 주셔야 합니다.');
					break;
					case(3):
						alert('DB connect 정보를 conf파일에 설정해 주셔야 합니다.');
					break;
				}
				location.reload();
			}
		}
	});
};
</script>
<div class="row">
	<div class="col-md-8 install_body etc_login">
		<ul>
			<li><img src="/smart_omr/_images/mama-omr-logo.png" class="install_logo" /></li>
			<li>
				<div class="col-md-12">
					<div class="col-md-4">conf 파일 생성</div>
					<div class="col-md-4"><a href="https://github.com/Mangongso/MamaOMR/wiki/Development-Document"  target="_blank">conf 파일 설정 가이드</a></div>
					<? if(!$arr_output['status']['conf']){ ?>
					<div class="col-md-4"><span class="label label-warning _d_label_conf">conf 파일 생성 필요</span></div>
					<? }else{ ?>
					<div class="col-md-4"><span class="label label-success _d_label_conf">conf 파일 생성 완료</span></div>
					<? } ?>
				</div>
			</li>
			<li>
				<div class="col-md-12">
					<div class="col-md-4">SNS API Key 설정</div>
					<div class="col-md-4"><a href="https://github.com/Mangongso/MamaOMR/wiki/Development-Document"  target="_blank">SNS API Key 설정 가이드</a></div>
					<? if(!$arr_output['status']['sns']){ ?>
					<div class="col-md-4"><span class="label label-warning _d_label_SNS">SNS API Key 필요</span></div>
					<? }else{ ?>
					<div class="col-md-4"><span class="label label-success _d_label_SNS">SNS API Key 완료</span></div>
					<? } ?>
				</div>
			</li>
			<li>
				<div class="col-md-12">
					<div class="col-md-4">DB 접속 정보 설정</div>
					<div class="col-md-4"><a href="https://github.com/Mangongso/MamaOMR/wiki/Development-Document"  target="_blank">DB 접속 정보 설정 가이드</a></div>
					<? if(!$arr_output['status']['db']){ ?>
					<div class="col-md-4"><span class="label label-warning _d_label_db">DB 접속 정보 필요</span></div>
					<? }else{ ?>
					<div class="col-md-4"><span class="label label-success _d_label_db">DB 접속 정보 완료</span></div>
					<? } ?>
				</div>
			</li>
			<li style="padding: 1px;" class="_d_btn_li">
				<? if($arr_output['status']['conf']==0){ ?>
				<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="createConf();"><i class="fa fa-cog fa-spin"></i> conf 파일 자동 생성</button>
				<? }else if($arr_output['status']['sns']==0 || $arr_output['status']['db']==0 || $arr_output['table_cnt']==0 ){ ?>
				<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="chkInstall();"><i class="fa fa-cog fa-spin"></i> Install</button>
				<? }else if( $arr_output['status']['conf'] && $arr_output['status']['sns'] && $arr_output['status']['db'] && $arr_output['table_cnt']>0 ){ ?>
				<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="location.href='adm_setting.php'"><i class="fa fa-cog fa-spin"></i> 관리자 설정가기</button>
				<? } ?>
			</li>
		</ul>
	</div>
</div>

<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>