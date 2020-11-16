<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{

//var_dump($_GET);

	$dsn_cen = '1 - CENTRAL';
	$user_cen = 'sa';
	$pass_cen = 'Axoft1988';
	
	$codArt = $_SESSION['codArt'];
	$contenedor = $_SESSION['contenedor'];
	
	$cid = odbc_connect($dsn_cen, $user_cen, $pass_cen);
	
	$pedido = $_GET['pedido'];
	
	echo '<h3 align="center">Aguarde un momento por favor</h3>';
	
	
	
	
	$sql2=
	"
	DELETE FROM SOF_DISTRIBUCION_INICIAL_RELACION
	WHERE ID =
	(
	SELECT A.ID FROM SOF_DISTRIBUCION_INICIAL_RELACION A
	INNER JOIN SOF_DISTRIBUCION_INICIAL_RELACION_VIEW B
	ON A.COD_CLIENT = B.COD_CLIENT AND A.COD_ARTICU = B.COD_ARTICU 
	WHERE NRO_PEDIDO = '$pedido'
	)
	"
	;


	odbc_exec($cid, $sql2) or die(exit("Error en odbc_exec"));
	
	
	
	$sql=
	"
	UPDATE GVA21 SET ESTADO = 5 WHERE NRO_PEDIDO = '$pedido' and TALON_PED IN (96);
	"
	;


	odbc_exec($cid, $sql)or die(exit("Error en odbc_exec"));
	
}
?>
<script>setTimeout(function () {window.location.href= 'detallePedidos.php?codArt=<?php echo $codArt ?>&contenedor=<?php echo $contenedor ?>';},1000);</script>