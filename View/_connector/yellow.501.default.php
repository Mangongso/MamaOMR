<?php
/**
 * ----------------------------------------
 * This file copy to yellow.501.php
 * ----------------------------------------
 */

/*
 * Set Iniclude Path - MamaOMR Install Directory 
 * ex)/home/MamaOMR
 * 다운로드 받은 MamaOMR 소스를 업로드한 디렉터리로 설정 합니다.
 */
 ini_set("include_path","");
 /*
  * Set File upload directory
  * QUESTION_FILE_DIR : Question File Directory
  * ex) /home/MamaOMR/Files/Question
  * OMR_FILE_DIR : Offline OMR Scaned file upload Directory
  * ex) /home/MamaOMR/Files/OMR
  * MamaOMR 업로드 디렉터리에 폴더를 만들고 해달 폴더명을 기입합니다.
  * 생성한 폴더의 퍼미션은 777 로 설정 하여야 웹서버가 접근 가능합니다.
  */
 define("QUESTION_FILE_DIR","");
 define("OMR_FILE_DIR","");
 /*
  * CONTROLLER_NAME : Controller directory
  * ex)Controller
  */
 define("CONTROLLER_NAME","Controller");
 /*
  * Controller 과 View 의 연결 정보를 설정한 XML 파일
  * ex) ControllerMapping.xml
  */
$XML_mapping_file = "ControllerMapping.xml";
/* 상수 설정 */
include(CONTROLLER_NAME."/_Lib/Constant.php");
/* Include Controller */
include(CONTROLLER_NAME."/_Lib/Controller.php");
?>