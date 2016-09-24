<?
/**
 * Install 여부를 확인
 * 1. Controller/_Config/MamaOMR.conf.php 파일 존재 여부 확인
 * 2. DB Conn check
 * 1 fslae 시 /smart_omr/install redirect
 * 2 fslae 시 /smart_omr/install/db_conn redirect
 * */
if(!file_exists(CONTROLLER_NAME."/_Config/MamaOMR.conf.php")){
	//header("Location: /smart_omr/install");
	//exit;
}
?>