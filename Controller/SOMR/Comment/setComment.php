<?php
/* include package */
require_once ('Model/Core/DBmanager/DBmanager.php');
require_once ('Model/BBS/BBS.php');
require_once ('Model/BBS/Post.php');
require_once ('Model/BBS/Comment.php');
require_once ('Model/Member/Member.php');

/* set variable */
$strName = $_SESSION['smart_omr']['name'];
$intBBSSeq = $_POST['bbs_seq'];
$intPostSeq = $_POST['post_seq'];
$strComment = $_POST['comment'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objPost = new Post($resMangongDB);
$objComment = new PostComment();
$objMember = new Member($resMangongDB);

/* main process */
include(CONTROLLER_NAME."/Auth/checkAuth.php");
//check auth
if($intAuthFlg!=AUTH_TRUE){
	header("HTTP/1.1 301 Moved Permanently");
	header('location:/');
	exit;
}
//get member info 
$arrMember = $objMember->getMemberByMemberSeq($_SESSION['smart_omr']['member_key']);

$arr_input = array(
		'cmt_name'=>$strName,
		'reg_id'=>$arrMember[0]['member_seq'],
		'comment'=>$strComment,
		'post_seq'=>$intPostSeq,
		'bbs_seq'=>$intBBSSeq
);
$boolResult = $objComment->setPostComment($resMangongDB,$arr_input);

/* make output */
$arr_output['result'] = array(
	'boolResult'=>$boolResult,
	'post_seq'=>$intPostSeq,
	'bbs_seq'=>$intBBSSeq
);
echo json_encode($arr_output['result']);
?>