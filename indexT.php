<?php
require_once 'extraLarge.php';

$sql= "select top 100 * from sta11";
$ext = new extraLarge;
$t = $ext -> retornarArray($sql);
var_dump($t);
die();


?>
