<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
	

include_once __DIR__.'/../class/remito.php';

$this->conn = new Conexion;

$cid = $this->conn->conectar('central');



$codClient = $_SESSION['codClient'];



for($i=0;$i<count($_GET['codArticu']);$i++){
	
	$rem = $_GET['rem'][$i];
	$id = $_GET['id'][$i];
	$codArticu = $_GET['codArticu'][$i];
	$observ = $_GET['observ_local'][$i];
	
	//echo $rem.' '.$codArticu.' '.$observ.'<br>';
	
	$sql= 
	"
	UPDATE SJ_CONTROL_AUDITORIA SET OBSERVAC_LOCAL = '$observ'
	WHERE NRO_REMITO = '$rem' AND COD_ARTICU = '$codArticu'
	AND ID = $id AND COD_CLIENT = '$user'	
	";
	
	sqlsrv_query($cid, $sql) or die(exit("Error en odbc_exec"));
}

}
?>
<script>setTimeout(function () {window.location.href= 'historial.php';},1);</script>