<?php
/**
 * @Controller 코멘트 조회
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @subpackage   	BBS/Comment
 */
require_once ('Model/Core/DBmanager/DBmanager.php');
require_once ('Model/BBS/Comment.php');

/**
 * Variable 세팅
 * @var 	$intBBSSeq		post 시컨즈
 * @var 	$intPostSeq		BBS 시컨즈
 * @var 	$intCommentSeq	코멘트 시컨즈
 */
$intBBSSeq = $_POST['bbs_seq'];
$intPostSeq = $_POST['post_seq'];
$strComment = $_POST['comment'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objComment  				: PostComment 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objComment = new PostComment();

/**
 * Main Process
 */
$arr_input = array('bbs_seq'=>$intBBSSeq,'post_seq'=>$intPostSeq);
$arrResult = $objComment->getPostComment($resMangongDB,$arr_input);

/**
 * View OutPut Data 세팅 
 * OutPut Type array
 * 
 * @property	array 		$arrResult 			: 코멘트 조회 결과
 * @property	integer	$intPostSeq 			: post 시컨즈
 * @property	integer 	$intBBSSeq 			: bbs 시컨즈
 */
$arr_output['comment'] = array(
	'comment'=>$arrResult,
	'post_seq'=>$intPostSeq,
	'bbs_seq'=>$intBBSSeq,
	'member_key'=>$_SESSION['smart_omr']['member_key']
);
?>