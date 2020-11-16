<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave= "Axoft1988";
$user = $_SESSION['username'];
$_SESSION['rubro'] = $_GET['rubro'];
$_SESSION['descrubro'] = $_GET['descrubro'];


$sqlLimpiar = 
"
DELETE FROM SOF_INVENTARIO WHERE USUARIO = '$user';
";

$cid=odbc_connect($dsn, $usuario, $clave);

odbc_exec($cid, $sqlLimpiar);



}
?>
<script> setTimeout(function () {window.location.href= 'area.php';},1); </script>

