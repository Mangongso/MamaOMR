<?php
include($_SERVER["DOCUMENT_ROOT"]."/_connector/yellow.501.php");
$strFile = QUESTION_FILE_DIR.DIRECTORY_SEPARATOR.$_GET['b'].DIRECTORY_SEPARATOR.$_GET['t'].DIRECTORY_SEPARATOR.$_GET['q'].DIRECTORY_SEPARATOR.$_GET['f'];
$imginfo = getimagesize($strFile);
header("Content-type: ".$imginfo['mime']);
readfile($strFile);
?>