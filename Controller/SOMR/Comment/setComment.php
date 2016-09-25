<?php
/**
 * @Controller 코멘트 조회
 *
 * @subpackage   	Core/DBmanager/DBmanager
 * @subpackage   	BBS/Comment
 * @subpackage   	Member/Member
 */
require_once ('Model/Core/DBmanager/DBmanager.php');
require_once ('Model/BBS/Comment.php');
require_once ('Model/Member/Member.php');

/**
 * Variable 세팅
 * @var 	$strName		코멘트 작성자명
 * @var 	$intBBSSeq		post 시컨즈
 * @var 	$intPostSeq		BBS 시컨즈
 * @var 	$intCommentSeq	코멘트 시컨즈
 */
$strName = $_SESSION['smart_omr']['name'];
$intBBSSeq = $_POST['bbs_seq'];
$intPostSeq = $_POST['post_seq'];
$strComment = $_POST['comment'];

/**
 * Object 생성
 * @property	resource 		$resMangongDB 	: DB 커넥션 리소스
 * @property	object			$objComment  				: PostComment 객체
 * @property	object			$objMember  				: Member 객체
 */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objComment = new PostComment();
$objMember = new Member($resMangongDB);

/**
 * Main Process
 */
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

/**
 * View OutPut Data 세팅 
 * OutPut Type json
 * 
 * @property	array 		$arrResult 			: 코멘트 조회 결과
 * @property	integer	$intPostSeq 			: post 시컨즈
 * @property	integer 	$intBBSSeq 			: bbs 시컨즈
 */
$arr_output['result'] = array(
	'boolResult'=>$boolResult,
	'post_seq'=>$intPostSeq,
	'bbs_seq'=>$intBBSSeq
);
echo json_encode($arr_output['result']);
?>