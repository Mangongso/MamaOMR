<? $viewID = "INSTALL_ADM_SETTING"; ?>
<? include("../_common/include/header.php"); ?>
<input type="hidden" id="admin_flg" value="1"/>
<div class="row">
	<div class="col-md-3 install_body etc_login">
<ul>
	<li><img src="/smart_omr/_images/mama-omr-logo.png" class="install_logo" /></li>
	<li>관리자 계정 생성</li>
	<li>* 아래 SNS를 통해 관리자 계정을 선택 합니다. </li>
	<? if($arr_output['$API_key']['facebook']['app_id']!=''){ ?>
	<li><a href="javascript:void(0);" class="list-group-item login_facebook" onclick="if(confirm('페이스북 계정을 관리자로 등록합니다.')){checkSnsLogin($(this).attr('sns_type'),$(this).attr('event_type'),$(this));}" ><i class="fa fa-facebook-official" aria-hidden="true"></i>페이스북 아이디로 로그인</a></li>
	<? }?>
	<? if($arr_output['$API_key']['naver']['client_id']!=''){ ?>
	<li><a href="javascript:void(0);"  onclick="if(confirm('네이버 계정을 관리자로 등록합니다.')){$('#naver_id_login_anchor').click();}" class="list-group-item login_naver"><span><img src="/smart_omr/_images/naver_logo.png" /></span>네이버 아이디로 로그인</a></li>
	<? } ?>
	<? if($arr_output['$API_key']['kakao']['client_id']!=''){ ?>
	<li><a href="javascript:void(0);"  onclick="if(confirm('카카오톡 계정을 관리자로 등록합니다.')){loginWithKakao();}" class="list-group-item login_kakao"><span><img src="/smart_omr/_images/kakao_logo.png" /></span>카카오톡 아이디로 로그인</a></li>
	<? } ?>
</ul>
</div>
</div>

<? include("../_common/include/footer.php"); ?>
<? include("../_common/include/bottom.php"); ?>