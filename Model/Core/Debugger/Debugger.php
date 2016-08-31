<?php
class Debug{
	function errorHandlering($arr_error_information){
		require_once("Model/Core/Debugger/errorCode.php");
		if($arr_error_information[code]){
			$str_error_message =  "Error code [".$arr_error_information[code]."] / view ID [".$arr_error_information[viewID]."] : ".constant($arr_error_information[code]);
			return($str_error_message);
		}
	}
}
?>