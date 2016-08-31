<?php
/* include package */
require_once ('Model/Core/DBmanager/DBmanager.php');
require_once ('Model/BBS/BBS.php');
require_once ('Model/BBS/Comment.php');

/* set variable */
$intBBSSeq = $_POST['bbs_seq'];
$intPostSeq = $_POST['post_seq'];
$strComment = $_POST['comment'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objComment = new PostComment();

/* main process */
$arr_input = array('bbs_seq'=>$intBBSSeq,'post_seq'=>$intPostSeq);
$arrResult = $objComment->getPostComment($resMangongDB,$arr_input);

/* make output */
$arr_output['comment'] = array(
	'comment'=>$arrResult,
	'post_seq'=>$intPostSeq,
	'bbs_seq'=>$intBBSSeq,
	'member_key'=>$_SESSION['smart_omr']['member_key']
);
?>