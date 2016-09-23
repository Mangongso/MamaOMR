<?
/**
 * @Controller 오답노트 upload한 이미지의 tmp 디렉토리의 파일 정보를 json으로 가져온다.  
 */
$strFilename = $_FILES['upload_files']['tmp_name'];
$strUploadKey = md5($_FILES['upload_files']['tmp_name']);
$arrFileInfo = pathinfo($_FILES['upload_files']['tmp_name']);
$boolResult = rename($_FILES['upload_files']['tmp_name'],$arrFileInfo['dirname'].DIRECTORY_SEPARATOR.$strUploadKey);
$arrResult = array(
		'uploaded_key'=>$strUploadKey,
		'file_name'=>$_FILES['upload_files']['name'],
		'file_type'=>'image/jpeg',
		'result'=>$boolResult
);
echo json_encode($arrResult);
?>