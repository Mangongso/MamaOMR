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

<!-- ################################################################## -->
<!-- ######################### PureCSS GNB JS ######################### -->
<!-- ################################################################## -->
<script src="/smart_omr/_js/purecss/js/ui.js"></script>

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
</body>
</html>