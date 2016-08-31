<?php
/* include package */
require_once("Model/Core/Secure/InputFilter.php");

function import($str_file){
	$arr_file_explode = explode(".",$str_file);
	$arr_file_explode[count($arr_file_explode)-1];
	if($arr_file_explode[count($arr_file_explode)-1] == "*"){
		array_splice($arr_file_explode,count($arr_file_explode)-1);
		$str_dir = ini_get('include_path')."/".$str_sub_dir=join("/",$arr_file_explode);
		if ($dh = opendir($str_dir)) {
		    while (($file = readdir($dh)) !== false) {
		    	if($file!="." && $file!=".." && eregi("\.php$",$file)){
		        require_once($str_sub_dir."/".$file);
		    	}
		    }
		    closedir($dh);
		}
	}else{
		require_once(join("/",$arr_file_explode).".php");
	}
}
function changeCharset(&$item, $key, $arrUserData=null)
{
	if(!is_null($item)){
		$item = urldecode($item);
		if(!$arrUserData){
			if (is_string($item)==true) {
				$item = iconv('UTF-8', 'EUC-KR', $item);
			}
		}else{
			if (is_string($item)==true) {
				$item = iconv($arrUserData['inputEncoding'], $arrUserData['outputEncoding'], $item);
			}
		}
	}
}
function h_json_encode($arr)
{
	//convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
	array_walk_recursive($arr, function (&$item, $key) {
		if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	});
	$arrReturn = str_replace("\'", "'", mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8'));
	return $arrReturn;
}
function make_uri($str_glue,$arr_input){
	$arr_tmp = array();
	if(is_array($arr_input)){
		foreach($arr_input as $key=>$value){
			if($value){
				$arr_tmp[] = $key."=".$value;
			}
		}
		return(join($str_glue,$arr_tmp));
	}else{
		return(null);
	}
}
if(!function_exists("iconv_substr")){
	function iconv_substr($string,$cut_start,$cut_length,$encoding){
		$result = substr($string, $cut_start, $cut_length);
		preg_match('/^([\x00-\x7e]|.{2})*/', $result, $string_return);
		if(strlen($string)>$cut_length){
			$string_return[0]=trim($string_return[0]);
		}
		return $string_return[0];
	}
}

/* check browser */
$agent = Array();
$agent['0'] = 'unknown';
$agent['1'] = 'Microsoft Internet Explorer';
$agent['2'] = 'Netscape';
$agent['3'] = 'Opera';
$agent['4'] = 'Mozilla';
$agent['5'] = 'firefox';

function browser_check(){
	$browser = Array();
	$is_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (preg_match('/msie/i', $is_agent)){
		$matches = Array();
		preg_match("/msie ([0-9][.][0-9]{0,3})/i",$is_agent,$matches);
		$browser[0] = '1';
		$browser[1] = $matches[1];
	}elseif (preg_match('/netscape/i', $is_agent)){
		$browser[0] = '2';
		$browser[1] = preg_replace("/[^0-9+.]/","",substr($is_agent,strrpos($is_agent,'netscape')));
	}elseif (preg_match('/opera/i', $is_agent)){
		$browser[0] = '3';
		$browser[1] = '0';
	}else{
		if(preg_match('/firefox/i',$is_agent)){//파폭
			preg_match("/firefox\/([0-9]{0,2}[.][0-9]{0,3}[.][0-9]{0,3})/i",$is_agent,$matches);
			$browser[0] = '5';
			$browser[1] = $matches[1];
		}elseif(preg_match('/mozilla/i',$is_agent)){//모질라
			$browser[0] = '4';
			if(preg_match('/rv/i',$is_agent)){
			preg_match_all("/rv:(.*)\)/i",$is_agent,$matches,PREG_SET_ORDER);
			$browser[1] = $matches[0][1];
			}
		}else{
			$browser[0] = '0';
			$browser[1] = '0';
		}
	}
	return $browser;
}
function ControllerLog($strLog){
	$strLogfile = $_SERVER['DOCUMENT_ROOT']."/../Logs/ControllerLog.txt";
	$resLogFile = fopen($strLogfile,"a+");
	fwrite($resLogFile, "\r\n-------".date("Y.m.d H:i:s")."----------\r\n");
	fwrite($resLogFile, $strLog);
	fclose($resLogFile);
}
function XSSFilltering($strString){
	if(trim($strString)){
		$tags = array(); // 테그 목록
		$attr = array(); // 속성(Attributes) 목록
		$tag_method = 0; // 0:전체테그 필터링하고 테그 목록만 허용, 1:전체 허용하고 테그목록만 필터링
		$attr_method = 0; // 0:전체속성 필터링하고 속성 목록만 허용, 1:전체 허용하고 속성목록만 필터링
		$xss_auto = 1; // 1:xss 자동처리, 0:xss 자동처리 안함
		$objFilter = new InputFilter($tags, $attr, $tag_method, $attr_method, $xss_auto);
		$strResult = $objFilter->process($strString);
		$strResult = htmlentities($strResult,ENT_QUOTES, "UTF-8");
	}else{
		$strResult = trim($strString);
	}
	return($strResult);
}
// Quote variable to make safe
function quote_smart($value)
{
	global $globalMysqliConn;// this value is in controller.php
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        //$value = "'" . mysql_real_escape_string($value) . "'";
        if($globalMysqliConn){
	        $value = mysqli_real_escape_string($globalMysqliConn,$value);
        }else{
        	$value = mysql_real_escape_string($value);
        }
    }
    return $value;
}
function checkDevice(){
	$agent = $_SERVER['HTTP_USER_AGENT']; // Put browser name into local variable
	if (preg_match("/iPad/", $agent)) { // Google Device using Android OS
		$strDevice = "iPad";
		$boolMobileFlg = true;
		$strDivideType="tablet";
	}else if(preg_match("/iPhone/", $agent)) { // Apple iPhone Device
		$strDevice = "iphone";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/iPod/", $agent)) { // Google Device using Android OS
		$strDevice = "iPod";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/Android/", $agent)) { // Google Device using Android OS
		$strDevice = "Android";
		$boolMobileFlg = true;
		if(!strstr(strtolower($agent), 'mobile')){ //If there is no ''mobile' in user-agent (Android have that on their phones, but not tablets)
			$strDivideType="tablet";
		}else{
			$strDivideType = "mobile";
		}
	}else if (preg_match("/BlackBerry/", $agent)) { // Google Device using Android OS
		$strDevice = "BlackBerry";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/SymbianOS/", $agent)) { // Google Device using Android OS
		$strDevice = "SymbianOS";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/SCH-M\d+/", $agent)) { // Google Device using Android OS
		$strDevice = "SCH-M\d+";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/Opera Mini/", $agent)) { // Google Device using Android OS
		$strDevice = "Opera Mini";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/Windows CE/", $agent)) { // Google Device using Android OS
		$strDevice = "Windows CE";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/Nokia/", $agent)) { // Google Device using Android OS
		$strDevice = "Nokia";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/SonyEricsson/", $agent)) { // Google Device using Android OS
		$strDevice = "SonyEricsson";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/webOS/", $agent)) { // Google Device using Android OS
		$strDevice = "webOS";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else if (preg_match("/PalmOS/", $agent)) { // Google Device using Android OS
		$strDevice = "PalmOS";
		$boolMobileFlg = true;
		$strDivideType = "mobile";
	}else{
		$strDevice = "unknow";
		$boolMobileFlg = false;
		$strDivideType = "desktop";
	}
	$arrReturn = array('mobile_flg'=>$boolMobileFlg,'device'=>$strDivideType,'type'=>$strDevice);
	return($arrReturn);
}

//string limit
function cut($str, $len){
	if(mb_strlen($str,'UTF-8')>=$len) {
		$str = mb_substr($str,0,$len,'utf8');
		$str = $str.'..';
	}
	return $str;
}
function str_replace2( $str, $changeStr, $start, $end ){
	while( $start < $end ){
		$str[$start] = $changeStr;
		$start ++;
	}
	return $str;
}
function fileSizeFilter( $bytes ){
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
}
function json_encode2($arr){
	//convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
	array_walk_recursive($arr, function (&$item, $key) {
		if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	});
	return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
}
?>