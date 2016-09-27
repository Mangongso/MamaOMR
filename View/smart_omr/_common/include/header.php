<? 
if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php") || !file_exists($_SERVER["DOCUMENT_ROOT"]."/../Controller/_Config/MamaOMR.conf.php")){
	header("location:./install/index.php");
	exit;
}else{
	include($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php"); 
}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="마마 OMR">
<title>MAMA OMR</title>
<link rel="shortcut icon" href="/_images/favicon/buygl_favicon.ico"
	type="image/x-icon">
<link rel="apple-touch-icon-precomposed"
	href="images/apple-touch-icon.png">
<!--###################################################################-->
<!--######################### PureCSS Include #########################-->
<!--###################################################################-->
<link rel="stylesheet"
	href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
<!--[if lte IE 8]>
        <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
<!--[if gt IE 8]><!-->
<link rel="stylesheet"
	href="/smart_omr/_css/pure-css-menu/css/layouts/side-menu.css">
<link rel="stylesheet"
	href="/smart_omr/_css/pure-css-menu/css/layouts/scrollable_menu.css">
<!--<![endif]-->

<!--#####################################################################-->
<!--######################### UIkit CSS Include #########################-->
<!--#####################################################################-->
<link rel="stylesheet" href="/smart_omr/ext_lib/uikit/css/uikit.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/slidenav.almost-flat.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/slideshow.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/slideshow.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/slidenav.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/dotnav.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/upload.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/upload.almost-flat.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/progress.almost-flat.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/form-file.almost-flat.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/form-file.gradient.min.css">
<link rel="stylesheet"
	href="/smart_omr/ext_lib/uikit/css/components/form-file.min.css">
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<!--#########################################################################-->
<!--######################### bootstrap CSS Include #########################-->
<!--#########################################################################-->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!--#######################################################################-->
<!--######################### MAMAomr CSS Include #########################-->
<!--#######################################################################-->
<link rel="stylesheet" href="/smart_omr/_css/core/main.css">

<? if(trim($arr_output['controller_info']['css'])){ ?>
<link href="<?=$arr_output['controller_info']['css'];?>?<?=mktime();?>"
	rel="stylesheet" type="text/css" />
<? } ?>

<!--##################################################################-->
<!--######################### Script Include #########################-->
<!--##################################################################-->
<script src="http://code.jquery.com/jquery-1.12.4.min.js"
	integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
	crossorigin="anonymous"></script>
<script src="/smart_omr/ext_lib/jquery/jquery.form.js"></script>
<script src="/smart_omr/ext_lib/jquery/purl.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/uikit.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/grid.min.js"></script>
<!-- 
<script src="/smart_omr/ext_lib/uikit/js/components/slideshow-fx.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/slideshow.min.js"></script>
 -->
<script src="/smart_omr/ext_lib/uikit/js/components/upload.min.js"></script>
<script src="//cdn.ckeditor.com/4.5.10/standard/ckeditor.js"></script>

<!--########################################################################-->
<!--######################### Bootstrap JS Include #########################-->
<!--########################################################################-->
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!--######################################################################-->
<!--######################### MAMAomr JS Include #########################-->
<!--######################################################################-->
<script src="/smart_omr/_js/common.js"></script>
<!--######################################################################-->
<? if(is_array($arr_output['controller_info']['js'])){ ?>
<? foreach($arr_output['controller_info']['js'][0] as $intKey=>$arrResult){?>
<? if(trim($arrResult)){?>
<script type="text/javascript" src="<?=$arrResult;?>?<?=time();?>"></script>
<? } ?>
<? } ?>
<? }else{ ?>
<? if(trim($arr_output['controller_info']['js'])){?>
<script type="text/javascript" src="<?=$arr_output['controller_info']['js'];?>"></script>
<? } ?>
<? } ?>

<!--###############################################################################-->
<!--######################### Fontawesome Webfont Include #########################-->
<!--###############################################################################-->
<script src="https://use.fontawesome.com/f53dd43260.js"></script>

<!--#############################################################-->
<!--######################### Naver API #########################-->
<!--#############################################################-->
<script>
/* naver api key */
naverClientID = "<?=$API_key['naver']['client_id']?>";
naverCallBackURL = "<?=$API_key['naver']['callback_url']?>";
naverDomain = "<?=$API_key['naver']['domain']?>";
/* naver api key */
 var ka_session_token = '<?=$_SESSION['smart_omr']['kakao_access_token']?$_SESSION['smart_omr']['kakao_access_token']:'undefined';?>';
 var ka_client_id = '<?=$API_key['kakao']['client_id']?>';
 /* facebook apii key */
 var fa_client_id = '<?=$API_key['facebook']['app_id']?>';
</script>
</head>
<body>