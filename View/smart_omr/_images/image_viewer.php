<?php
$imginfo = getimagesize("/tmp/".$_GET['k']);
// var_dump($_GET['k']);
// var_dump($imginfo['mime']);
header("Content-type: ".$imginfo['mime']);
readfile("/tmp/".$_GET['k']);
?>