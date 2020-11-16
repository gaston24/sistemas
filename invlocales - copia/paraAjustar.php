<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$user = $_SESSION['username'];
$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

for($i=0;$i<count($_POST['ajusta']);$i++){
	
	if($_POST['ajusta'][$i]=='1' && $_POST['dif'][$i] != 0){
		//echo $_POST['id'][$i].' - '.$_POST['fecha'][$i].' - '.$_POST['codArticu'][$i].' - DIF: '.$_POST['dif'][$i].' - AJUSTA? '.$_POST['ajusta'][$i].' - OBSERVAC '.$_POST['observac'][$i].'</br>';
		
		$id = $_POST['id'][$i];
		$fecha = $_POST['fecha'][$i];
		$codArt = $_POST['codArticu'][$i];
		$cant = $_POST['cant'][$i];
		$cantStock = $_POST['cantStock'][$i];
		$dif = $_POST['dif'][$i];
		
		if(!isset($_POST['observac'][$i])){
			$observac = 'nada';
		}else{
			$observac = $_POST['observac'][$i];
		}
		
		
		$sqlMarca=
		"
		SET DATEFORMAT YMD
		UPDATE SOF_INVENTARIO_HISTORICO SET AREA = 'SI' WHERE COD_ARTICU = '$codArt' AND ID = '$id';
		";

		ini_set('max_execution_time', 300);
		odbc_exec($cid,$sqlMarca)or die(exit("Error en odbc_exec"));
		
		
		$sqlAudita =
		"
		SET DATEFORMAT YMD
		INSERT INTO SOF_INVENTARIO_AUDITA (ID_APP, FECHA, USUARIO, COD_ARTICU, CANT, CANT_STOCK, DIF, OBSERVAC) 
		VALUES ($id, '$fecha', '$user', '$codArt', '$cant', '$cantStock', $dif, '$observac');
		";
		
		ini_set('max_execution_time', 300);
		odbc_exec($cid,$sqlAudita)or die(exit("Error en odbc_exec"));
		
	}
}

echo "<script>setTimeout(function () {window.location.href= '../index.php';},1);</script>";

}
?>