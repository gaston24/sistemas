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
	

	$_post = json_decode(file_get_contents('php://input'),true);
	$articulosControlados = $_post['data'];
	
	$_SESSION['articulosControlados'] = null;
	$_SESSION['articulosControlados'] = json_encode($articulosControlados);

	// TRAER TODOS LOS ARTICULOS DEL REMITO PARA LUEGO COMPARARLO

	$_SESSION['articulosRemito'] = null;
	$_SESSION['articulosRemito'] = json_encode($remito->traerTodosLosArticulosRemito($rem));

	//MARCAR REMITO COMO REGISTRADO
	$remito->marcarRemitoRegistrado($rem);


	echo true;
} catch (\Throwable $th) {
	throw $th;
}




