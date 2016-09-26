
<? $confFailCnt = 0;?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="만공소 스마트 OMR">
<title>MAMA OMR Install Page</title>
<link rel="stylesheet" href="/smart_omr/ext_lib/uikit/css/uikit.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="/smart_omr/ext_lib/jquery/jquery.form.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/uikit.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/grid.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/f53dd43260.js"></script>
<style>
.install .list-group-item {
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    border-bottom-right-radius: 0px;
    border-bottom-left-radius: 0px;
}
.install .row h4,
.install .list-group-item span,
.install button {
	font-family: 'Roboto Condensed', sans-serif;
}
.install .row h4 {
    font-weight: 700;
}
.install .row small {
	position: relative;
    top: 14px;
    font-size: 11px;
}
.install .list-group-item {
    border: 1px solid #f1f1f1;
}
.install .list-group-item span {
    font-size: 13px;
    margin-right: 5px;
}
.install .list-group-item {
    font-size: 12px;
    color: #777;
}
.install .help-block {
	font-size: 13px;
    color: #777;
}
.install button {
	height: 50px;
    border-radius: 0px;
    font-size: 18px;
}
</style>
</head>
<body>
<!--###########################################################-->
<!--######################### INSTALL #########################-->
<!--###########################################################-->
<!-- ########################## -->
<div class="container install">
	<div class="row">
		<h1 class="text-center" style="text-align: center; border-bottom: 1px solid #666; padding-bottom: 20px; margin-bottom: 20px;">
			<img src="/smart_omr/_images/mama-omr-h-logo.png" alt=" " style="height: 50px;"/>
		</h1>
	</div>
	<!-- ########################## -->
	<!-- ########################## -->
	<div class="row">
		<h4 class="pull-left"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i> Connector File Setup </h4>
		<small class="pull-right"><a href="https://github.com/Mangongso/MamaOMR/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90-%EB%A9%94%EB%89%B4%EC%96%BC" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> 설치 가이드 바로 가기</a></small>
	</div>
	<!-- ########################## -->
	<!-- ########################## -->
	<div class="row">
		<ul class="list-group">
		  <li class="list-group-item">
		  	<? if(file_exists($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php")){?>
		  	<? include($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php");?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    Connector File 생성 - View/_connector/yellow.501.default.php 파일을 yellow.501.php 로 복사합니다.
		  </li>	
		  <li class="list-group-item">
		  	<? if(strpos(realpath(ini_get('include_path')),realpath($_SERVER["DOCUMENT_ROOT"]."/.."))!==false){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에  ini_set("include_path",""); 를 설정 하세요. - <?=realpath($_SERVER["DOCUMENT_ROOT"]."/..");?>
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("QUESTION_FILE_DIR") && trim(QUESTION_FILE_DIR)){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에  define("QUESTION_FILE_DIR",""); 를 설정 하세요. - <?=realpath($_SERVER["DOCUMENT_ROOT"]."/..");?>/Files/Questions
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("OMR_FILE_DIR") && trim(OMR_FILE_DIR)){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에   define("OMR_FILE_DIR","");	 를 설정 하세요. - <?=realpath($_SERVER["DOCUMENT_ROOT"]."/..");?>/Files/OMR
		  </li>
		  <li class="list-group-item">
		  	<? if(file_exists(realpath($_SERVER["DOCUMENT_ROOT"]."/..")."/Files") && substr(sprintf("%o",fileperms(realpath($_SERVER["DOCUMENT_ROOT"]."/..")."/Files")),-4)=="0777" ){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		   <?=realpath($_SERVER["DOCUMENT_ROOT"]."/..");?>/Files 디렉터리를 퍼미션은 777(rwxrwxrwx)로 생성하세요.
		  </li>			  
		</ul>
	</div>
	<!-- ########################## -->
	<!-- ########################## -->
	<div class="row">
		<h4 class="pull-left"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i> Config File Setup</h4>
		<small class="pull-right"><a href="https://github.com/Mangongso/MamaOMR/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90-%EB%A9%94%EB%89%B4%EC%96%BC" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> 설치 가이드 바로 가기</a></small>
	</div>	
	<!-- ########################## -->
	<!-- ########################## -->	
	<div class="row">
		<ul class="list-group">  	
		  <li class="list-group-item">
		  	<? if(file_exists($_SERVER["DOCUMENT_ROOT"]."/../Controller/_Config/MamaOMR.conf.php")){?>
		  	<? include($_SERVER["DOCUMENT_ROOT"]."/../Controller/_Config/MamaOMR.conf.php");?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    Config File 생성 - Controller/_Config/MamaOMR.conf.default.php 파일을 MamaOMR.conf.php 로 복사합니다.
		  </li>
		  <li class="list-group-item">
		    <? if(
		    		($DB_info['MAIN_SERVER']['host']=="MAMA_OMR_DB_HOST" || !trim($DB_info['MAIN_SERVER']['host']) ) ||
		    		($DB_info['MAIN_SERVER']['user']=="MAMA_OMR_DB_USER" || !trim($DB_info['MAIN_SERVER']['user']) ) ||
		    		($DB_info['MAIN_SERVER']['pass']=="MAMA_OMR_DB_PASSWORD" || !trim($DB_info['MAIN_SERVER']['pass']) ) || 
		    		($DB_info['MAIN_SERVER']['database']=="MAMA_OMR_DB_DATABASE" || !trim($DB_info['MAIN_SERVER']['database']) )
		    		){ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		    <? }else{ ?>
		    <span class="label label-success">Pass</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 $DB_info['MAIN_SERVER']에 DB 연결 정보를 설정하세요.
		  </li>
		  <li class="list-group-item">
		  	<?php 
		  	if($DB_info['MAIN_SERVER']['host'] && $DB_info['MAIN_SERVER']['database'] && $DB_info['MAIN_SERVER']['user'] && $DB_info['MAIN_SERVER']['pass']){
		  	if(phpversion()<7){
		  		$conn = mysql_connect($DB_info['MAIN_SERVER']['host'],$DB_info['MAIN_SERVER']['user'],$DB_info['MAIN_SERVER']['pass'],true);
		  		$conn = mysql_select_db($DB_info['MAIN_SERVER']['database']);
		  	}else{
		  		try {
		  			$conn = new PDO('mysql:host='.$DB_info['MAIN_SERVER']['host'].';dbname='.$DB_info['MAIN_SERVER']['database'],$DB_info['MAIN_SERVER']['user'],$DB_info['MAIN_SERVER']['pass']);
		  		} catch (PDOException $e) {
		  			$conn = false;
		  		}
		  	}
			}else{
				$conn = false;
		  	}
		  	?>
		    <? if($conn!==false){ ?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 $DB_info['MAIN_SERVER']설정 값에 따라 DB 연결을 확인합니다.
		  </li>
		  <li class="list-group-item">
		    <? if(
		    		($API_key['naver']['client_id']=="CLIENT_ID" || !trim($API_key['naver']['client_id'])) &&
		    		($API_key['facebook']['app_id']=="APP_ID" || !trim($API_key['facebook']['app_id'])) &&
		    		($API_key['kakao']['client_id']=="CLIENT_ID" || !trim($API_key['kakao']['client_id']))
		    		){ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		    <? }else{ ?>
		    <span class="label label-success">Pass</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 $API_key에 SNS Auth Key 를 설정하세요. Kakao,Naver,Facebook 중 한곳은 입력하여야 합니다. - 
		    SNS Auth API 바로가기 (
		    <a href="https://developers.naver.com/docs/login/overview" target="_blank">네이버</a> |
		    <a href="https://developers.kakao.com/docs/js-reference#kakao_auth" target="_blank">Kakao</a> | 
		    <a href="https://developers.facebook.com/docs/facebook-login/web" target="_blank">Facebook</a>
		    )		    
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("BOOK_SEARCH_API_KEY") && trim(BOOK_SEARCH_API_KEY)!="BOOK_SEARCH_API_KEY" && trim(BOOK_SEARCH_API_KEY)!=""){?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 define("BOOK_SEARCH_API_KEY","BOOK_SEARCH_API_KEY"); 에 도서검색 API key 를 설정하세요. -
		    <a href="https://developers.daum.net/services/apis/search/book" target="_blank">
		    다음 책 검색 API 바로가기
		    </a>
		  </li>
		  <li class="list-group-item">
		  	<? if(defined("TMP_DIR") && file_exists(TMP_DIR)){?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 define("TMP_DIR","/tmp"); 에 임시파일 디렉터리를 설정 하세요.
		  </li>		
		  <li class="list-group-item">
		  	<? if(defined("OCR_TYPE") && (OCR_TYPE=="ocr.space" || OCR_TYPE=="tesseract")){?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 define("OCR_TYPE","ocr.space"); 에 사용할 OCR을 설정하세요. - ocr.space 또는 tesseract
		  </li>			    
		  <li class="list-group-item">
		  	<? if(defined("OCR_API_KEY") && (OCR_API_KEY!="OCR_API_KEY" && trim(OCR_TYPE)!="")){?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 define("OCR_API_KEY","OCR_API_KEY"); 에 OCR API Key를 설정하세요. - <a href="https://ocr.space/OCRAPI" target="_blank">ocr.space</a> API Key 발급 안내.
		  </li>			   		  
		</ul>
	</div>
	<!-- ########################## -->
	<div class="row">
		<h4 class="pull-left"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i> Dependency Check</h4>
		<small class="pull-right"><a href="https://github.com/Mangongso/MamaOMR/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90-%EB%A9%94%EB%89%B4%EC%96%BC" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> 설치 가이드 바로 가기</a></small>
	</div>
	<div class="row">
		<ul class="list-group">  	
		  <li class="list-group-item">
		  	<? if(PHP_VERSION_ID>=50404){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    PHP Version은 5.4 이상이어야 합니다. - 현재버전 <?=phpversion()?>
		  </li>
		  <li class="list-group-item">
		  	<?  if (extension_loaded('gd') && function_exists('gd_info')) {?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    MamaOMR 는 PHP GD Library v2.1.1 이 필요 합니다. - PHP GD Library 문서 보기
		    (<a href="http://php.net/manual/kr/book.image.php" target="_blank">한글</a> | <a href="http://php.net/manualen/book.image.php" target="_blank">영문</a>)
		  </li>		
		  <li class="list-group-item">
		  	<?  if (extension_loaded('libxml')) {?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    MamaOMR 는 PHP libxml v2.9.3 이 필요 합니다. - PHP GD Library 문서 보기
		    (<a href="http://php.net/manual/kr/book.libxml.php" target="_blank">한글</a> | <a href="http://php.net/manual/en/book.libxml.php" target="_blank">영문</a>)
		  </li>			    
		  <li class="list-group-item">
		  	<? if(file_exists($_SERVER["DOCUMENT_ROOT"]."/../ExternalApps/openomr")){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    OpenOMR을 [MamaOMR 설치디렉터리]/ExternalApps/openomr 에 업로드 하세요.
		    <a href="https://github.com/Mangongso/openomr/" target="_blank">
		    OpenOMR GitHub 바로가기
		    </a>		    
		  </li>
		  <li class="list-group-item">
		  	<? if(exec("tesseract")!=""){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    tesseract 가 설치 되어야 합니다.
		    <a href="https://github.com/tesseract-ocr/tesseract" target="_blank">
		    tesseract GitHub 바로가기
		    </a>
		  </li>					  			
		  <li class="list-group-item">
		  	<? if(file_exists($_SERVER["DOCUMENT_ROOT"]."/../ExternalApps/tesseract-ocr-for-php")){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    tesseract-ocr-for-php를 [MamaOMR 설치디렉터리]/ExternalApps/tesseract-ocr-for-php 에 업로드 하세요.
		    <a href="https://github.com/Mangongso/tesseract-ocr-for-php" target="_blank">
		    tesseract-ocr-for-php GitHub 바로가기
		    </a>
		  </li>
		 </ul>
	<div class="row">
	<!-- ########################## -->		
	<div class="row">
		<p class="help-block">모든 상태가 <span class="label label-success">Pass</span> 이어야 다음단계로 진행이 가능 합니다. Check Install 버튼을 클릭하여 상태를 확인 하세요.</p>
		<br/>
		<? if($confFailCnt>0){?>
		<button type="button" class="btn-block install_bt" onclick="document.location.reload();"><i class="uk-icon-check-circle"></i> Check Inistall</button>
		<? }else{ ?>
		<button type="button" class="btn-block install_bt" onclick="install();"><i class="fa fa-cog fa-spin"></i> Install</button>
		<? } ?>
	</div>
	<!-- ########################## -->
</div>
<br/><br/>
<script>
function install(){
	if(confirm('설치를 시작 하시겠습니까?')){
		if($('span.label-danger').length>0){
			$('span.label-danger').parent().css({'color':'red'});
			alert('완료되지 않은 설정이 있습니다. Fail상태의 메세지를 확인 하세요.');
		}else{
			$.ajax({
				url: '/_connector/yellow.501.php',
				data:{'viewID':'INSTALL'},
				type: 'POST',
				dataType: 'json',
				beforeSend:function(){
				},
				success: function(jsonResult){
					if(jsonResult.result){
						if(!jsonResult.installed){
							alert("설정이 완료 되었습니다. 관리자를 설정해 주세요.");
						}else{
							alert("이미 설치되어 있습니다. 관리자 설정 페이지로 이동 합니다.");
						}
						location.href='adm_setting.php';
					}else{
						alert('설정에 문제 있습니다. 설정 내용을 다시한번 확인해 주세요.');
						location.reload();
					}
				}
			});
		}
	}
}
</script>
</body>
</html>