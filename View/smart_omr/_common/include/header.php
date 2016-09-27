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
<meta name="description" content="만공소 스마트 OMR">
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
	href="/smart_omr/_css/pure-css-menu/css/layouts/side-menu.min.css">
<link rel="stylesheet"
	href="/smart_omr/_css/pure-css-menu/css/layouts/scrollable_menu.min.css">
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

<!-- javascript move bottom  -->

<!-- end javascirpt move bottom -->
</head>
<body>