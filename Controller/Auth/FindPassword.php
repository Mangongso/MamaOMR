<?php
if($_SERVER['HTTP_REFERER']!='http://'.$_SERVER['SERVER_NAME'].'/login/' || $_SESSION[$_COOKIE['member_token']]){
	header('Location: /');
  	return;
}
?>