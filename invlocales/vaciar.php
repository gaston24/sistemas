<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";
$cid = odbc_connect($dsn, $usuario, $clave);
$sql =	"
SET DATEFORMAT YMD
TRUNCATE TABLE SOF_INVENTARIO_FINAL
";
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

echo '

<script>

alert("Se ha vaciado el historial del inventario");
setTimeout(function () {window.location.href= "index.php";},1);</script>';

}
?>