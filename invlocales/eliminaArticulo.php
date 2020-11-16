<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{

//var_dump($_GET);

	$dsn_cen = '1 - CENTRAL';
	$user_cen = 'sa';
	$pass_cen = 'Axoft1988';
	$cid = odbc_connect($dsn_cen, $user_cen, $pass_cen);

	$codigo = $_GET['codigo'];
	$user = $_SESSION['username'];
		
	$sql=
	"
	DELETE FROM SOF_INVENTARIO WHERE COD_ARTICU = '$codigo' AND USUARIO = '$user'
	"
	;


	odbc_exec($cid, $sql)or die(exit("Error en odbc_exec"));
	
	$_SESSION['conteo'] = 1;
	
}
?>
<script>setTimeout(function () {window.location.href= 'recoleccion.php?area=<?php echo $_SESSION['area']; ?>';},1);</script>