<?php
session_start();
try {
	require_once __DIR__.'/../../class/remito.php';

	$remito = new Remito();

	$codClient = $_SESSION['codClient'];
	$usuarioLocal = $_SESSION['usuario'];
	$rem = $_SESSION['rem'];
	$nroSucurs = $_SESSION['nro_sucurs'];
	$sucOrig = $_SESSION['suc_orig'];
	$sucDestin = $_SESSION['suc_destin'];
	$fechaRem = $_SESSION['fecha_mov'];
	$codVend = $_SESSION['codVen'];
	
	$_post = json_decode(file_get_contents('php://input'),true);
	$articulosControlados = $_post['data'];

	foreach($articulosControlados as $art){
		$codArticu = $art[0];
		$cantRem = $art[1];
		$cantControl = $art[2];

		$remito->insertarAuditoria($codClient, $rem, $sucOrig, $sucDestin, $codArticu, $cantRem, $cantControl, $codVend);
	}

	echo 'Datos cargados satisfactoriamente';

} catch (\Throwable $th) {
	throw $th;
}



