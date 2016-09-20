<?php
//$imginfo = getimagesize("/tmp/".$_GET['k']);
$imginfo = getimagesize(TMP_DIR.$_GET['k']);

// var_dump($_GET['k']);
// var_dump($imginfo['mime']);
header("Content-type: ".$imginfo['mime']);
//readfile("/tmp/".$_GET['k']);
//readfile("C:/xampp/tmp/".$_GET['k']);
readfile(TMP_DIR.$_GET['k']);
?>