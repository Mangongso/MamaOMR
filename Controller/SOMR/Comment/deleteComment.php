<?php
/* include package */
require_once ('Model/Core/DBmanager/DBmanager.php');
require_once ('Model/BBS/BBS.php');
require_once ('Model/BBS/Post.php');
require_once ('Model/BBS/Comment.php');

/* set variable */
$intBBSSeq = $_POST['bbs_seq'];
$intPostSeq = $_POST['post_seq'];
$intCommentSeq = $_POST['comment_seq'];

/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objComment = new PostComment();

/* main process */
$arr_input = array(
		'cmt_id'=>$intCommentSeq,
		'post_seq'=>$intPostSeq,
		'bbs_seq'=>$intBBSSeq
);

$boolResult = $objComment->deletePostComment($resMangongDB,$arr_input);

/* make output */
$arr_output['result'] = array('result'=>$boolResult);
echo json_encode($arr_output['result']);
?>