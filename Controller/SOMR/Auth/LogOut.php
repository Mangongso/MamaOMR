<?
/**
 * @Controller 로그아웃
 * 세션 초기화 ,세션파괴
 */

session_unset(); //세션변수 초기화
session_destroy(); //세션 파괴

$boolResult=true;

/**
 * View OutPut Data 세팅 
 * OutPut Type Json
 * 
 * @property	boolean 		$boolResult 			: 로그아웃 결과 성공 여부
 */
$arrResult = array(
		'boolResult'=>$boolResult
);
echo json_encode($arrResult);
?>