<div id="LOGIN" class="uk-offcanvas">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
    	<!--h2><i class="fa fa-user" aria-hidden="true"></i> 로그인</h2>
	<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="control-label sr-only">Email</label>
    <div class="col-sm-12">
      <input type="email" class="form-control input-lg" id="inputEmail3" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="control-label sr-only">Password</label>
    <div class="col-sm-12">
      <input type="password" class="form-control input-lg" id="inputPassword3" placeholder="Password">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-12">
      <button type="submit" class="btn btn-primary col-lg-12 btn-lg btn-block">로그인 <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-12">
      <div class="checkbox">
        <label>
          <input type="checkbox"> 로그인 정보 저장하기
        </label>
      </div>
    </div>
  </div>
</form-->
<div class="list-group etc_login">
            <!--a href="#" class="list-group-item active">Link</a-->
            <a href="javascript:void(0);" sns_type="facebook" event_type="login" class="list-group-item login_facebook _d_sns_btn"><i class="fa fa-facebook-official" aria-hidden="true"></i>페이스북 아이디로 로그인</a>
            <!-- 
            <a href="#" class="list-group-item login_twitter"><i class="fa fa-twitter" aria-hidden="true"></i>트위터 아이디로 로그인</a>
             -->
            <a href="javascript:$('#naver_id_login_anchor').click();" class="list-group-item login_naver"><span><img src="/smart_omr/_images/naver_logo.png" /></span>네이버 아이디로 로그인</a>
            <div id="naver_id_login" style="position:absolute;top:-1000px;"></div>
            <a href="javascript:loginWithKakao();" class="list-group-item login_kakao"><span><img src="/smart_omr/_images/kakao_logo.png" /></span>카카오톡 아이디로 로그인</a>
          </div>

	</div>
</div>