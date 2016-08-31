<?
/* main process */
session_unset(); //세션변수 초기화
session_destroy(); //세션 파괴

$boolResult=true;

/* make output */
$arrResult = array(
		'boolResult'=>$boolResult
);
echo json_encode($arrResult);
?>