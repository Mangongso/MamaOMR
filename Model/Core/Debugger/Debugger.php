<?php
/**
 * Debug
 * @category     	Debug
 */
class Debug{
	
	/**
	 * 에러 핸들링
	 *
	 * @param array $arr_error_information : 에러 정보
	 * @return string $str_error_message : 에러 메세지를 반환
	 */
	function errorHandlering($arr_error_information){
		require_once("Model/Core/Debugger/errorCode.php");
		if($arr_error_information[code]){
			$str_error_message =  "Error code [".$arr_error_information[code]."] / view ID [".$arr_error_information[viewID]."] : ".constant($arr_error_information[code]);
			return($str_error_message);
		}
	}
}
?>