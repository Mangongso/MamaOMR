
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
</head>
<body>
<div class="container">
	<div class="row">
		<h1 class="text-center">
			<img src="/smart_omr/_images/mama-omr-logo.png" width="200" alt="MamaOMR Install Page"/>
		</h1>
	</div>
	<div class="row">
		<h4 class="pull-left">Connector File Setup </h4>
		<small class="pull-right"><i class="fa fa-mortar-board" aria-hidden="true"></i> <a href="https://github.com/Mangongso/MamaOMR/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90-%EB%A9%94%EB%89%B4%EC%96%BC" target="_blank">설치 가이드 바로 가기</a></small>
	</div>
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
		  	<? if(strpos(ini_get('include_path'),dirname($_SERVER["DOCUMENT_ROOT"],1))!==false){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에  ini_set("include_path",""); 를 설정 하세요. - <?=dirname($_SERVER["DOCUMENT_ROOT"],1);?>
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("QUESTION_FILE_DIR") && trim(QUESTION_FILE_DIR)){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에  define("QUESTION_FILE_DIR",""); 를 설정 하세요. - <?=dirname($_SERVER["DOCUMENT_ROOT"],1);?>/Files/Questions
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("OMR_FILE_DIR") && trim(OMR_FILE_DIR)){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		    yellow.501.php 파일에   define("OMR_FILE_DIR","");	 를 설정 하세요. - <?=dirname($_SERVER["DOCUMENT_ROOT"],1);?>/Files/OMR
		  </li>
		  <li class="list-group-item">
		  	<? if(file_exists(dirname($_SERVER["DOCUMENT_ROOT"],1)."/Files") && substr(sprintf("%o",fileperms(dirname($_SERVER["DOCUMENT_ROOT"],1)."/Files")),-4)=="0777" ){?>
		   <span class="label label-success">Pass</span>
		   	<? }else{ ?>
		   	<? $confFailCnt++; ?>
		   	<span class="label label-danger">Fail</span>
		    <? } ?>
		   <?=dirname($_SERVER["DOCUMENT_ROOT"],1);?>/Files 디렉터리를 퍼미션은 777(rwxrwxrwx)로 생성하세요.
		  </li>			  
		</ul>
	</div>
	<div class="row">
		<h4 class="pull-left">Config File Setup</h4>
		<small class="pull-right"><i class="fa fa-mortar-board" aria-hidden="true"></i> <a href="https://github.com/Mangongso/MamaOMR/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90-%EB%A9%94%EB%89%B4%EC%96%BC" target="_blank">설치 가이드 바로 가기</a></small>
	</div>		
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
		    MamaOMR.conf.php 파일의 $API_key에 SNS Auth Key 를 설정하세요. Kakao,Naver,Facebook 중 한곳은 입력하여야 합니다.
		  </li>	
		  <li class="list-group-item">
		  	<? if(defined("BOOK_SEARCH_API_KEY") && trim(BOOK_SEARCH_API_KEY)!="BOOK_SEARCH_API_KEY" && trim(BOOK_SEARCH_API_KEY)!=""){?>
		    <span class="label label-success">Pass</span>
		    <? }else{ ?>
		    <? $confFailCnt++; ?>
		    <span class="label label-danger">Fail</span>
		     <? } ?>
		    MamaOMR.conf.php 파일의 define("BOOK_SEARCH_API_KEY","BOOK_SEARCH_API_KEY"); 에 도서검색 API key 를 설정하세요.
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
		    MamaOMR.conf.php 파일의 define("OCR_API_KEY","OCR_API_KEY"); 에 OCR API Key를 설정하세요. - <a href="https://ocr.space/OCRAPI" target="_blank">ocr.space</a> 발급 안내.
		  </li>			   		  
		</ul>
		<p class="help-block">모든 상태가 <span class="label label-success">Pass</span> 이어야 다음단계로 진행이 가능 합니다. Check Install 버튼을 클릭하여 상태를 확인 하세요.</p>
		<br/>
		<? if($confFailCnt>0){?>
		<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="document.location.reload();"><i class="uk-icon-check-circle"></i> Check Inistall</button>
		<? }else{ ?>
		<button type="button" class="pure-button pure-form_in btn-lg btn-block install_bt" onclick="install();"><i class="fa fa-cog fa-spin"></i> Install</button>
		<? } ?>
	</div>
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
						alert('설정에 문제 있습니다. 설정 내용을 다시한반 확인해 주세요.');
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