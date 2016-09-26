<!-- ############################################################### -->
<!-- ######################### Modal LOGIN ######################### -->
<!-- ############################################################### -->
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/elements/login.php");?>

<!-- ############################################################## -->
<!-- ######################### Modal ISBN ######################### -->
<!-- ############################################################## -->
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/modal/about_ISBN.php");?>

<!-- ############################################################### -->
<!-- ######################### Modal eMail ######################### -->
<!-- ############################################################### -->
<?php  include($_SERVER["DOCUMENT_ROOT"]."/smart_omr/_common/modal/form_mail.php");?>


<!-- header javascript  -->
<!--##################################################################-->
<!--######################### Script Include #########################-->
<!--##################################################################-->
<script src="http://code.jquery.com/jquery-1.12.4.min.js"
	integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
	crossorigin="anonymous"></script>
<script src="/smart_omr/ext_lib/jquery/jquery.form.js"></script>
<script src="/smart_omr/ext_lib/jquery/purl.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/uikit.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/grid.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/slideshow-fx.min.js"></script>
<script src="/smart_omr/ext_lib/uikit/js/components/slideshow.min.js"></script>
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

<!-- header javascript  end -->

<!-- ################################################################## -->
<!-- ######################### PureCSS GNB JS ######################### -->
<!-- ################################################################## -->
<script src="/smart_omr/_js/purecss/js/ui.min.js"></script>

<!-- ############################################################# -->
<!-- ######################### LOGIN API ######################### -->
<!-- ############################################################# -->
<? if(!$_SESSION['smart_omr']['member_key']){ ?>
<!-- kakao -->
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="/smart_omr/_js/ka_social.js"></script>
<!-- facebook -->
<script src="/smart_omr/_js/fa_social.js"></script>
<!-- naver -->
<script type="text/javascript"
	src="https://static.nid.naver.com/js/naverLogin_implicit-1.0.2.js"
	charset="utf-8"></script>
<script src="/smart_omr/_js/na_social.js"></script>
<? } ?>

<? if($viewID=="SOMR_INDEX"){ ?>
<script>
$(document).ready(function(){
<? if(count($arr_output['manager'])){ ?>
alert('<?=$arr_output['manager']['manager_msg']?>');
<? }else if(!$_SESSION['smart_omr'] && $_GET['mat']){ ?>
alert('로그인 하시면 매니저로 등록됩니다.');
UIkit.offcanvas.show('#LOGIN');
<? } ?>
});
</script>
<? } ?>

<? if($viewID=="SOMR_EXERCISE_BOOK_TEST"){ ?>
<? if($arr_output['device']['mobile_flg'] && 0){ // 해당 기능 보류 ?>
<script>
$(document).ready(function(){
	$('.ans_correct .btn-default').on('click',function(){
		var questionEle = $(this).parents('.question_div');
		objRegistration.animateBtn(questionEle);
	});
});
</script>
<? } ?>
<? } ?>
</body>
</html>