<?php
$strQuery = sprintf("select count(*) as cnt from member_basic_info where member_id='%s' and (pwd=password('%s') or pwd=old_password('%s')) and auth_flg=1 and del_flg='0'",$strMemberId,$strPassword,$strPassword);
?>