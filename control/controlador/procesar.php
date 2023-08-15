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

	if($_SESSION['articulosControlados'] != json_encode($articulosControlados)){
		$_SESSION['articulosControlados'] = json_encode($articulosControlados);
	}

	// TRAER TODOS LOS ARTICULOS DEL REMITO PARA LUEGO COMPARARLO

	$_SESSION['articulosRemito'] = null;
	
	$articulosRemito = $remito->traerTodosLosArticulosRemito($rem);

	$_SESSION['articulosRemito'] = json_encode($articulosRemito);

	if($_SESSION['articulosRemito'] != json_encode($articulosRemito)){
		$_SESSION['articulosRemito'] = json_encode($articulosRemito);
	}


	// LOGUEAR TODO PARA DEBUG

	error_log("-------- PROCESAR REMITO INICIO --------");
	error_log("COD CLIENTE: ".$codClient);
	error_log("USUARIO LOCAL: ".$usuarioLocal);
	error_log("REM: ".$rem);
	error_log("NRO SUCURS: ".$nroSucurs);
	error_log("SUC ORIG: ".$sucOrig);
	error_log("SUC DESTIN: ".$sucDestin);
	error_log("FECHA REM: ".$fechaRem);
	error_log("ARTICULOS CONTROLADOS: ".json_encode($articulosControlados));
	error_log("ARTICULOS REMITO: ".$_SESSION['articulosRemito']);
	error_log("-------- PROCESAR REMITO FINAL --------");



	//MARCAR REMITO COMO REGISTRADO
	$remito->marcarRemitoRegistrado($rem);


	echo true;
} catch (\Throwable $th) {
	throw $th;
}




