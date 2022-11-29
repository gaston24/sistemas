<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	 
require_once __DIR__.'/../class/remito.php';

$user = $_SESSION['codClient'];
$_SESSION['usuario'] = $_GET['usuario'];
$_SESSION['ultimoCodigo'] = '';

$_SESSION['rem'] = trim($_GET['rem']);
$rem = $_SESSION['rem'];

//BUSCA EL REMITO EN EL LOCAL
$remito = new Remito();
$result = $remito->buscarRemitoPorLocal($rem);

if( count($result) > 0 ){

	//BUSCA QUE NO ESTE CONTROLADO EL REMITO
	//SI YA FUE CONTROLADO, TE ENVIA AL INDEX CON CARTEL DE AVISO

	if( $result[0]['TALONARIO'] != 0 ) { 
		$_SESSION['conteo'] = 2;
		header("Location:index.php");
	}

	//BUSCA QUE ESTE INGRESADO EL REMITO
	//SI NO FUE INGRESADO, TE ENVIA AL INDEX CON CARTEL DE AVISO
	if( $result[0]['ESTADO'] == 'P' ) { 
		$_SESSION['conteo'] = 3;
		header("Location:index.php");
	}

	$_SESSION['nro_sucurs'] = $result[0]['NRO_SUCURS'];
	$_SESSION['suc_orig'] = $result[0]['SUC_ORIG'];
	$_SESSION['suc_destin'] = $result[0]['SUC_DESTIN'];
	$_SESSION['fecha_mov'] = $result[0]['FECHA_MOV'];


	// BORRAR TABLAS
	// CONTROL Y AUX 
	$remito->deleteControlRemitoTables($user);

	//TRAER MAESTRO DE ARTICULOS 
	$maestroArt = $remito->traerMaestroDeArticulos();

	?>
	<script>
	
	document.addEventListener("DOMContentLoaded", ()=>{

		localStorage.setItem('maestroArt', '');

		let maestroArt = '<?=$maestroArt?>'
		
		localStorage.setItem('maestroArt', maestroArt)

		setTimeout(function () {window.location.href= 'controlRemitos.php';}, 1000);
	})
	
	</script>
	<?php
}else{
	$_SESSION['conteo'] = 1;
	header("Location:index.php");
}


}
?>