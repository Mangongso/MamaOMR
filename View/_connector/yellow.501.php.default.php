<?php
session_start();

if (PHP_MAJOR_VERSION >= 7) {
	set_error_handler(function ($errno, $errstr) {
		return strpos($errstr, 'Declaration of') === 0;
	}, E_WARNING);
}

/* set session env */
session_cache_limiter("private");

// ini_set("include_path",DIRECTORY_SEPARATOR."home".DIRECTORY_SEPARATOR."roundmatch".DIRECTORY_SEPARATOR."v1.1");

$sv_name = $_SERVER['SERVER_NAME'];
$arrTemp = explode(".",$sv_name);
$strServerType = $arrTemp[0];  
switch($strServerType){
  case('stage'):
		ini_set("include_path","/home/ManGong/Live");
		define("POST_FILE_UPLOAD_DIR","/home/idgkr/UpLoadfile");  
		define("CONTROLLER_NAME","Controller");	
		define("CONFERENCE_DOMAIN","beta.conf.idg.co.kr");
  break;
  case('www'):
  case('mamaomr'):
		ini_set("include_path","/home/ManGong/Live");
		define("POST_FILE_UPLOAD_DIR","/home/idgkr/UpLoadfile");  
		define("CONTROLLER_NAME","Controller");		
		define("CONFERENCE_DOMAIN","conf.idg.co.kr");	
  break;  	  	
  default:
  	ini_set("include_path","/home/zmania/git/MamaOMR");
  	define("QUESTION_FILE_DIR","/home/zmania/git/MamaOMR/Files/Questions");
  	define("OMR_FILE_DIR","/home/zmania/git/MamaOMR/Files/OMR");
  	define("CONTROLLER_NAME","Controller");
  	break;  
}
  
define("DR",$_SERVER["DOCUMENT_ROOT"]);

$XML_mapping_file = "ControllerMapping.xml";
include(CONTROLLER_NAME."/_Lib/Constant.php");
include(CONTROLLER_NAME."/_Lib/Controller.php");
?>